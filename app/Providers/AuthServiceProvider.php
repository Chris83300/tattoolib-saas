<?php

namespace App\Providers;

use App\Models\AccountingTransaction;
use App\Models\Appointment;
use App\Models\Availability;
use App\Models\BookingRequest;
use App\Models\ClientCareSheet;
use App\Models\Conversation;
use App\Models\InventoryItem;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Piercer;
use App\Models\Tattooer;
use App\Models\Client;
use App\Models\TraceabilityRecord;
use App\Policies\AccountingPolicy;
use App\Policies\AppointmentPolicy;
use App\Policies\AvailabilityPolicy;
use App\Policies\BookingRequestPolicy;
use App\Policies\ClientCareSheetPolicy;
use App\Policies\ConversationPolicy;
use App\Policies\InventoryPolicy;
use App\Policies\MessagePolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PierceurPolicy;
use App\Policies\TattooerPolicy;
use App\Policies\ClientPolicy;
use App\Policies\TraceabilityPolicy;
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
        Client::class => ClientPolicy::class,
        AccountingTransaction::class => AccountingPolicy::class,
        Availability::class => AvailabilityPolicy::class,
        ClientCareSheet::class => ClientCareSheetPolicy::class,
        InventoryItem::class => InventoryPolicy::class,
        Payment::class => PaymentPolicy::class,
        Piercer::class => PierceurPolicy::class,
        TraceabilityRecord::class => TraceabilityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
