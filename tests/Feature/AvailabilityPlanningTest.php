<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\Tattooer;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityPlanningTest extends TestCase
{
    use RefreshDatabase;

    private User $tattooerUser;
    private Tattooer $tattooer;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un tatoueur avec son utilisateur
        $this->tattooerUser = User::factory()->tattooer()->create();
        $this->tattooer = Tattooer::factory()->create([
            'user_id' => $this->tattooerUser->id,
        ]);
    }

    /** @test ⭐ Dashboard planning tatoueur */
    public function test_tattooer_can_view_planning_dashboard()
    {
        // Créer des availabilities variées
        Availability::factory()->count(5)
            ->forTattooer($this->tattooer->id)
            ->fullWorkDay()
            ->create();

        Availability::factory()->count(2)
            ->forTattooer($this->tattooer->id)
            ->busy()
            ->create();

        $response = $this->actingAs($this->tattooerUser)
            ->getJson('/api/planning/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'period' => [
                    'start',
                    'end',
                    'view'
                ],
                'availabilities_by_date',
                'appointments',
                'statistics' => [
                    'total_appointments',
                    'total_hours_booked'
                ]
            ]);
    }

    /** @test ⭐ Tatoueur peut bloquer un créneau manuellement */
    public function test_tattooer_can_block_slot_manually()
    {
        $slotData = [
            'date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '14:00',
            'end_time' => '16:00',
            'type' => 'blocked',
            'notes' => 'Rendez-vous personnel'
        ];

        $response = $this->actingAs($this->tattooerUser)
            ->postJson('/api/planning/block-slot', $slotData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'availability' => [
                    'id',
                    'owner_type',
                    'owner_id',
                    'date',
                    'start_time',
                    'end_time',
                    'type',
                    'source',
                    'notes'
                ]
            ]);

        // Vérifier que l'enregistrement a été créé avec les bonnes valeurs
        $this->assertDatabaseHas('availabilities', [
            'owner_type' => Tattooer::class,
            'owner_id' => $this->tattooer->id,
            'type' => 'blocked',
            'source' => 'manual',
            'notes' => $slotData['notes']
        ]);
    }

    /** @test ⭐ Créer un RDV externe */
    public function test_tattooer_can_create_external_appointment()
    {
        $externalData = [
            'date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '13:00',
            'source' => 'external_walk_in',
            'client_name' => 'Marie Dupont',
            'notes' => 'Pris en boutique'
        ];

        $response = $this->actingAs($this->tattooerUser)
            ->postJson('/api/planning/create-external-appointment', $externalData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'availability'
            ]);

        $this->assertDatabaseHas('availabilities', [
            'owner_type' => Tattooer::class,
            'owner_id' => $this->tattooer->id,
            'type' => 'external_booking',
            'source' => 'manual'
        ]);
    }

    /** @test ⭐ Libérer un créneau bloqué */
    public function test_tattooer_can_release_blocked_slot()
    {
        // Créer un créneau bloqué avec Availability::create()
        $availability = Availability::create([
            'owner_type' => Tattooer::class,
            'owner_id' => $this->tattooer->id,
            'date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '14:00',
            'end_time' => '16:00',
            'type' => Availability::TYPE_BLOCKED,
            'source' => Availability::SOURCE_MANUAL,
            'notes' => 'Bloqué pour test'
        ]);

        $response = $this->actingAs($this->tattooerUser)
            ->deleteJson("/api/planning/release-slot/{$availability->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Créneau libéré']);

        $this->assertDatabaseMissing('availabilities', [
            'id' => $availability->id
        ]);
    }

    /** @test ⭐ Invités peuvent voir les dates disponibles */
    public function test_guests_can_view_available_dates()
    {
        // Créer des availabilities
        Availability::factory()->count(10)
            ->forTattooer($this->tattooer->id)
            ->fullWorkDay()
            ->sequence(function ($sequence) {
                return ['date' => now()->addDays($sequence->index)->format('Y-m-d')];
            })
            ->create();

        $response = $this->actingAs($this->tattooerUser)
            ->getJson("/api/planning/tattooers/{$this->tattooer->id}/available-dates");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'period' => [
                    'start',
                    'end'
                ],
                'available_dates' => [
                    '*' => [
                        'date',
                        'day_name',
                        'is_today',
                        'is_weekend',
                        'available_slots_count',
                        'total_available_minutes'
                    ]
                ],
                'total_dates'
            ]);
    }

    /** @test ⭐ Consultation créneaux pour une date */
    public function test_guests_can_view_slots_for_specific_date()
    {
        $date = now()->addDays(5)->format('Y-m-d');

        // Créer une journée complète
        Availability::factory()
            ->forTattooer($this->tattooer->id)
            ->fullWorkDay()
            ->forDate($date)
            ->create();

        // Ajouter une pause
        Availability::factory()
            ->forTattooer($this->tattooer->id)
            ->lunchBreak()
            ->forDate($date)
            ->create();

        $response = $this->actingAs($this->tattooerUser)
            ->getJson("/api/planning/tattooers/{$this->tattooer->id}/slots-for-date?date={$date}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'date',
                'available_slots' => [
                    '*' => [
                        'date',
                        'start_time',
                        'end_time',
                        'duration_minutes'
                    ]
                ],
                'total_slots'
            ]);
    }

    /** @test ⭐ Validation bloquer créneau */
    public function test_blocking_slot_requires_valid_data()
    {
        $response = $this->actingAs($this->tattooerUser)
            ->postJson('/api/planning/block-slot', [
                'date' => 'invalid-date',
                'start_time' => '14:00',
                'end_time' => '13:00', // Avant le début
                'type' => 'blocked'
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['date', 'end_time']);
    }

    /** @test ⭐ Non-tatoueur ne peut pas accéder au planning */
    public function test_non_tattooer_cannot_access_planning_endpoints()
    {
        $clientUser = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $clientUser->id]);

        $response = $this->actingAs($clientUser)
            ->getJson('/api/planning/dashboard');

        $response->assertStatus(403);
    }

    /** @test ⭐ Vérification disponibilités avec méthodes du modèle */
    public function test_availability_model_methods_work_correctly()
    {
        $date = now()->addDays(3)->format('Y-m-d');

        // Créer une journée de travail
        Availability::factory()
            ->forTattooer($this->tattooer->id)
            ->fullWorkDay()
            ->forDate($date)
            ->create();

        // Ajouter un RDV externe
        Availability::factory()
            ->forTattooer($this->tattooer->id)
            ->externalBooking()
            ->forDate($date)
            ->create(['start_time' => '10:00', 'end_time' => '12:00']);

        // Tester la méthode getAvailableSlotsForDay
        $slots = Availability::getAvailableSlotsForDay($this->tattooer->id, $date);

        $this->assertIsArray($slots);
        $this->assertNotEmpty($slots);

        // Vérifier que le créneau 10-12 n'est pas disponible
        $hasMorningSlot = collect($slots)->contains(function ($slot) {
            return $slot['start_time'] === '09:00' && $slot['end_time'] === '10:00';
        });
        $this->assertTrue($hasMorningSlot);

        // Vérifier que le créneau 12-18 est disponible
        $hasAfternoonSlot = collect($slots)->contains(function ($slot) {
            return $slot['start_time'] === '12:00' && $slot['end_time'] === '18:00';
        });
        $this->assertTrue($hasAfternoonSlot);
    }

    /** @test ⭐ Génération depuis WorkingHours */
    public function test_can_generate_availabilities_from_working_hours()
    {
        // Créer des horaires de travail pour le tatoueur
        \App\Models\WorkingHour::factory()->create([
            'owner_type' => Tattooer::class,
            'owner_id' => $this->tattooer->id,
            'day_of_week' => now()->dayOfWeek,
            'is_open' => true,
            'start_time' => '09:00',
            'end_time' => '18:00',
            'break_start' => '12:00',
            'break_end' => '13:00'
        ]);

        $startDate = now();
        $endDate = now()->addDays(7);

        $generated = Availability::generateFromWorkingHours(
            $this->tattooer->id,
            $startDate,
            $endDate
        );

        $this->assertGreaterThan(0, $generated);

        // Vérifier qu'on a bien les availabilities créées
        $this->assertDatabaseHas('availabilities', [
            'owner_type' => Tattooer::class,
            'owner_id' => $this->tattooer->id,
            'type' => 'available',
            'source' => 'working_hours'
        ]);
    }
}
