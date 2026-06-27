<?php

namespace App\Services;

use App\Events\TranscriptCreated;
use App\Http\Requests\StoreTranscriptRequest;
use App\Jobs\ProcessGenerateInsightsAI;
use App\Mail\TranscriptLimitWarningMail;
use App\Models\Transcript;
use App\Support\AudioLimits;
use App\Support\PlanLimits;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;

class TranscriptService
{
    protected Transcript $transcript;
    protected DeepgramService $deepgramService;
    protected DocumentService $documentService;

    public function __construct(
        Transcript $transcript, 
        DeepgramService $deepgramService,
        DocumentService $documentService
    )
    {
        $this->transcript = $transcript;
        $this->deepgramService = $deepgramService;
        $this->documentService = $documentService;
    }

    public function getUserTranscripts(int $userId): LengthAwarePaginator
    {
        return $this->baseTranscriptHistoryQuery()
            ->where('user_id', $userId)
            ->paginate(10);
    }

    public function searchUserTranscripts(array $request, int $userId): Collection
    {
        $username = $request['user'] ?? null;
        $date = $request['date'] ?? null;
        $type = $request['type'] ?? null;

        $query = $this->baseTranscriptHistoryQuery()
            ->where('user_id', $userId);

        if($username) {
            $query->where('patient', 'ILIKE', "%{$username}%");
        }

        if($date) {
            $date = Carbon::parse($request['date'])->toDateString();

            $query->whereDate('created_at', $date);
        }

        if ($type) {
            $query->where('transcript_type_id', $type);
        }
         
        return $query->limit(30)->get();
    }

    private function baseTranscriptHistoryQuery(): Builder
    {
        return $this->transcript
            ->with([
                'document:id,transcript_id,document_template_id',
                'document.documentTemplate:id,name,category_id',
                'document.documentTemplate.category:id,color',
                'transcriptType:id,type',
            ])
            ->select(['id', 'transcript_type_id', 'patient', 'end_conversation_time', 'file_size', 'description', 'created_at'])
            ->selectRaw('LEFT(description, 86) as description')
            ->latest();
    }

    public function getTranscriptAndDocument(int $id): object
    {
        return $this->transcript
            ->with([
                'document:id,transcript_id,document_template_id,result,created_at,feedback',
                'document.documentTemplate:id,name',
                'document.ai_insights:id,document_id,possible_diagnoses,red_flags,case_severity,brief_description,possible_diagnoses,suggested_cid_codes,suggested_exams,suggested_conducts,missing_clinical_information'
            ])
            ->where('id', $id)
            ->firstOrFail(['id', 'patient', 'created_at', 'end_conversation_time']);
    }

    public function deleteTranscript(int $id): void
    {
        $transcript = $this->transcript->findOrFail($id);
        $transcript->delete();
    }

    public function getConversations(int $id): object
    {
        $transcript = $this->transcript
            ->where('id', $id)
            ->first(['id', 'conversation']);

        return $transcript;
    }

    private function processAudioAndBuildConversation(StoreTranscriptRequest $request): array
    {
        $file = $request->file('audio');
  
        $audio = $this->getAudioContent($file);
        $utterances = $this->deepgramService->transcribeAudio($audio['content'], $audio['mimeType']);
        $conversation = $this->organizeUtterances($utterances);

        return [
            'file' => $file,
            'utterances' => $utterances,
            'conversation' => $conversation,
        ];
    }

