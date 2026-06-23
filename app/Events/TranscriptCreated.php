<?php

namespace App\Events;

use App\Models\Transcript;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TranscriptCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Transcript $transcript,
        public readonly User $user,
    ) {}
}
