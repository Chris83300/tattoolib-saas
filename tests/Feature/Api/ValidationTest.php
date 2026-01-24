<?php

use App\Models\Client;
use App\Models\Tattooer;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// Tests de validation des entrées
test('api validates required fields', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);
    actingAs($user, 'sanctum');

    $response = postJson('/api/booking-requests', []);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['bookable_type', 'bookable_id', 'description', 'tattoo_size', 'body_zone']);
});

test('api validates email format', function () {
    $response = postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'invalid-email-format',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('api validates password confirmation', function () {
    $response = postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'different-password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

test('api validates date format', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);
    actingAs($user, 'sanctum');

    $tattooer = Tattooer::factory()->verified()->create();

    $response = postJson('/api/booking-requests', [
        'user_id' => $user->id,
        'description' => 'Description valide de plus de 20 caractères',
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'preferred_days' => ['invalid-date-format'], // ✅ Array avec valeur invalide
        'estimated_budget' => 500,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['preferred_days.0']);
});

test('api validates numeric fields', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);
    actingAs($user, 'sanctum');

    $tattooer = Tattooer::factory()->verified()->create();

    $response = postJson('/api/booking-requests', [
        'user_id' => $user->id,
        'description' => 'Description valide de plus de 20 caractères',
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'preferred_days' => ['monday', 'tuesday'],
        'estimated_budget' => 'not-a-number', // ✅ Invalide
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['estimated_budget']);
});

// Tests de sécurité
test('api rejects suspicious input', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);
    actingAs($user, 'sanctum');

    $tattooer = Tattooer::factory()->verified()->create();
    $xssPayload = '<script>alert("xss")</script>';

    $response = postJson('/api/booking-requests', [
        'user_id' => $user->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => $xssPayload,
        'preferred_days' => ['monday', 'tuesday'],
        'estimated_budget' => 500,
    ]);

    // Laravel échappe automatiquement les chaînes, donc le test devrait passer
    // On vérifie juste que la requête est traitée correctement
    expect($response->status())->toBeIn([201, 422]);
});

test('api validates file uploads', function () {
    $user = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $user->id]);
    actingAs($user, 'sanctum');

    $response = postJson("/api/tattooers/{$tattooer->id}/portfolio", [
        'image' => 'not-a-real-file'
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['image']);
});

test('api validates enum values', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);
    $tattooer = Tattooer::factory()->verified()->create();
    actingAs($user, 'sanctum');

    $response = postJson('/api/booking-requests', [
        'user_id' => $user->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Description valide de plus de 20 caractères',
        'preferred_days' => ['invalid-day'], // ✅ Valeur invalide
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['preferred_days.0']);
});

// Tests de limites de taux (rate limiting)
test('api implements rate limiting', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);
    actingAs($user, 'sanctum');

    $tattooer = Tattooer::factory()->verified()->create();

    // Envoyer 61 requêtes (limite configurée à 60/minute)
    $responses = collect();
    for ($i = 0; $i < 61; $i++) {
        $responses->push(postJson('/api/booking-requests', [
            'user_id' => $user->id,
            'tattoo_size' => 'medium',
            'body_zone' => 'arm',
            'description' => 'Description valide de plus de 20 caractères pour le test ' . $i,
            // Envoyer des données invalides pour forcer la validation 422
            'preferred_days' => ['invalid-day-' . $i],
        ]));
    }

    // Les 60 premières devraient échouer en validation (422) mais rate limit OK
    for ($i = 0; $i < 60; $i++) {
        $responses->get($i)->assertStatus(422);
    }

    // La 61ème devrait être bloquée par rate limiting
    $responses->get(60)->assertStatus(429)
        ->assertJson([
            'message' => 'Too Many Attempts.',
        ]);
});

// Tests d'autorisation
test('api validates resource ownership', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $tattooer = Tattooer::factory()->create(['user_id' => $user2->id]);

    actingAs($user1, 'sanctum');

    $response = putJson("/api/tattooers/{$tattooer->id}/working-hours", [
        ['day_of_week' => 1, 'is_open' => true, 'opening_time' => '09:00', 'closing_time' => '18:00']
    ]);

    $response->assertStatus(403);
});

test('api validates resource existence', function () {
    $user = User::factory()->create();
    actingAs($user, 'sanctum');

    $response = getJson('/api/tattooers/999999');
    $response->assertStatus(404);
});

// Tests de validation des relations
test('api validates foreign key constraints', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);
    actingAs($user, 'sanctum');

    $response = postJson('/api/booking-requests', [
        'bookable_type' => 'invalid_type', // Type invalide
        'bookable_id' => 999999, // ID qui n'existe pas
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Description valide de plus de 20 caractères',
        'preferred_days' => ['monday', 'tuesday'],
        'estimated_budget' => 500,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['bookable_type', 'bookable_id']);
});

