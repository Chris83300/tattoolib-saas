<?php

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Tattooer;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

beforeEach(function () {
    // Créer un utilisateur et son client associé
    $user = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);

    // Créer aussi un tatoueur pour les tests
    $tattooer = Tattooer::factory()->verified()->create();

    // Rafraîchir pour s'assurer des relations
    $user = $user->fresh();
    $client = $client->fresh();
    $tattooer = $tattooer->fresh();

    // Rendre disponibles dans les tests
    test()->user = $user;
    test()->client = $client;
    test()->tattooer = $tattooer;

    actingAs($user, 'sanctum');
});

test('client can see their appointments', function () {
    // Créer un rendez-vous pour le client
    $appointment = Appointment::factory()->create([
        'client_id' => test()->client->id,
        'tattooer_id' => test()->tattooer->id,
        'status' => Appointment::STATUS_CONFIRMED,
        'start_time' => now()->addDay(),
        'end_time' => now()->addDay()->addHours(2),
    ]);

    $response = getJson('/api/appointments');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'start_time',
                    'end_time',
                    'status',
                    'tattooer' => [
                        'name',
                        'studio_name'
                    ]
                ]
            ]
        ]);
});

test('client can see upcoming appointments', function () {
    // Créer un rendez-vous futur
    $appointment = Appointment::factory()->create([
        'client_id' => test()->client->id,
        'tattooer_id' => test()->tattooer->id,
        'status' => Appointment::STATUS_CONFIRMED,
        'start_time' => now()->addDays(2),
        'end_time' => now()->addDays(2)->addHours(2),
    ]);

    $response = getJson('/api/appointments?filter=upcoming');

    $response->assertStatus(200);
    $response->assertJsonPath('data.0.id', $appointment->id);
});

test('client can see past appointments', function () {
    // Créer un rendez-vous passé
    $appointment = Appointment::factory()->create([
        'client_id' => test()->client->id,
        'tattooer_id' => test()->tattooer->id,
        'status' => Appointment::STATUS_COMPLETED,
        'start_time' => now()->subDays(2),
        'end_time' => now()->subDays(2)->addHours(2),
    ]);

    $response = getJson('/api/appointments?filter=past');

    $response->assertStatus(200);
    $response->assertJsonPath('data.0.id', $appointment->id);
});

test('client can cancel appointment', function () {
    $appointment = Appointment::factory()->create([
        'client_id' => test()->client->id,
        'tattooer_id' => test()->tattooer->id,
        'status' => Appointment::STATUS_CONFIRMED,
        'start_time' => now()->addDay(),
        'end_time' => now()->addDay()->addHours(2),
    ]);

    $response = postJson("/api/appointments/{$appointment->id}/cancel", [
        'reason' => 'Empêchement'
    ]);

    $response->assertStatus(200);
    $appointment->refresh();
    expect($appointment->status)->toEqual(Appointment::STATUS_CANCELLED);
});

test('tattooer can confirm appointment completion', function () {
    // Créer un rendez-vous confirmé
    $appointment = Appointment::factory()->create([
        'client_id' => test()->client->id,
        'tattooer_id' => test()->tattooer->id,
        'status' => Appointment::STATUS_CONFIRMED,
        'start_time' => now()->subHour(), // RDV qui vient de se terminer
        'end_time' => now()->addHour(),
    ]);

    // Se connecter en tant que tatoueur
    actingAs(test()->tattooer->user, 'sanctum');

    // Vérifier que l'appointment existe toujours avant l'appel API
    test()->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'tattooer_id' => test()->tattooer->id,
        'status' => Appointment::STATUS_CONFIRMED,
    ]);

    $response = postJson("/api/appointments/{$appointment->id}/confirm-completion", [
        'notes' => 'Tatouage terminé avec succès'
    ]);

    $response->assertStatus(200);
    $appointment->refresh();
    expect($appointment->status)->toEqual(Appointment::STATUS_COMPLETED);
});

test('client can report issue', function () {
    $appointment = Appointment::factory()->create([
        'client_id' => test()->client->id,
        'tattooer_id' => test()->tattooer->id,
        'status' => Appointment::STATUS_COMPLETED,
        'start_time' => now()->subDays(2),
        'end_time' => now()->subDays(2)->addHours(2),
    ]);

    $response = postJson("/api/appointments/{$appointment->id}/report-issue", [
        'issue_type' => 'allergic_reaction',
        'description' => 'Réaction allergique légère'
    ]);

    $response->assertStatus(200);
});

test('client can view calendar', function () {
    $currentDate = now();
    $response = getJson("/api/appointments/calendar?year=" . $currentDate->format('Y') . "&month=" . $currentDate->format('n'));

    $response->assertStatus(200);
    // Just vérifier que la réponse contient les données de base
    $response->assertJsonStructure([
        'year',
        'month'
    ]);
});

test('client can see appointment statistics', function () {
    // Créer quelques rendez-vous pour les stats
    Appointment::factory()->count(3)->create([
        'client_id' => test()->client->id,
        'tattooer_id' => test()->tattooer->id,
        'status' => Appointment::STATUS_COMPLETED,
    ]);

    Appointment::factory()->count(2)->create([
        'client_id' => test()->client->id,
        'tattooer_id' => test()->tattooer->id,
        'status' => Appointment::STATUS_CANCELLED,
    ]);

    $response = getJson('/api/appointments/statistics');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'total',
            'completed',
            'cancelled',
            'upcoming'
        ]);
});
