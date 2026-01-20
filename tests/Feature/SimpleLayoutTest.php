<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\StudioArtist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewLayoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_render_layout_without_errors()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/dashboard');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_render_profile_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/settings/profile');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_render_studio_artist_profile()
    {
        $studioArtist = StudioArtist::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/artists/' . $studioArtist->slug);

        $response->assertStatus(200);
    }
}
