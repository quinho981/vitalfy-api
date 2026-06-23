<?php

namespace App\Listeners;

use App\Events\TranscriptCreated;
use App\Mail\FirstTranscriptionSuccessMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendFirstTranscriptionEmail implements ShouldQueue
{
    public function handle(TranscriptCreated $event): void
    {
        $user = $event->user;
        $transcript = $event->transcript;

        // Só envia quando for a primeira transcrição do usuário
        if ($user->transcripts()->count() !== 1) {
            return;
        }

        $appUrl = config('app.frontend_url');

        Mail::to($user->email)->send(
            new FirstTranscriptionSuccessMail($user->name, $transcript->id, $appUrl)
        );
    }
}
