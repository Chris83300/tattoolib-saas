<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

beforeEach(function () {
    test()->user = User::factory()->create();
    actingAs(test()->user, 'sanctum');
});

// Tests de stockage du token FCM
test('user can store fcm token', function () {
    $tokenData = [
        'token' => 'test_fcm_token_123456789'
    ];

    $response = postJson('/api/fcm-token', $tokenData);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Token enregistré avec succès'
        ]);

    test()->assertDatabaseHas('users', [
        'id' => test()->user->id,
        'fcm_token' => 'test_fcm_token_123456789'
    ]);
});

test('user cannot store empty fcm token', function () {
    $response = postJson('/api/fcm-token', [
        'token' => ''
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['token']);
});

test('user cannot store fcm token without authentication', function () {
    // Créer un nouvel utilisateur sans l'authentifier
    $unauthenticatedUser = User::factory()->create();

    $response = postJson('/api/fcm-token', [
        'token' => 'test_token'
    ]);

    $response->assertStatus(401);
});

test('user can update their fcm token', function () {
    // D'abord stocker un token
    postJson('/api/fcm-token', [
        'token' => 'old_token'
    ]);

    // Puis le mettre à jour
    $response = postJson('/api/fcm-token', [
        'token' => 'new_token'
    ]);

    $response->assertStatus(200);

    test()->assertDatabaseHas('users', [
        'id' => test()->user->id,
        'fcm_token' => 'new_token'
    ]);

    test()->assertDatabaseMissing('users', [
        'id' => test()->user->id,
        'fcm_token' => 'old_token'
    ]);
});

test('fcm token validation', function () {
    $response = postJson('/api/fcm-token', [
        'token' => ''
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['token']);
});

test('multiple users can have different fcm tokens', function () {
    $user2 = User::factory()->create();

    // Premier utilisateur
    postJson('/api/fcm-token', [
        'token' => 'token_user_1'
    ]);

    // Deuxième utilisateur
    actingAs($user2, 'sanctum');
    $response = postJson('/api/fcm-token', [
        'token' => 'token_user_2'
    ]);

    $response->assertStatus(200);

    test()->assertDatabaseHas('users', [
        'id' => test()->user->id,
        'fcm_token' => 'token_user_1'
    ]);

    test()->assertDatabaseHas('users', [
        'id' => $user2->id,
        'fcm_token' => 'token_user_2'
    ]);
});
