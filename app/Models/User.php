<?php

namespace App\Models;

use App\Mail\ResetPasswordMail;
use App\Mail\WelcomeVerificationMail;
use App\Models\Transcript;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'google_id',
        'avatar',
        'recording_tour_completed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function transcripts(): HasMany
    {
        return $this->hasMany(Transcript::class);
    }

    public function plan()
    {
        $subscription = $this->activeSubscription()->first();

        return $subscription ? (object) ['name' => $subscription->type] : (object) ['name' => 'Free'];
    }

    public function hasProPlan(): bool
    {
        return $this->activeSubscription()->exists();
    }

    public function sendPasswordResetNotification($token): void
    {
        $frontendUrl = config('app.frontend_url');
        $resetUrl = $frontendUrl . '/auth/reset-password?token=' . $token . '&email=' . urlencode($this->email);

        Mail::to($this->email)->send(new ResetPasswordMail($resetUrl, $this->name));
    }

    public function sendEmailVerificationNotification(): void
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addHour(),
            ['id' => $this->getKey(), 'hash' => sha1($this->getEmailForVerification())]
        );

        Mail::to($this->email)->queue(new WelcomeVerificationMail($this->name, $verificationUrl));
    }

    private function activeSubscription()
    {
        return $this->subscriptions()
            ->where('stripe_status', 'active')
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>', now());
            });
    }
}
