<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Policies\AppointmentPolicy;
use App\Policies\BookingRequestPolicy;
use App\Policies\ConversationPolicy;
use App\Policies\MessagePolicy;
use App\Models\Tattooer;
use App\Policies\TattooerPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Conversation::class => ConversationPolicy::class,
        Message::class => MessagePolicy::class,
        BookingRequest::class => BookingRequestPolicy::class,
        Appointment::class => AppointmentPolicy::class,
        Tattooer::class => TattooerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
