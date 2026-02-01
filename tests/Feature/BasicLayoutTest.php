<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimpleLayoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_basic_layout()
    {
        $user = User::factory()->client()->create();

        $response = $this->actingAs($user)
            ->get('/client/dashboard');

        $response->assertStatus(403);
    }
}
