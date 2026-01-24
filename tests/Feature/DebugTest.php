<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\Tattooer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DebugTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_debug_availability_creation()
    {
        $user = User::factory()->create();
        $tattooer = Tattooer::factory()->create(['user_id' => $user->id]);

        // Test 1: Créer une availability directement
        try {
            $availability = Availability::create([
                'owner_type' => Tattooer::class,
                'owner_id' => $tattooer->id,
                'date' => '2025-12-09',
                'start_time' => '09:00',
                'end_time' => '18:00',
                'type' => 'available',
                'source' => 'working_hours'
            ]);
            $this->assertTrue(true, 'Availability created successfully');
        } catch (\Exception $e) {
            $this->fail('Availability creation failed: ' . $e->getMessage());
        }

        // Test 2: Vérifier si l'availability existe
        try {
            $exists = Availability::where('owner_id', $tattooer->id)
                ->whereDate('date', '2025-12-09')
                ->exists();
            $this->assertTrue($exists, 'Availability exists');
        } catch (\Exception $e) {
            $this->fail('Availability exists check failed: ' . $e->getMessage());
        }

        // Test 3: Essayer la méthode getAvailableSlotsForDay
        try {
            $slots = Availability::getAvailableSlotsForDay($tattooer->id, '2025-12-09');
            $this->assertTrue(true, 'getAvailableSlotsForDay works');
        } catch (\Exception $e) {
            $this->fail('getAvailableSlotsForDay failed: ' . $e->getMessage());
        }
    }
}