    public function processAudioAndCreate(StoreTranscriptRequest $request): array
    {
        $user = $request->user();
        $remainingTranscripts = null;

        [
            'file' => $file,
            'utterances' => $utterances,
            'conversation' => $conversation
        ] = $this->processAudioAndBuildConversation($request);

        $this->validateAudioDuration($utterances);

        $transcript = Transcript::create([
            'user_id' => $user->id,
            'patient' => $request['patient'],
            'conversation' => $conversation,
            'transcript_type_id' => $request['type'],
            'end_conversation_time' => $this->getLastEndUtteranceTime($utterances),
            'file_size' => $file->getSize()
        ]);

        if(!$user->hasProPlan()) {
            $remainingTranscripts = $this->getRemainingMonthlyTranscripts($user->id);
            $this->dispatchLimitWarningIfNeeded($user, $remainingTranscripts);
        }

        TranscriptCreated::dispatch($transcript, $user);

        return [
            'transcript' => $transcript,
            'remaining' => $remainingTranscripts
        ];
    }

    public function storeAndGenerateDocument(StoreTranscriptRequest $request): array
    {
        $user = $request->user();
        $remainingTranscripts = null;
    
        [
            'file' => $file,
            'utterances' => $utterances,
            'conversation' => $conversation
        ] = $this->processAudioAndBuildConversation($request);

        $this->validateAudioDuration($utterances);

        $documentContent = $this->documentService->generateLlmDocument($conversation, $request['template']);

        $document = DB::transaction(function () use ($request, $file, $utterances, $conversation, $documentContent) {
            $transcript = Transcript::create([
                'user_id' => Auth::id(),
                'patient' => $request['patient'],
                'conversation' => $conversation,
                'transcript_type_id' => $request['type'],
                'end_conversation_time' => $this->getLastEndUtteranceTime($utterances),
                'file_size' => $file->getSize()
            ]);
    
            $document = $transcript->document()->create([
                'document_template_id' => $request['template'],
                'patient' => $request['patient'],
                'result' => $documentContent,
                'transcript_id' => $request['transcript_id']
            ]);

            return $document;
        });

        if(!$user->hasProPlan()) {
            $remainingTranscripts = $this->getRemainingMonthlyTranscripts($user->id);
            $this->dispatchLimitWarningIfNeeded($user, $remainingTranscripts);
        }

        ProcessGenerateInsightsAI::dispatch($document->id, $conversation);
        TranscriptCreated::dispatch($document->transcript, $user);

        return [
            'document' => $document,
            'remaining' => $remainingTranscripts
        ];
    }

    private function dispatchLimitWarningIfNeeded($user, int $remaining): void
    {
        if ($remaining === 2) {
            Mail::to($user->email)->queue(
                new TranscriptLimitWarningMail($user->name, config('app.frontend_url'))
            );
        }
    }

    public function getRemainingMonthlyTranscripts(int $userId): int
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $usedTranscripts = Transcript::fromUserBetweenDates($userId, $startOfMonth, $endOfMonth)->count();

        return PlanLimits::FREE_MONTHLY_TRANSCRIPTS - $usedTranscripts;
    }

    private function getAudioContent(UploadedFile $file): array
    {
        $mimeType = $file->getMimeType();
        $content = file_get_contents($file->getRealPath());

        return ['content' => $content, 'mimeType' => $mimeType];
    }

    private function organizeUtterances(array $utterances): array
    {
        $conversation = [];

        foreach ($utterances as $utterance) {
            $conversation[] = [
                'speaker' => $utterance['speaker'],
                'text' => $utterance['transcript'],
                'start' => round($utterance['start'], 2),
                'end' => round($utterance['end'], 2)
            ];
        }

        return $conversation;
    }

    private function getLastEndUtteranceTime(array $utterances): int
    {
        if (empty($utterances)) return 0;

        $lastUtterance = end($utterances);
        return floor($lastUtterance['end']);
    }

    private function validateAudioDuration(array $utterances): void
    {
        $duration = $this->getLastEndUtteranceTime($utterances);

        if ($duration > AudioLimits::MAX_RECORDING_DURATION_SECONDS) {
            throw new InvalidArgumentException(
                sprintf(
                    'O áudio excede o limite máximo de %d minutos. Duração atual: %d segundos.',
                    AudioLimits::MAX_RECORDING_DURATION_SECONDS / 60,
                    $duration
                )
            );
        }
    }
}