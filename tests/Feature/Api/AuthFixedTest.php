<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\actingAs;

// Tests d'inscription
test('user can register with valid data', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'client'
    ];

    $response = postJson('/api/register', $userData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'name',
                'email',
                'created_at'
            ],
            'access_token',
            'token_type'
        ]);

    expect(DB::table('users')->where(['email' => 'test@example.com'])->exists())->toBeTrue();
});

test('user cannot register with invalid email', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'invalid-email',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'client'
    ];

    $response = postJson('/api/register', $userData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('user cannot register with mismatched passwords', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'different',
        'role' => 'client'
    ];

    $response = postJson('/api/register', $userData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});

test('user can login with correct credentials', function () {
    $user = User::factory()->create();

    $response = postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password'
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'user' => [
                'id',
                'name',
                'email'
            ]
        ]);
});

test('user cannot login with incorrect password', function () {
    $user = User::factory()->create();

    $response = postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrongpassword'
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('user cannot login with nonexistent email', function () {
    $response = postJson('/api/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'password'
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('protected route requires authentication', function () {
    $response = getJson('/api/user');

    $response->assertStatus(401);
});

test('authenticated user can access user info', function () {
    $user = User::factory()->create();

    $response = actingAs($user, 'sanctum')
        ->getJson('/api/user');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'name',
            'email',
            'email_verified_at'
        ]);
});

test('user can logout', function () {
    $user = User::factory()->create();

    $response = postJson('/api/logout', []);

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Unauthenticated.'
        ]);
});

test('cannot logout without token', function () {
    $response = postJson('/api/logout', []);

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Unauthenticated.'
        ]);
});
