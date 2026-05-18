<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\SubscriptionSyncService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookHandled;
use Laravel\Cashier\Events\WebhookReceived;

class StripeEventListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        $payload  = $event->payload;
        $type     = $payload['type'] ?? null;
        $eventId  = $payload['id']   ?? null;

        // Deduplicação — continua funcionando normalmente
        if ($eventId && Cache::has("stripe_event_{$eventId}")) {
            // Log::info("Evento duplicado ignorado: {$eventId}");
            return;
        }

        if ($eventId) {
            Cache::put("stripe_event_{$eventId}", true, now()->addMinutes(10));
        }

        Log::info("Stripe event recebido: {$type}");

        match ($type) {
            'invoice.payment_succeeded'    => $this->handleInvoicePaymentSucceeded($payload),
            'customer.subscription.created' => $this->handleSubscriptionCreated($payload),
            'checkout.session.completed'   => $this->handleCheckoutCompleted($payload),
            default => Log::info("---"),
        };
    }

    private function handleInvoicePaymentSucceeded(array $payload): void
    {
        $invoice = $payload['data']['object'];
        
        $subscriptionId =
            $invoice['subscription']
            ?? data_get($invoice, 'parent.subscription_details.subscription')
            ?? data_get($invoice, 'lines.data.0.parent.subscription_item_details.subscription');
        
        if (! $subscriptionId) return;
            
        $user = User::where('stripe_id', $invoice['customer'])->first();

        if ($user) {
            SubscriptionSyncService::syncUserData($user->id);
        } else {
            Log::warning("User not found for customer {$invoice['customer']}");
        }
    }

    private function handleSubscriptionCreated(array $payload): void
    {
        Log::info("Subscription created event received");
    }

    private function handleCheckoutCompleted(array $payload): void
    {
        Log::info("Checkout completed event received");
    }
}
