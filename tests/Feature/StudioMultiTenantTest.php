<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Studio;
use App\Models\StudioArtist;
use App\Models\BookingRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudioMultiTenantTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_supports_both_tattooer_and_studio_artist()
    {
        // Créer studio
        $studio = Studio::factory()->create([
            'name' => 'Test Studio',
            'slug' => 'test-studio',
        ]);

        // Créer tattooer indépendant
        $tattooer = \App\Models\Tattooer::factory()->create([
            'studio_id' => $studio->id,
            'artist_name' => 'Independent Artist',
            'slug' => 'independent-artist',
        ]);

        // Créer studio artist
        $studioArtist = StudioArtist::factory()->create([
            'studio_id' => $studio->id,
            'artist_name' => 'Studio Artist',
            'slug' => 'studio-artist',
        ]);

        // Créer client
        $client = Client::factory()->create();

        // Créer bookings pour les deux types
        $tattooerBooking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => \App\Models\Tattooer::class,
            'bookable_id' => $tattooer->id,
            'status' => BookingRequest::STATUS_ACCEPTED,
            'estimated_price' => 200.00,
        ]);

        $studioArtistBooking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $studioArtist->id,
            'status' => BookingRequest::STATUS_ACCEPTED,
            'estimated_price' => 300.00,
        ]);

        // Vérifier les relations
        $this->assertEquals($tattooer->id, $tattooerBooking->bookable->id);
        $this->assertEquals($studioArtist->id, $studioArtistBooking->bookable->id);
        $this->assertEquals(\App\Models\Tattooer::class, $tattooerBooking->bookable_type);
        $this->assertEquals(StudioArtist::class, $studioArtistBooking->bookable_type);

        // Vérifier que les deux artistes appartiennent au même studio
        $this->assertEquals($studio->id, $tattooer->studio_id);
        $this->assertEquals($studio->id, $studioArtist->studio_id);
    }

    /** @test */
    public function it_shows_unified_artist_profile()
    {
        $studio = Studio::factory()->create(['slug' => 'test-studio']);

        $tattooer = \App\Models\Tattooer::factory()->create([
            'studio_id' => $studio->id,
            'artist_name' => 'Tattooer Test',
            'slug' => 'tattooer-test',
        ]);

        $studioArtist = StudioArtist::factory()->create([
            'studio_id' => $studio->id,
            'artist_name' => 'Studio Artist Test',
            'slug' => 'studio-artist-test',
        ]);

        // Test route unifiée pour tattooer
        $response = $this->get("/artists/tattooer-test");
        $response->assertStatus(200)
            ->assertJsonFragment(['artist_name' => 'Tattooer Test'])
            ->assertJsonFragment(['type' => 'tattooer']);

        // Test route unifiée pour studio artist
        $response = $this->get("/artists/studio-artist-test");
        $response->assertStatus(200)
            ->assertJsonFragment(['artist_name' => 'Studio Artist Test'])
            ->assertJsonFragment(['type' => 'studio_artist']);
    }

    /** @test */
    public function it_prevents_duplicate_slugs_within_same_studio()
    {
        $studio = Studio::factory()->create();

        // Créer premier artiste
        $artist1 = StudioArtist::factory()->create([
            'studio_id' => $studio->id,
            'artist_name' => 'Artist One',
            'slug' => 'same-slug',
        ]);

        // Tenter de créer deuxième artiste avec même slug
        $response = $this->postJson('/api/studios/' . $studio->id . '/artists', [
            'artist_name' => 'Artist Two',
            'slug' => 'same-slug', // Slug en double
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['slug']);
    }
}
