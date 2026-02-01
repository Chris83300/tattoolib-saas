<?php

namespace Tests\Feature;

use App\Jobs\CheckExpiredBookingRequests;
use App\Models\Availability;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\Tattooer;
use App\Models\User;
use App\Models\WorkingHour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingRequestAdditionalTest extends TestCase
{
    use RefreshDatabase;

    /** @test ⭐ Conflit de créneaux */
    public function test_cannot_accept_overlapping_appointments()
    {
        $tattooerUser = User::factory()->tattooer()->create();
        $tattooer = Tattooer::factory()->create([
            'user_id' => $tattooerUser->id,
            'siret_verified' => true,
            'stripe_onboarding_complete' => true,
            'stripe_connect_account_id' => 'acct_test_' . uniqid(),
        ]);
        $clientUser1 = User::factory()->client()->create();
        $client1 = Client::factory()->create(['user_id' => $clientUser1->id]);
        $clientUser2 = User::factory()->client()->create();
        $client2 = Client::factory()->create(['user_id' => $clientUser2->id]);

        $date = now()->addDays(5)->format('Y-m-d');

        Availability::factory()
            ->forTattooer($tattooer->id)
            ->fullWorkDay()
            ->forDate($date)
            ->create();

        $booking1 = BookingRequest::factory()->create([
            'client_id' => $client1->id,
            'bookable_type' => Tattooer::class,
            'bookable_id' => $tattooer->id,
            'preferred_date' => $date,
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        $this->actingAs($tattooerUser)
            ->postJson("/api/booking-requests/{$booking1->id}/accept", [
                'scheduled_date' => $date,
                'scheduled_start_time' => '10:00',
                'scheduled_duration_minutes' => 120,
                'total_price' => 300,
                'deposit_rate' => 30,
                'deposit_deadline_hours' => 48,
            ])
            ->assertStatus(200);

        Availability::factory()
            ->forTattooer($tattooer->id)
            ->externalBooking()
            ->forDate($date)
            ->create(['start_time' => '10:00', 'end_time' => '12:00']);

        $booking2 = BookingRequest::factory()->create([
            'client_id' => $client2->id,
            'bookable_type' => Tattooer::class,
            'bookable_id' => $tattooer->id,
            'preferred_date' => $date,
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        $response = $this->actingAs($tattooerUser)
            ->postJson("/api/booking-requests/{$booking2->id}/accept", [
                'scheduled_date' => $date,
                'scheduled_start_time' => '11:00',
                'scheduled_duration_minutes' => 120,
                'total_price' => 300,
                'deposit_rate' => 30,
                'deposit_deadline_hours' => 48,
            ]);

        $response->assertStatus(422)
            ->assertJson(['message' => 'Créneau non disponible']);
    }

    /** @test ⭐ Génération automatique des availabilities */
    public function test_availabilities_are_auto_generated_when_missing()
    {
        $tattooerUser = User::factory()->create();
        $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

        WorkingHour::factory()->create([
            'owner_type' => Tattooer::class,
            'owner_id' => $tattooer->id,
            'day_of_week' => now()->addDays(10)->dayOfWeek,
            'is_open' => true,
            'start_time' => '09:00',
            'end_time' => '18:00',
        ]);

        $futureDate = now()->addDays(10)->format('Y-m-d');

        $this->assertDatabaseMissing('availabilities', [
            'owner_type' => Tattooer::class,
            'owner_id' => $tattooer->id,
            'date' => $futureDate,
        ]);

        $response = $this->actingAs($tattooerUser)
            ->getJson("/api/planning/tattooers/{$tattooer->id}/available-dates");
        $response->assertStatus(200);

        $this->assertDatabaseHas('availabilities', [
            'owner_type' => Tattooer::class,
            'owner_id' => $tattooer->id,
            'date' => $futureDate,
            'source' => 'working_hours',
        ]);
    }

    /** @test ⭐ Workflow complet avec paiement simulé */
    public function test_complete_workflow_with_stripe_simulation()
    {
        $clientUser = User::factory()->client()->create();
        $client = Client::factory()->create(['user_id' => $clientUser->id]);
        $tattooerUser = User::factory()->tattooer()->create();
        $tattooer = Tattooer::factory()->create([
            'user_id' => $tattooerUser->id,
            'siret_verified' => true,
            'stripe_onboarding_complete' => true,
            'stripe_connect_account_id' => 'acct_test',
        ]);

        $date = now()->addDays(7)->format('Y-m-d');

        Availability::factory()
            ->forTattooer($tattooer->id)
            ->fullWorkDay()
            ->forDate($date)
            ->create();

        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => Tattooer::class,
            'bookable_id' => $tattooer->id,
            'preferred_date' => $date,
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        $response = $this->actingAs($tattooerUser)
            ->postJson("/api/booking-requests/{$booking->id}/accept", [
                'scheduled_date' => $date,
                'scheduled_start_time' => '14:00',
                'scheduled_duration_minutes' => 120,
                'total_price' => 500,
                'deposit_rate' => 30,
                'deposit_deadline_hours' => 48,
            ]);

        if ($response->status() !== 200) {
            dump([
                'status' => $response->status(),
                'json' => $response->json(),
                'exception' => $response->exception ? $response->exception->getMessage() : 'No exception'
            ]);
        }

        $response->assertStatus(200);

        $booking->refresh();

        $booking->update(['status' => BookingRequest::STATUS_AWAITING_DEPOSIT]);

        $this->actingAs($clientUser)
            ->postJson("/api/booking-requests/{$booking->id}/mark-deposit-paid", [
                'payment_intent_id' => 'pi_test_' . uniqid(),
            ])
            ->assertStatus(200);

        $booking->refresh();
        $this->assertEquals(BookingRequest::STATUS_DEPOSIT_PAID, $booking->status);
        $this->assertNotNull($booking->stripe_payment_intent_id);

        $slots = Availability::getAvailableSlotsForDay($tattooer->id, $date);

        $hasSlot = collect($slots)->contains(function ($slot) {
            return $slot['start_time'] === '14:00';
        });

        $this->assertFalse($hasSlot);
    }

    /** @test ⭐ Nettoyage automatique */
    public function test_old_availabilities_are_cleaned_automatically()
    {
        $tattooer = Tattooer::factory()->create();

        Availability::factory()->count(10)->create([
            'owner_type' => Tattooer::class,
            'owner_id' => $tattooer->id,
            'date' => now()->subDays(45)->format('Y-m-d'),
            'source' => 'working_hours',
        ]);

        Availability::factory()->count(5)->create([
            'owner_type' => Tattooer::class,
            'owner_id' => $tattooer->id,
            'date' => now()->addDays(5)->format('Y-m-d'),
        ]);

        $this->artisan('availability:generate --days=7')
            ->assertExitCode(0);

        $oldCount = Availability::where('owner_type', Tattooer::class)
            ->where('owner_id', $tattooer->id)
            ->where('date', '<', now()->subDays(30))
            ->count();

        $this->assertEquals(0, $oldCount);

        $recentCount = Availability::where('owner_type', Tattooer::class)
            ->where('owner_id', $tattooer->id)
            ->where('date', '>=', now())
            ->count();

        $this->assertGreaterThan(0, $recentCount);
    }

    /** @test ⭐ Performance sous charge */
    public function test_handles_concurrent_booking_requests()
    {
        $tattooerUser = User::factory()->tattooer()->create();
        $tattooer = Tattooer::factory()->create([
            'user_id' => $tattooerUser->id,
            'siret_verified' => true,
            'stripe_onboarding_complete' => true,
            'stripe_connect_account_id' => 'acct_test_' . uniqid(),
        ]);
        $clients = Client::factory()->count(10)->create();

        $date = now()->addDays(7)->format('Y-m-d');

        Availability::factory()
            ->forTattooer($tattooer->user_id)
            ->fullWorkDay()
            ->forDate($date)
            ->create();

        $bookings = $clients->map(function ($client) use ($tattooer, $date) {
            return BookingRequest::factory()->create([
                'client_id' => $client->id,
                'bookable_type' => Tattooer::class,
            'bookable_id' => $tattooer->id,
                'preferred_date' => $date,
                'preferred_time_slot' => 'morning',
                'status' => BookingRequest::STATUS_PENDING,
            ]);
        });

        $accepted = 0;
        $rejected = 0;

        foreach ($bookings as $booking) {
            $response = $this->actingAs($tattooerUser)
                ->postJson("/api/booking-requests/{$booking->id}/accept", [
                    'scheduled_date' => $date,
                    'scheduled_start_time' => '10:00',
                    'scheduled_duration_minutes' => 120,
                    'total_price' => 300,
                    'deposit_rate' => 30,
                    'deposit_deadline_hours' => 48,
                ]);

            if ($response->status() === 200) {
                $accepted++;
                Availability::factory()
                    ->forTattooer($tattooer->id)
                    ->externalBooking()
                    ->forDate($date)
                    ->create(['start_time' => '10:00', 'end_time' => '12:00']);
            } else {
                $rejected++;
            }
        }

        $this->assertEquals(1, $accepted);
        $this->assertEquals(9, $rejected);
    }
}
