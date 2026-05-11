<?php

namespace App\Providers;

use App\Listeners\StripeEventListener;
use App\Models\Document;
use App\Models\Transcript;
use App\Observers\DocumentObserver;
use App\Observers\TranscriptObserver;
use Illuminate\Support\Facades\Event;
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
    }
}
