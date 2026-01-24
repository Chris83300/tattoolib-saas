<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\Tattooer;
use App\Models\User;
use App\Models\WorkingHour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DebugCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_debug_availability_command()
    {
        $user = User::factory()->create();
        $tattooer = Tattooer::factory()->create(['user_id' => $user->id]);

        // Créer des horaires de travail pour aujourd'hui
        WorkingHour::create([
            'owner_type' => Tattooer::class,
            'owner_id' => $user->id,
            'day_of_week' => now()->dayOfWeek, // Jour actuel
            'is_open' => true,
            'start_time' => '09:00',
            'end_time' => '18:00'
        ]);

        // Tester la commande pour un jour spécifique
        $this->artisan('availability:generate --days=1')
            ->assertExitCode(0);

        // Vérifier que les availabilities ont été créées
        $this->assertDatabaseHas('availabilities', [
            'owner_type' => Tattooer::class,
            'owner_id' => $user->id,
            'type' => 'available',
            'source' => 'working_hours'
        ]);
    }
}