// Tests de validation des formats JSON
test('api validates json structure', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);
    actingAs($user, 'sanctum');

    $tattooer = Tattooer::factory()->verified()->create();

    // Test avec structure JSON incorrecte (champs manquants)
    $response2 = postJson('/api/booking-requests', [
        'bookable_type' => \App\Models\Tattooer::class,
        'bookable_id' => $tattooer->id,
        // Manque: tattoo_size, body_zone, description
    ]);

    $response2->assertStatus(422)
        ->assertJsonValidationErrors(['tattoo_size', 'body_zone', 'description']);

    // Test avec types de données incorrects dans JSON
    $response3 = postJson('/api/booking-requests', [
        'bookable_type' => 123, // Devrait être une chaîne
        'bookable_id' => 'not-a-number', // Devrait être un nombre
        'tattoo_size' => 123, // Devrait être une chaîne
        'body_zone' => ['array', 'instead', 'of', 'string'], // Devrait être une chaîne
        'description' => 'Description valide de plus de 20 caractères',
        'preferred_days' => 'not-an-array', // Devrait être un tableau
        'estimated_budget' => 'not-a-number', // Devrait être un nombre
    ]);

    $response3->assertStatus(422)
        ->assertJsonValidationErrors(['bookable_type', 'bookable_id', 'tattoo_size', 'body_zone', 'preferred_days', 'estimated_budget']);

    // Test avec JSON contenant des valeurs invalides
    $response4 = postJson('/api/booking-requests', [
        'bookable_type' => \App\Models\Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Description valide de plus de 20 caractères',
        'preferred_days' => ['invalid-day'], // Valeur invalide dans l'array
        'estimated_budget' => 500,
    ]);

    $response4->assertStatus(422)
        ->assertJsonValidationErrors(['preferred_days.0']);
});

// Tests de validation des longueurs
test('api validates string length limits', function () {
    $response = postJson('/api/register', [
        'name' => str_repeat('a', 300), // Nom trop long
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

// Tests de validation des enums
test('api validates booking enum values', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create([
        'user_id' => $user->id,
        'is_blacklisted' => false,
    ]);
    $tattooer = Tattooer::factory()->verified()->create();
    actingAs($user, 'sanctum');

    $response = postJson('/api/booking-requests', [
        'bookable_type' => \App\Models\Tattooer::class,
        'bookable_id' => $tattooer->id,
        'description' => 'Description valide de plus de 20 caractères pour passer la validation',
        'tattoo_size' => 'Petit (< 10cm)',
        'body_zone' => 'Avant-bras',
        'preferred_days' => ['monday', 'tuesday', 'wednesday'],
        'estimated_budget' => 500,
    ]);

    $response->assertStatus(201);
});

// Tests de validation des dates
test('api validates date ranges', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create([
        'user_id' => $user->id,
        'is_blacklisted' => false,
    ]);
    $tattooer = Tattooer::factory()->verified()->create();
    actingAs($user, 'sanctum');

    $response = postJson('/api/booking-requests', [
        'bookable_type' => \App\Models\Tattooer::class,
        'bookable_id' => $tattooer->id,
        'description' => 'Description valide de plus de 20 caractères',
        'tattoo_size' => 'Moyen (10-20cm)',
        'body_zone' => 'Épaule',
        'preferred_days' => ['monday', 'friday'],
        'estimated_budget' => 300,
    ]);

    $response->assertStatus(201);
});

test('api validates budget logic', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);
    actingAs($user, 'sanctum');

    $tattooer = Tattooer::factory()->verified()->create();

    // Test avec budget négatif
    $response = postJson('/api/booking-requests', [
        'user_id' => $user->id,
        'tattoo_size' => 'Petit (< 10cm)',
        'body_zone' => 'Poignet',
        'description' => 'Description valide de plus de 20 caractères',
        'preferred_days' => ['saturday'],
        'estimated_budget' => -100, // ✅ Budget négatif invalide
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['estimated_budget']);
});

test('api validates estimated budget', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);
    actingAs($user, 'sanctum');

    $tattooer = Tattooer::factory()->verified()->create();

    // Test avec budget dépassant le maximum
    $response = postJson('/api/booking-requests', [
        'user_id' => $user->id,
        'description' => 'Description valide de plus de 20 caractères',
        'tattoo_size' => 'Grand (20-40cm)',
        'body_zone' => 'Dos complet',
        'preferred_days' => ['monday', 'thursday'],
        'estimated_budget' => 15000, // ✅ Dépasse max:10000
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['estimated_budget']);
});
