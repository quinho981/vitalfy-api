<?php

namespace App\Jobs;

use App\Mail\TranscriptLimitReminderMail;
use App\Models\Transcript;
use App\Models\User;
use App\Support\PlanLimits;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTranscriptMonthlyReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly User $user) {}

    public function handle(): void
    {
        $user = User::find($this->user->id);

        if (!$user) {
            return;
        }

        // Não envia resumo de limite para usuários Pro (sem limite mensal)
        if ($user->hasProPlan()) {
            return;
        }

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $transcriptsUsed = Transcript::fromUserBetweenDates($user->id, $startOfMonth, $endOfMonth)->count();
        $remaining = max(0, PlanLimits::FREE_MONTHLY_TRANSCRIPTS - $transcriptsUsed);

        $appUrl = config('app.frontend_url');

        Mail::to($user->email)->send(
            new TranscriptLimitReminderMail($user->name, $transcriptsUsed, $remaining, $appUrl)
        );
    }
}
