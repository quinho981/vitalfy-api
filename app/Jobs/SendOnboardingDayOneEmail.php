<?php

namespace App\Jobs;

use App\Mail\OnboardingDayOneMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOnboardingDayOneEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly User $user) {}

    public function handle(): void
    {
        $user = User::find($this->user->id);

        if (!$user) {
            return;
        }

        if ($this->userHasUsedPlatform($user)) {
            return; // Usuário já usou a plataforma — e-mail irrelevante
        }

        $appUrl = config('app.frontend_url');

        Mail::to($user->email)->send(new OnboardingDayOneMail($user->name, $appUrl));
    }

    public function userHasUsedPlatform(User $user): bool
    {
        return $user->transcripts()->count() > 0;
    }
}
