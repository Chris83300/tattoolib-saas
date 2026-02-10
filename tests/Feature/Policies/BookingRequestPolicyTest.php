<?php

namespace Tests\Feature\Policies;

use App\Models\User;
use App\Models\BookingRequest;
use App\Models\Tattooer;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingRequestPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Client can view own booking request
     */
    public function test_client_can_view_own_booking_request(): void
    {
        $client = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->client->id,
        ]);
        
        expect($client->can('view', $booking))->toBeTrue();
    }

    /**
     * Client cannot view other client booking request
     */
    public function test_client_cannot_view_other_client_booking_request(): void
    {
        $client1 = User::factory()->client()->create();
        $client2 = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client2->client->id,
        ]);
        
        expect($client1->can('view', $booking))->toBeFalse();
    }

    /**
     * Tattooer can view booking addressed to them
     */
    public function test_tattooer_can_view_booking_addressed_to_them(): void
    {
        $tattooer = User::factory()->tattooer()->create();
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $tattooer->tattooer->id,
            'bookable_type' => Tattooer::class,
        ]);
        
        expect($tattooer->can('view', $booking))->toBeTrue();
    }

    /**
     * Tattooer can update booking addressed to them
     */
    public function test_tattooer_can_update_booking_addressed_to_them(): void
    {
        $tattooer = User::factory()->tattooer()->create();
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $tattooer->tattooer->id,
            'bookable_type' => Tattooer::class,
        ]);
        
        expect($tattooer->can('update', $booking))->toBeTrue();
    }

    /**
     * Tattooer cannot update booking not addressed to them
     */
    public function test_tattooer_cannot_update_booking_not_addressed_to_them(): void
    {
        $tattooer1 = User::factory()->tattooer()->create();
        $tattooer2 = User::factory()->tattooer()->create();
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $tattooer2->tattooer->id,
            'bookable_type' => Tattooer::class,
        ]);
        
        expect($tattooer1->can('update', $booking))->toBeFalse();
    }

    /**
     * Client can only pay deposit when status is awaiting_deposit
     */
    public function test_client_can_only_pay_deposit_when_status_is_awaiting_deposit(): void
    {
        $client = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->client->id,
            'status' => BookingRequest::STATUS_AWAITING_DEPOSIT,
        ]);
        
        expect($client->can('payDeposit', $booking))->toBeTrue();
        
        $booking->update(['status' => BookingRequest::STATUS_PENDING]);
        expect($client->can('payDeposit', $booking))->toBeFalse();
    }

    /**
     * Tattooer can send design only after deposit paid
     */
    public function test_tattooer_can_send_design_only_after_deposit_paid(): void
    {
        $tattooer = User::factory()->tattooer()->create();
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $tattooer->tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => BookingRequest::STATUS_PENDING,
        ]);
        
        expect($tattooer->can('sendDesign', $booking))->toBeFalse();
        
        $booking->update(['status' => BookingRequest::STATUS_DEPOSIT_PAID]);
        expect($tattooer->can('sendDesign', $booking))->toBeTrue();
    }

    /**
     * Tattooer can accept pending booking
     */
    public function test_tattooer_can_accept_pending_booking(): void
    {
        $tattooer = User::factory()->tattooer()->create();
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $tattooer->tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => BookingRequest::STATUS_PENDING,
        ]);
        
        expect($tattooer->can('accept', $booking))->toBeTrue();
    }

    /**
     * Tattooer cannot accept already accepted booking
     */
    public function test_tattooer_cannot_accept_already_accepted_booking(): void
    {
        $tattooer = User::factory()->tattooer()->create();
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $tattooer->tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => BookingRequest::STATUS_ACCEPTED,
        ]);
        
        expect($tattooer->can('accept', $booking))->toBeFalse();
    }

    /**
     * Client can cancel own booking
     */
    public function test_client_can_cancel_own_booking(): void
    {
        $client = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->client->id,
            'status' => BookingRequest::STATUS_PENDING,
        ]);
        
        expect($client->can('cancel', $booking))->toBeTrue();
    }

    /**
     * Client cannot cancel other client booking
     */
    public function test_client_cannot_cancel_other_client_booking(): void
    {
        $client1 = User::factory()->client()->create();
        $client2 = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client2->client->id,
            'status' => BookingRequest::STATUS_PENDING,
        ]);
        
        expect($client1->can('cancel', $booking))->toBeFalse();
    }

    /**
     * Admin can view any booking
     */
    public function test_admin_can_view_any_booking(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = BookingRequest::factory()->create();
        
        expect($admin->can('view', $booking))->toBeTrue();
    }

    /**
     * Admin can update any booking
     */
    public function test_admin_can_update_any_booking(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = BookingRequest::factory()->create();
        
        expect($admin->can('update', $booking))->toBeTrue();
    }
}
