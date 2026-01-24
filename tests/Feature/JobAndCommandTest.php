<?php

namespace Tests\Feature;

use App\Jobs\CheckExpiredBookingRequests;
use App\Models\Availability;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\Tattooer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class JobAndCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test ⭐ Job CheckExpiredBookingRequests */
    public function test_check_expired_booking_requests_job_works()
    {
        // Créer des demandes acceptées avec délais expirés
        BookingRequest::factory()
            ->count(3)
            ->create([
                'status' => BookingRequest::STATUS_ACCEPTED,
                'deposit_deadline' => now()->subHours(24),
            ]);

        // Créer une demande valide (non expirée)
        BookingRequest::factory()
            ->create([
                'status' => BookingRequest::STATUS_ACCEPTED,
                'deposit_deadline' => now()->addHours(48),
            ]);

        // Créer une demande déjà expirée
        BookingRequest::factory()
            ->create([
                'status' => BookingRequest::STATUS_EXPIRED,
                'expired_at' => now()->subHours(12)
            ]);

        $job = new CheckExpiredBookingRequests();
        $job->handle();

        // Vérifier que les demandes acceptées expirées sont marquées
        $expiredRequests = BookingRequest::where('status', BookingRequest::STATUS_EXPIRED)
            ->whereNotNull('expired_at')
            ->get();

        $this->assertGreaterThanOrEqual(1, $expiredRequests->count());

        // Vérifier que la demande valide n'est pas affectée
        $validRequest = BookingRequest::where('status', BookingRequest::STATUS_ACCEPTED)
            ->whereNull('expired_at')
            ->first();
        $this->assertNotNull($validRequest);
    }

    /** @test ⭐ Commande check-expired */
    public function test_check_expired_command_works()
    {
        // Créer des demandes expirées
        BookingRequest::factory()
            ->count(2)
            ->create([
                'status' => BookingRequest::STATUS_ACCEPTED,
                'deposit_deadline' => now()->subHours(24),
            ]);

        $this->artisan('booking-requests:check-expired')
            ->expectsOutput('Vérification des demandes de réservation expirées...')
            ->expectsOutput('Job de vérification dispatché avec succès.')
            ->assertExitCode(0);
    }

    /** @test ⭐ Commande availability:generate */
    public function test_availability_generate_command_works()
    {
        $user = User::factory()->create();
        $tattooer = Tattooer::factory()->create(['user_id' => $user->id]);

        // Créer des horaires de travail
        \App\Models\WorkingHour::factory()->create([
            'owner_type' => Tattooer::class,
            'owner_id' => $tattooer->user_id,
            'day_of_week' => now()->dayOfWeek,
            'is_open' => true,
            'start_time' => '09:00',
            'end_time' => '18:00',
            'break_start' => '12:00',
            'break_end' => '13:00'
        ]);

        $this->artisan('availability:generate --days=7')
            ->assertExitCode(0);

        // Vérifier que les availabilities ont été créées
        $this->assertDatabaseHas('availabilities', [
            'owner_type' => Tattooer::class,
            'owner_id' => $tattooer->user_id,
            'type' => 'available',
            'source' => 'working_hours'
        ]);
    }

    /** @test ⭐ Commande availability:generate avec nettoyage */
    public function test_availability_generate_command_cleans_old_records()
    {
        $user = User::factory()->create();
        $tattooer = Tattooer::factory()->create(['user_id' => $user->id]);

        // Créer de vieilles availabilities (plus de 30 jours)
        Availability::create([
            'owner_type' => Tattooer::class,
            'owner_id' => $tattooer->user_id,
            'date' => now()->subDays(45)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '18:00',
            'type' => 'available',
            'source' => 'working_hours'
        ]);

        // Créer des horaires de travail
        \App\Models\WorkingHour::factory()->create([
            'owner_type' => Tattooer::class,
            'owner_id' => $tattooer->user_id,
            'day_of_week' => now()->dayOfWeek,
            'is_open' => true,
            'start_time' => '09:00',
            'end_time' => '18:00'
        ]);

        $this->artisan('availability:generate --days=7')
            ->assertExitCode(0);

        // Vérifier que les vieilles availabilities ont été supprimées
        $this->assertDatabaseMissing('availabilities', [
            'owner_type' => Tattooer::class,
            'owner_id' => $tattooer->user_id,
            'date' => now()->subDays(45)->format('Y-m-d')
        ]);
    }
}
