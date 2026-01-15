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
        'fcm_token' => 'test_fcm_token_123456789'
    ];

    $response = postJson('/api/fcm-token', $tokenData);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'FCM token stored successfully'
        ]);

    test()->assertDatabaseHas('fcm_tokens', [
        'user_id' => test()->user->id,
        'token' => 'test_fcm_token_123456789'
    ]);
});

test('user cannot store empty fcm token', function () {
    $response = postJson('/api/fcm-token', [
        'fcm_token' => ''
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['fcm_token']);
});

test('user cannot store fcm token without authentication', function () {
    auth()->logout();

    $response = postJson('/api/fcm-token', [
        'fcm_token' => 'test_token'
    ]);

    $response->assertStatus(401);
});

test('user can update their fcm token', function () {
    // D'abord stocker un token
    postJson('/api/fcm-token', [
        'fcm_token' => 'old_token'
    ]);

    // Puis le mettre à jour
    $response = postJson('/api/fcm-token', [
        'fcm_token' => 'new_token'
    ]);

    $response->assertStatus(200);

    test()->assertDatabaseHas('fcm_tokens', [
        'user_id' => test()->user->id,
        'token' => 'new_token'
    ]);

    test()->assertDatabaseMissing('fcm_tokens', [
        'user_id' => test()->user->id,
        'token' => 'old_token'
    ]);
});

test('fcm token validation', function () {
    $response = postJson('/api/fcm-token', [
        'fcm_token' => 'too_short'
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['fcm_token']);
});

test('multiple users can have different fcm tokens', function () {
    $user2 = User::factory()->create();

    // Premier utilisateur
    postJson('/api/fcm-token', [
        'fcm_token' => 'token_user_1'
    ]);

    // Deuxième utilisateur
    actingAs($user2, 'sanctum');
    $response = postJson('/api/fcm-token', [
        'fcm_token' => 'token_user_2'
    ]);

    $response->assertStatus(200);

    test()->assertDatabaseHas('fcm_tokens', [
        'user_id' => test()->user->id,
        'token' => 'token_user_1'
    ]);

    test()->assertDatabaseHas('fcm_tokens', [
        'user_id' => $user2->id,
        'token' => 'token_user_2'
    ]);
});
