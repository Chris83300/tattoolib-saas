<?php

use App\Models\Tattooer;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('can list tattooers', function () {
    // Créer un tatoueur pour s'assurer qu'il y a des données
    $tattooer = Tattooer::factory()->create();

    $user = User::factory()->create();
    actingAs($user, 'sanctum');

    $response = getJson('/api/tattooers');
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'bio', 'city', 'instagram', 'avatar_url']
            ]
        ]);
});
