<?php

namespace App\Jobs;

use App\Mail\ProBenefitsReminderMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendProBenefitsReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly User $user) {}

    public function handle(): void
    {
        $user = User::find($this->user->id);

        if (!$user) {
            return;
        }

        // Só envia se o usuário não criou nenhuma transcrição até agora
        if ($user->transcripts()->count() > 0) {
            return;
        }

        $appUrl = config('app.frontend_url');

        Mail::to($user->email)->send(new ProBenefitsReminderMail($user->name, $appUrl));
    }
}
