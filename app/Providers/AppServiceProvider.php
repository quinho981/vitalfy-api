<?php

namespace App\Providers;

use App\Events\TranscriptCreated;
use App\Events\UserRegistered;
use App\Listeners\SendFirstTranscriptionEmail;
use App\Listeners\SendWelcomeEmailSequence;
use App\Listeners\StripeEventListener;
use App\Models\Document;
use App\Models\Transcript;
use App\Observers\DocumentObserver;
use App\Observers\TranscriptObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Events\WebhookReceived;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Transcript::observe(TranscriptObserver::class);
        Document::observe(DocumentObserver::class);
        Event::listen(
            WebhookReceived::class,
            [StripeEventListener::class, 'handle']
        );

        Event::listen(
            UserRegistered::class,
            [SendWelcomeEmailSequence::class, 'handle']
        );

        Event::listen(
            TranscriptCreated::class,
            [SendFirstTranscriptionEmail::class, 'handle']
        );

        RateLimiter::for('auth', function (Request $request) {
            return [
                Limit::perMinute(5)
                    ->by($request->ip())
                    ->response(function () {
                        return response()->json([
                            'message' => 'Muitas tentativas. Tente novamente em alguns minutos.'
                        ], 429);
                    }),
            ];
        });

        RateLimiter::for('api', function (Request $request) {
            return [
                Limit::perMinute(180)
                    ->by($request->user()?->id ?: $request->ip()),
            ];
        });

        RateLimiter::for('transcripts', function (Request $request) {
            return [
                Limit::perMinute(30)
                    ->by($request->user()?->id),
            ];
        });

        RateLimiter::for('stream', function (Request $request) {
            return [
                Limit::perMinute(60)
                    ->by($request->ip()),
            ];
        });
    }
}
