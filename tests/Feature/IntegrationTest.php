<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\StudioArtist;
use App\Models\Tattooer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test ⭐ Workflow complet avec factories */
    public function test_complete_workflow_with_factories()
    {
        // 1. Setup des utilisateurs
        $clientUser = User::factory()->client()->create();
        $client = Client::factory()->create(['user_id' => $clientUser->id]);

        $artistUser = User::factory()->studioArtist()->create();
        $artist = StudioArtist::factory()->create([
            'user_id' => $artistUser->id,
            'siret_verified' => true,
            'stripe_onboarding_complete' => true,
            'stripe_connect_account_id' => 'acct_test_' . uniqid(),
        ]);

        // 2. Créer les availabilities via WorkingHours
        \App\Models\WorkingHour::factory()->create([
            'owner_type' => StudioArtist::class,
            'owner_id' => $artist->id, // ID du studio artist
            'day_of_week' => now()->addDays(5)->dayOfWeek,
            'is_open' => true,
            'start_time' => '09:00',
            'end_time' => '18:00',
            'break_start' => '12:00',
            'break_end' => '13:00'
        ]);

        $targetDate = now()->addDays(5);
        Availability::generateFromWorkingHours(
            $artist->id, // ID du studio artist
            $targetDate,
            $targetDate
        );

        // 3. Client crée une demande
        $bookingRequest = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
            'preferred_date' => $targetDate->format('Y-m-d'),
            'preferred_time_slot' => 'afternoon',
            'preferred_time_notes' => 'Créneau flexible',
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        $this->assertEquals(BookingRequest::STATUS_PENDING, $bookingRequest->status);

        // 4. Tatoueur accepte
        $acceptData = [
            'scheduled_date' => $targetDate->format('Y-m-d'),
            'scheduled_start_time' => '14:00',
            'scheduled_duration_minutes' => 180,
            'total_price' => 300,
            'deposit_rate' => 30,
            'deposit_deadline_hours' => 72
        ];

        $response = $this->actingAs($artistUser)
            ->postJson("/api/booking-requests/{$bookingRequest->id}/accept", $acceptData);

        // Debug: afficher la réponse en cas d'erreur
        if ($response->status() !== 200) {
            dump($response->json());
        }

        $response->assertStatus(200);

        $bookingRequest->refresh();
        $this->assertEquals(BookingRequest::STATUS_ACCEPTED, $bookingRequest->status);

        // 5. Client paie l'acompte
        $bookingRequest->update(['status' => BookingRequest::STATUS_AWAITING_DEPOSIT]);

        $response = $this->actingAs($clientUser)
            ->postJson("/api/booking-requests/{$bookingRequest->id}/mark-deposit-paid", [
                'payment_intent_id' => 'pi_test_' . uniqid(),
            ]);

        $response->assertStatus(200);

        $bookingRequest->refresh();
        $this->assertEquals(BookingRequest::STATUS_DEPOSIT_PAID, $bookingRequest->status);

        // 6. Vérifier que les availabilities sont correctement gérées
        $slots = Availability::getAvailableSlotsForDay($artist->user_id, $targetDate->format('Y-m-d'));

        // Le créneau 14-17 ne devrait plus être disponible
        $hasSlot = collect($slots)->contains(function ($slot) {
            return $slot['start_time'] === '14:00' && $slot['end_time'] === '17:00';
        });
        $this->assertFalse($hasSlot);
    }

    /** @test ⭐ Gestion des RDV externes */
    public function test_external_appointments_management()
    {
        $tattooerUser = User::factory()->tattooer()->create();
        $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

        // Créer une journée de travail
        $date = now()->addDays(3)->format('Y-m-d');
        Availability::factory()
            ->forTattooer($tattooer->user_id)
            ->fullWorkDay()
            ->forDate($date)
            ->create();

        // Ajouter un RDV externe
        $response = $this->actingAs($tattooerUser)
            ->postJson('/api/planning/create-external-appointment', [
                'date' => $date,
                'start_time' => '10:00',
                'end_time' => '12:00',
                'source' => 'external_walk_in',
                'client_name' => 'Marie Dupont',
                'notes' => 'Pris en boutique'
            ]);

        $response->assertStatus(201);

        // Vérifier que le créneau est bien bloqué
        $slots = Availability::getAvailableSlotsForDay($tattooer->id, $date);

        // Debug : voir tous les créneaux disponibles
        dump('Available slots:', $slots);

        $hasMorningSlot = collect($slots)->contains(function ($slot) {
            return $slot['start_time'] === '09:00' && $slot['end_time'] === '10:00';
        });

        // Debug temporaire
        if (!$hasMorningSlot) {
            dump('Morning slot not found in slots:', $slots);
        }

        $this->assertTrue($hasMorningSlot);

        $hasExternalSlot = collect($slots)->contains(function ($slot) {
            return $slot['start_time'] === '10:00' && $slot['end_time'] === '12:00';
        });
        $this->assertFalse($hasExternalSlot);

        $hasAfternoonSlot = collect($slots)->contains(function ($slot) {
            return $slot['start_time'] === '12:00' && $slot['end_time'] === '18:00';
        });
        $this->assertTrue($hasAfternoonSlot);
    }

    /** @test ⭐ Performance avec grand nombre d'availabilities */
    public function test_performance_with_large_dataset()
    {
        $tattooerUser = User::factory()->create();
        $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

        // Créer 365 jours d'availabilities
        $startTime = microtime(true);

        for ($i = 0; $i < 365; $i++) {
            Availability::factory()
                ->forTattooer($tattooer->user_id)
                ->fullWorkDay()
                ->forDate(now()->addDays($i)->format('Y-m-d'))
                ->create();

            if ($i % 7 < 5) { // Pas le week-end
                Availability::factory()
                    ->forTattooer($tattooer->user_id)
                    ->lunchBreak()
                    ->forDate(now()->addDays($i)->format('Y-m-d'))
                    ->create();
            }
        }

        $creationTime = microtime(true) - $startTime;

        // Tester la récupération des disponibilités
        $startTime = microtime(true);

        $dates = Availability::getAvailableDates(
            $tattooer->user_id,
            now(),
            now()->addMonths(3)
        );

        $queryTime = microtime(true) - $startTime;

        $this->assertLessThan(5.0, $creationTime); // Création < 5s
        $this->assertLessThan(1.0, $queryTime); // Query < 1s
        $this->assertGreaterThan(60, count($dates)); // Au moins 60 jours disponibles
    }

    /** @test ⭐ Gestion des conflits de créneaux */
    public function test_slot_conflicts_handling()
    {
        $tattooerUser = User::factory()->tattooer()->create();
        $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

        $date = now()->addDays(2)->format('Y-m-d');

        // Créer une journée complète
        Availability::factory()
            ->forTattooer($tattooer->user_id)
            ->fullWorkDay()
            ->forDate($date)
            ->create();

        // Ajouter plusieurs RDV qui ne se chevauchent pas
        $slots = [
            ['09:00', '10:30'],
            ['11:00', '12:30'],
            ['14:00', '16:00'],
            ['16:30', '18:00']
        ];

        $releaseSlot = null;

        foreach ($slots as [$start, $end]) {
            $booking = Availability::factory()
                ->forTattooer($tattooer->user_id)
                ->externalBooking()
                ->forDate($date)
                ->create(['start_time' => $start, 'end_time' => $end]);

            if ($start === '14:00') {
                $releaseSlot = $booking;
            }
        }

        // Vérifier que le créneau 14:00-16:00 n'est plus disponible
        $availableSlots = Availability::getAvailableSlotsForDay($tattooer->id, $date);
        $hasBookedSlot = collect($availableSlots)->contains(function ($slot) {
            return $slot['start_time'] === '14:00' && $slot['end_time'] === '16:00';
        });
        $this->assertFalse($hasBookedSlot);

        // Libérer un créneau
        $this->assertNotNull($releaseSlot);

        $response = $this->actingAs($tattooerUser)
            ->deleteJson("/api/planning/release-slot/{$releaseSlot->id}");

        if ($response->status() !== 200) {
            dump([
                'status' => $response->status(),
                'json' => $response->json(),
                'releaseSlot' => $releaseSlot->toArray(),
                'tattooerUserId' => $tattooerUser->id,
                'tattooerId' => $tattooer->id
            ]);
        }

        $response->assertStatus(200);

        // Vérifier que le créneau est à nouveau disponible
        $availableSlots = Availability::getAvailableSlotsForDay($tattooer->id, $date);
        $this->assertNotEmpty($availableSlots);
    }
}
