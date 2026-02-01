<?php

use App\Models\Pierceur;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('can list piercers', function () {
    // Créer un pierceur pour s'assurer qu'il y a des données
    $pierceur = Pierceur::factory()->create();

    $user = User::factory()->create();
    actingAs($user, 'sanctum');

    $response = getJson('/api/piercers');
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'specialization', 'specialization_label', 'bio', 'city', 'instagram', 'avatar_url']
            ]
        ]);
});

test('can list piercers by specialization', function () {
    // Créer des pierceurs avec différentes spécialisations
    Pierceur::factory()->pierceurSpecialization()->create();
    Pierceur::factory()->bodemodeur()->create();
    Pierceur::factory()->both()->create();

    $response = getJson('/api/piercers?specialization=pierceur');
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.specialization', 'pierceur');
});

test('can search piercers', function () {
    $pierceur = Pierceur::factory()->create([
        'name' => 'John Doe Piercing',
        'city' => 'Paris'
    ]);

    $response = getJson('/api/piercers?search=John');
    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'John Doe Piercing');
});

test('can view pierceur profile', function () {
    $pierceur = Pierceur::factory()->create();

    $response = getJson("/api/piercers/{$pierceur->id}");
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id', 'name', 'specialization', 'specialization_label', 'bio',
                'city', 'postal_code', 'phone', 'email', 'instagram',
                'facebook', 'tiktok', 'website', 'avatar_url', 'studio_name'
            ]
        ]);
});

test('pierceur can update own profile', function () {
    $pierceur = Pierceur::factory()->create();
    $user = $pierceur->user;

    actingAs($user, 'sanctum');

    $response = $this->putJson("/api/piercers/{$pierceur->id}", [
        'bio' => 'Updated bio',
        'phone' => '0612345678'
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('piercers', [
        'id' => $pierceur->id,
        'bio' => 'Updated bio',
        'phone' => '0612345678'
    ]);
});

test('pierceur cannot update other profile', function () {
    $pierceur = Pierceur::factory()->create();
    $otherUser = User::factory()->create();

    actingAs($otherUser, 'sanctum');

    $response = $this->putJson("/api/piercers/{$pierceur->id}", [
        'bio' => 'Hacked bio'
    ]);

    $response->assertStatus(403);
});

test('pierceur can manage specialization', function () {
    $pierceur = Pierceur::factory()->pierceurSpecialization()->create();
    $user = $pierceur->user;

    actingAs($user, 'sanctum');

    $response = $this->putJson("/api/piercers/{$pierceur->id}/specialization", [
        'specialization' => 'pierceur_bodemodeur'
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('piercers', [
        'id' => $pierceur->id,
        'specialization' => 'pierceur_bodemodeur'
    ]);
});

test('verified pierceur scope works', function () {
    // Créer des pierceurs vérifiés et non vérifiés
    Pierceur::factory()->verified()->count(3)->create();
    Pierceur::factory()->count(2)->create(['siret_verified' => false]);

    $verifiedPierceurs = Pierceur::verified()->get();
    expect($verifiedPierceurs)->toHaveCount(3);

    $verifiedPierceurs->each(function ($pierceur) {
        expect($pierceur->siret_verified)->toBeTrue();
    });
});

test('specialization helpers work', function () {
    $pierceur = Pierceur::factory()->create(['specialization' => 'pierceur_bodemodeur']);

    expect($pierceur->isBoth())->toBeTrue();
    expect($pierceur->specialization_label)->toBe('Pierceur / Bodemodeur');
});

test('pierceur factory states work', function () {
    $pierceur = Pierceur::factory()->verified()->pro()->create();

    expect($pierceur->siret_verified)->toBeTrue();
    expect($pierceur->isPro())->toBeTrue();
    expect($pierceur->stripe_onboarding_complete)->toBeTrue();
});

test('pierceur can accept bookings when verified', function () {
    $pierceur = Pierceur::factory()->verified()->create();

    expect($pierceur->canAcceptBookings())->toBeTrue();
});

test('pierceur cannot accept bookings when not verified', function () {
    $pierceur = Pierceur::factory()->create(['siret_verified' => false]);

    expect($pierceur->canAcceptBookings())->toBeFalse();
});
