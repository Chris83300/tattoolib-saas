<?php

namespace App\Providers;

use App\Models\Message;
use App\Observers\ConversationObserver;
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
    ];

    public function boot(): void
    {
        // Enregistrement des observers
        Message::observe(ConversationObserver::class);

        parent::boot();
    }

    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
} 
