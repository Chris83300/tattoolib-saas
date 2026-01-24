<?php

namespace Tests\Feature;

use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\Tattooer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingRequestFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test ⭐ Factory BookingRequest avec préférences */
    public function test_booking_request_factory_with_preferences_works()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);
        $tattooer = Tattooer::factory()->create();

        $bookingRequest = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => \App\Models\Tattooer::class,
            'bookable_id' => $tattooer->id,
            'preferred_date' => now()->addDays(5)->format('Y-m-d'),
            'preferred_time_slot' => 'morning',
            'preferred_time_notes' => 'De préférence le matin',
        ]);

        $this->assertNotNull($bookingRequest->preferred_date);
        $this->assertNotNull($bookingRequest->preferred_time_slot);
        $this->assertNotNull($bookingRequest->preferred_time_notes);
        $this->assertContains($bookingRequest->preferred_time_slot, ['morning', 'afternoon', 'evening']);
    }

    /** @test ⭐ Factory BookingRequest acceptée */
    public function test_booking_request_factory_accepted_works()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);
        $tattooer = Tattooer::factory()->create();

        $bookingRequest = BookingRequest::factory()
            ->accepted()
            ->create([
                'client_id' => $client->id,
                'bookable_type' => \App\Models\Tattooer::class,
                'bookable_id' => $tattooer->id,
                'accepted_at' => now(),
                'scheduled_start_time' => '10:00',
                'scheduled_end_time' => '12:00',
                'scheduled_duration_minutes' => 120,
                'total_price' => 500,
                'deposit_deadline' => now()->addHours(48),
            ]);

        $this->assertEquals(BookingRequest::STATUS_ACCEPTED, $bookingRequest->status);
        $this->assertNotNull($bookingRequest->accepted_at);
        $this->assertNotNull($bookingRequest->scheduled_start_time);
        $this->assertNotNull($bookingRequest->scheduled_end_time);
        $this->assertNotNull($bookingRequest->total_price);
        $this->assertNotNull($bookingRequest->deposit_deadline);
    }

    /** @test ⭐ Factory BookingRequest expirée */
    public function test_booking_request_factory_expired_works()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);
        $tattooer = Tattooer::factory()->create();

        $bookingRequest = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => \App\Models\Tattooer::class,
            'bookable_id' => $tattooer->id,
            'status' => BookingRequest::STATUS_EXPIRED,
            'deposit_deadline' => now()->subHours(24),
        ]);

        $this->assertEquals(BookingRequest::STATUS_EXPIRED, $bookingRequest->status);
        $this->assertNotNull($bookingRequest->deposit_deadline);
        $this->assertLessThan(now(), $bookingRequest->deposit_deadline);
    }

    /** @test ⭐ Factory BookingRequest workflow complet */
    public function test_booking_request_factory_confirmed_works()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $user->id]);
        $tattooer = Tattooer::factory()->create();

        $bookingRequest = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => \App\Models\Tattooer::class,
            'bookable_id' => $tattooer->id,
            'status' => BookingRequest::STATUS_DEPOSIT_PAID,
            'stripe_payment_intent_id' => 'pi_test_' . uniqid(),
            'deposit_paid_at' => now(),
            'tattooer_design_deadline' => now()->addDays(7),
        ]);

        $this->assertEquals(BookingRequest::STATUS_DEPOSIT_PAID, $bookingRequest->status);
        $this->assertNotNull($bookingRequest->stripe_payment_intent_id);
        $this->assertNotNull($bookingRequest->deposit_paid_at);
    }
}
