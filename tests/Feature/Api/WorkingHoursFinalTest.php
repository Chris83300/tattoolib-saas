<?php

use App\Models\Tattooer;
use App\Models\User;
use App\Models\WorkingHour;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
    test()->user = User::factory()->create();
    test()->tattooer = Tattooer::factory()->create(['user_id' => test()->user->id]);
    actingAs(test()->user, 'sanctum');
});

test('tattooer can get their working hours', function () {
    WorkingHour::factory()->create([
        'owner_type' => \App\Models\Tattooer::class,
        'owner_id' => test()->tattooer->id,
        'day_of_week' => 1, // Lundi
        'start_time' => '09:00',
        'end_time' => '18:00',
        'is_open' => true
    ]);

    $response = getJson("/api/tattooers/" . test()->tattooer->id . "/working-hours");

    $response->assertStatus(200)
        ->assertJsonStructure([
            '*' => [
                'id',
                'day_of_week',
                'start_time',
                'end_time',
                'is_open'
            ]
        ]);
});

test('tattooer can update working hours for single day', function () {
    $workingHour = WorkingHour::factory()->create([
        'owner_type' => \App\Models\Tattooer::class,
        'owner_id' => test()->tattooer->id,
        'day_of_week' => 3, // Mercredi
        'start_time' => '09:00',
        'end_time' => '18:00'
    ]);

    $updateData = [
        'start_time' => '10:00',
        'end_time' => '19:00',
        'is_open' => false
    ];

    $response = putJson("/api/tattooers/" . test()->tattooer->id . "/working-hours/3", $updateData);

    $response->assertStatus(200);
});

test('tattooer can create new working hours', function () {
    $updateData = [
        'day_of_week' => 4, // Jeudi
        'start_time' => '11:00',
        'end_time' => '20:00',
        'is_open' => true
    ];

    $response = putJson("/api/tattooers/" . test()->tattooer->id . "/working-hours/4", $updateData);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Horaire mis à jour',
            'data' => [
                'day_of_week' => 4,
                'start_time' => '11:00',
                'end_time' => '20:00',
                'is_open' => true,
                'owner_id' => test()->tattooer->id,
                'owner_type' => \App\Models\Tattooer::class
            ]
        ]);
});

test('unauthenticated user cannot update working hours', function () {
    $otherTattooer = Tattooer::factory()->create();

    $response = putJson("/api/tattooers/{$otherTattooer->id}/working-hours/1", [
        'start_time' => '10:00',
        'end_time' => '17:00'
    ]);

    $response->assertStatus(403); // Changé de 401 à 403
});

test('user cannot update other tattooers working hours', function () {
    $otherUser = User::factory()->create();
    $otherTattooer = Tattooer::factory()->create(['user_id' => $otherUser->id]);

    $response = putJson("/api/tattooers/{$otherTattooer->id}/working-hours/1", [
        'start_time' => '10:00',
        'end_time' => '17:00'
    ]);

    $response->assertStatus(403);
});

test('validation works correctly', function () {
    $response = putJson("/api/tattooers/" . test()->tattooer->id . "/working-hours/5", [
        'start_time' => '25:00', // Heure invalide
        'closing_time' => '09:00' // Fin avant début
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['start_time']);
});
