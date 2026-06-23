<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Jobs\SendOnboardingDayOneEmail;
use App\Jobs\SendProBenefitsReminderEmail;
use App\Jobs\SendTranscriptMonthlyReminderEmail;
use App\Mail\WelcomeVerificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailSequence implements ShouldQueue
{
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;

        // Email 1: boas-vindas + verificação — URL já gerada no contexto da request
        Mail::to($user->email)->send(new WelcomeVerificationMail($user->name, $event->verificationUrl));

        // Email 2: ativação — 24h após o registro
        SendOnboardingDayOneEmail::dispatch($user)
            ->delay(now()->addHours(24));

        // Email 3: benefícios Pro — 72h após o registro (condicional: só se sem transcrições)
        SendProBenefitsReminderEmail::dispatch($user)
            ->delay(now()->addHours(72));

        // Email 4: resumo mensal — 7 dias após o registro
        SendTranscriptMonthlyReminderEmail::dispatch($user)
            ->delay(now()->addDays(7));
    }
}
