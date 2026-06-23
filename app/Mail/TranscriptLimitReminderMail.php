<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TranscriptLimitReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $userName,
        public readonly int $transcriptsUsed,
        public readonly int $remaining,
        public readonly string $appUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Seu resumo Vitalfy — {$this->remaining} transcrições restantes este mês",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.transcript-limit-reminder',
        );
    }
}
