<?php

namespace App\Services;

use App\Enums\TranscriptsTypeEnum;
use App\Models\Document;
use App\Models\Transcript;
use App\Models\TranscriptType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    public function summary(?string $period = 'today'): array
    {
        $userId = Auth::id();
        [$start, $end] = $this->getPeriodDates($period);
        
        $cacheKey = "dashboard:summary:{$period}:{$userId}";
        $transcriptsCount = Cache::remember("{$cacheKey}:count", 300, function () use ($userId, $start, $end) {
                                return $this->transcriptsTodayQuery($userId, $start, $end)->count();
                            });

        $transcriptsDuration = Cache::remember("{$cacheKey}:time", 300, function () use ($userId, $start, $end) {
                                return $this->transcriptsTodayQuery($userId, $start, $end)->sum('end_conversation_time');
                            });

        $urgentTranscriptsCount = Cache::remember("{$cacheKey}:urgent", 300, function () use ($userId, $start, $end) {
                                return $this->transcriptsTodayQuery($userId, $start, $end)->where('transcript_type_id', TranscriptsTypeEnum::URGENTE->value)->count();
                            });

        $transcriptsCountWithTrashed = $this->transcriptsTodayQuery($userId, $start, $end)->withTrashed()->count();
        $documentCountWithTrashed = $this->documentsTodayQuery($userId, $start, $end)->withTrashed()->count();

        return [
            'transcriptsCountToday' => $transcriptsCount,
            'transcriptsDurationToday' => $transcriptsDuration,
            'urgentTranscriptsCountToday' => $urgentTranscriptsCount,
            'averageTranscriptsTime' => $this->averageTranscriptsTime($transcriptsDuration, $transcriptsCount),
            
            'transcriptsCountWithTrashedToday' => $transcriptsCountWithTrashed,
            'transcriptDiscarded' => $this->transcriptsTodayQuery($userId, $start, $end)->onlyTrashed()->count(),
            'documentCountWithTrashehdToday' => $documentCountWithTrashed,
            'documentDiscarded' => $this->documentsTodayQuery($userId, $start, $end)->onlyTrashed()->count()
        ];
    }

    public function charts(): array
    {
        $userId = Auth::id();
        $startWeek = now()->startOfWeek(Carbon::SUNDAY);
        $endWeek = now()->endOfWeek(Carbon::SATURDAY);

        return [
            'transcriptsByWeek' => $this->currentWeekTranscripts($userId, $startWeek, $endWeek),
            'transcriptsByType' => $this->countWeekTranscriptByType($userId, $startWeek, $endWeek)
        ];
    }

    public function latestRecentTranscripts(): Collection
    {
        $userId = Auth::id();

        return Transcript::select('id', 'patient', 'end_conversation_time', 'transcript_type_id', 'created_at')
            ->with([
                'transcriptType:id,type',
                'document:id,transcript_id,document_template_id',
                'document.documentTemplate:id,name'
            ])
            ->where('user_id', $userId)
            ->latest()
            ->limit(4)
            ->get();
    }

    private function getPeriodDates(string $period): array
    {
        return match($period) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'week' => [now()->startOfWeek(Carbon::SUNDAY), now()->endOfWeek(Carbon::SATURDAY)],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            default => [now()->startOfDay(), now()->endOfDay()],
        };
    }

    private function transcriptsTodayQuery(int $userId, Carbon $start, Carbon $end)
    {
        return Transcript::fromUserBetweenDates($userId, $start, $end);
    }

    private function documentsTodayQuery(int $userId, Carbon $start, Carbon $end)
    {
        return Document::fromUserBetweenDatesViaTranscript($userId, $start, $end);
    }

    private function averageTranscriptsTime(int|float $totalTimeTranscripts, int $totalTranscripts)
    {
        if($totalTranscripts <= 0) return 0;
        return $totalTimeTranscripts / $totalTranscripts;
    }

    private function currentWeekTranscripts(int $userId, Carbon $startWeek, Carbon $endWeek)
    {
        return Cache::remember("dashboard:charts:week:{$userId}", 600, function () use ($userId, $startWeek, $endWeek) {
            return Transcript::query()
                ->selectRaw("
                    EXTRACT(DOW FROM created_at AT TIME ZONE 'America/Sao_Paulo') as day_of_week,
                    COUNT(*) as total
                ")
                ->where('user_id', $userId)
                ->whereBetween('created_at', [$startWeek, $endWeek])
                ->groupBy('day_of_week')
                ->orderBy('day_of_week')
                ->get()
                ->toArray();
        });
    }

    private function countWeekTranscriptByType(int $userId, Carbon $startWeek, Carbon $endWeek)
    {
        return Cache::remember("dashboard:charts:type:{$userId}", 600, function () use ($userId, $startWeek, $endWeek) {
            return TranscriptType::select(['id', 'type'])
                ->withCount([
                    'transcripts' => function ($query) use ($userId, $startWeek, $endWeek) {
                        $query->where('user_id', $userId);
                        $query->whereBetween('created_at', [$startWeek, $endWeek]);
                    }
                ])
                ->get()
                ->toArray();
        });
    }

    public static function clear(int $userId)
    {
        foreach (['today', 'week', 'month'] as $period) {
            Cache::forget("dashboard:summary:{$period}:{$userId}:count");
            Cache::forget("dashboard:summary:{$period}:{$userId}:time");
            Cache::forget("dashboard:summary:{$period}:{$userId}:urgent");
            Cache::forget("dashboard:charts:{$period}:{$userId}");
            Cache::forget("dashboard:charts:type:{$period}:{$userId}");
        }
    }
}