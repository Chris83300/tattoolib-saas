<?php

namespace App\Providers;

use App\Models\Message;
use App\Models\BookingRequest;
use App\Models\Tattooer;
use App\Observers\ConversationObserver;
use App\Observers\BookingRequestObserver;
use App\Observers\TattooerObserver;
use Laravel\Cashier\Events\WebhookReceived;
use App\Listeners\StripeSubscriptionListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\MessageCreated::class => [
            \App\Listeners\SendNewMessageNotification::class,
            \App\Listeners\UpdateUnreadCounts::class,
        ],
        \App\Events\MessageDeleted::class => [
            \App\Listeners\CleanupMessageMedia::class,
        ],
        WebhookReceived::class => [
            StripeSubscriptionListener::class,
            \App\Listeners\HandleStudioSubscriptionCreated::class,
        ],
    ];

    public function boot(): void
    {
        // Enregistrement des observers
        Message::observe(ConversationObserver::class);
        BookingRequest::observe(BookingRequestObserver::class);
        Tattooer::observe(TattooerObserver::class);

        parent::boot();
    }

    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
