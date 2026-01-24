<?php

use App\Models\Studio;
use App\Models\Tattooer;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

// Tests pour les tatoueurs - API publique
test('can list tattooers without auth', function () {
    // Créer des tatoueurs pour les tests
    Tattooer::factory()->count(3)->create();

    $response = getJson('/api/tattooers');
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'name', 'bio', 'city', 'instagram', 'avatar_url'
                ]
            ]
        ]);
});

test('can show tattooer details', function () {
    $tattooer = Tattooer::factory()->create();

    $response = getJson("/api/tattooers/{$tattooer->id}");
    $response->assertStatus(200)
        ->assertJson([
            'id' => $tattooer->id,
            'name' => $tattooer->name
        ]);
});

test('can get tattooer portfolio', function () {
    $tattooer = Tattooer::factory()->create();

    $response = getJson("/api/tattooers/{$tattooer->id}/portfolio");
    $response->assertStatus(200);
});

test('can get tattooer availability', function () {
    $tattooer = Tattooer::factory()->create();

    $response = getJson("/api/tattooers/{$tattooer->id}/availability");
    $response->assertStatus(200);
});

// Tests pour les routes protégées
test('can access protected routes with auth', function () {
    $user = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $user->id]);

    actingAs($user, 'sanctum');

    $response = getJson("/api/tattooers/{$tattooer->id}/working-hours");
    $response->assertStatus(200);
});

test('cannot access protected routes without auth', function () {
    $tattooer = Tattooer::factory()->create();

    $response = getJson("/api/tattooers/{$tattooer->id}/working-hours");

    if ($response->status() !== 401) {
        dump([
            'status' => $response->status(),
            'json' => $response->json(),
            'exception' => $response->exception ? $response->exception->getMessage() : 'No exception'
        ]);
    }

    $response->assertStatus(401);
});

test('can upload portfolio image with auth', function () {
    $user = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $user->id]);

    actingAs($user, 'sanctum');

    $response = postJson("/api/tattooers/{$tattooer->id}/portfolio", [
        'image' => 'fake_image_data'
    ]);
    // Doit retourner une erreur de validation (422) car ce n'est pas une vraie image
    $response->assertStatus(422);
});
