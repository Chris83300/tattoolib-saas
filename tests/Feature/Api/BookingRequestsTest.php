<?php

use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\Tattooer;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
    test()->user = User::factory()->create();
    test()->client = Client::factory()->create(['user_id' => test()->user->id]);
    test()->tattooer = Tattooer::factory()->create();
    actingAs(test()->user, 'sanctum');
});

// Tests de création de réservation
test('client can create booking request', function () {
    $bookingData = [
        'tattooer_id' => test()->tattooer->id,
        'description' => 'Tattoo de dragon sur le bras',
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'preferred_days' => [1, 2, 3, 4, 5, 6, 7],
        'estimated_budget' => 500,
    ];

    $response = postJson('/api/booking-requests', $bookingData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'id',
            'description',
            'status',
            'created_at'
        ]);

    test()->assertDatabaseHas('booking_requests', [
        'client_id' => test()->client->id,
        'tattooer_id' => test()->tattooer->id,
        'description' => 'Tattoo de dragon sur le bras'
    ]);
});

test('cannot create booking request with invalid data', function () {
    $response = postJson('/api/booking-requests', [
        'tattooer_id' => test()->tattooer->id,
        // Missing required fields
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['description', 'preferred_days']);
});

// Tests de liste des réservations
test('client can see their booking requests', function () {
    $bookingRequest = BookingRequest::factory()->create([
        'client_id' => test()->client->id
    ]);

    $response = getJson('/api/booking-requests');

    $response->assertStatus(200)
        ->assertJsonStructure([
            '*' => [
                'id',
                'description',
                'status',
                'created_at'
            ]
        ]);
});

test('tattooer can see booking requests for them', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

    $bookingRequest = BookingRequest::factory()->create([
        'tattooer_id' => $tattooer->id
    ]);

    actingAs($tattooerUser, 'sanctum');
    $response = getJson('/api/booking-requests');

    $response->assertStatus(200)
        ->assertJsonCount(1);
});

// Tests d'acceptation/rejet
test('tattooer can accept booking request', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

    $bookingRequest = BookingRequest::factory()->create([
        'tattooer_id' => $tattooer->id,
        'status' => 'pending'
    ]);

    actingAs($tattooerUser, 'sanctum');
    $response = postJson("/api/booking-requests/{$bookingRequest->id}/accept");

    $response->assertStatus(200);
    test()->assertDatabaseHas('booking_requests', [
        'id' => $bookingRequest->id,
        'status' => 'accepted'
    ]);
});

test('tattooer can reject booking request', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

    $bookingRequest = BookingRequest::factory()->create([
        'tattooer_id' => $tattooer->id,
        'status' => 'pending'
    ]);

    actingAs($tattooerUser, 'sanctum');
    $response = postJson("/api/booking-requests/{$bookingRequest->id}/reject");

    $response->assertStatus(200);
    test()->assertDatabaseHas('booking_requests', [
        'id' => $bookingRequest->id,
        'status' => 'rejected'
    ]);
});

test('client cannot accept booking request', function () {
    $bookingRequest = BookingRequest::factory()->create([
        'client_id' => test()->client->id,
        'status' => 'pending'
    ]);

    $response = postJson("/api/booking-requests/{$bookingRequest->id}/accept");
    $response->assertStatus(403);
});

// Tests d'autorisation
test('cannot access other users booking requests', function () {
    $otherUser = User::factory()->create();
    $otherClient = Client::factory()->create(['user_id' => $otherUser->id]);
    $bookingRequest = BookingRequest::factory()->create([
        'client_id' => $otherClient->id
    ]);

    $response = getJson("/api/booking-requests/{$bookingRequest->id}");
    $response->assertStatus(403);
});
