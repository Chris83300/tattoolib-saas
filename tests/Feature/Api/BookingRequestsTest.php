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
    test()->user = User::factory()->client()->create();
    test()->client = Client::factory()->create(['user_id' => test()->user->id]);
    test()->tattooer = Tattooer::factory()->verified()->create();
    actingAs(test()->user, 'sanctum');
});

test('client can create booking request', function () {
    $bookingData = [
        'bookable_type' => Tattooer::class,
        'bookable_id' => test()->tattooer->id,
        'description' => 'Tattoo de dragon sur le bras avec une description suffisamment longue pour valider',
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'estimated_budget' => 500,
    ];

    $response = postJson('/api/booking-requests', $bookingData);

    if ($response->status() !== 201) {
        dump([
            'status' => $response->status(),
            'json' => $response->json(),
            'exception' => $response->exception ? $response->exception->getMessage() : 'No exception'
        ]);
    }

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'booking_request' => [
                'id',
                'description',
                'status',
                'created_at'
            ]
        ]);

    test()->assertDatabaseHas('booking_requests', [
        'client_id' => test()->client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => test()->tattooer->id,
        'description' => 'Tattoo de dragon sur le bras avec une description suffisamment longue pour valider'
    ]);
});

test('cannot create booking request with invalid data', function () {
    $response = postJson('/api/booking-requests', [
        'user_id' => test()->user->id,
        // Missing required fields
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['description', 'tattoo_size', 'body_zone']);
});

test('client can see their booking requests', function () {
    $bookingRequest = BookingRequest::factory()->create([
        'client_id' => test()->client->id
    ]);

    $response = getJson('/api/booking-requests');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'description',
                    'status',
                    'created_at'
                ]
            ]
        ]);
});

test('tattooer can see booking requests for them', function () {
    $tattooerUser = User::factory()->tattooer()->create();
    $tattooer = Tattooer::factory()->verified()->create(['user_id' => $tattooerUser->id]);

    $clientUser = User::factory()->client()->create();
    $client = Client::factory()->create(['user_id' => $clientUser->id]);

    $bookingRequest = BookingRequest::factory()->create([
        'bookable_type' => \App\Models\Tattooer::class,
        'bookable_id' => $tattooer->id,
        'client_id' => $client->id
    ]);

    actingAs($tattooerUser, 'sanctum');
    $response = getJson('/api/booking-requests');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'description',
                    'status',
                    'created_at'
                ]
            ]
        ]);
});

test('tattooer can accept booking request', function () {
    $tattooerUser = User::factory()->tattooer()->create();
    $tattooer = Tattooer::factory()->verified()->create(['user_id' => $tattooerUser->id]);

    $clientUser = User::factory()->client()->create();
    $client = Client::factory()->create(['user_id' => $clientUser->id]);

    $scheduledDate = now()->addDays(7)->toDateString();

    // Créer une availability pour le tatoueur ce jour-là
    \App\Models\Availability::factory()->create([
        'owner_type' => \App\Models\Tattooer::class,
        'owner_id' => $tattooer->id,
        'date' => $scheduledDate,
        'start_time' => '09:00',
        'end_time' => '18:00',
        'type' => \App\Models\Availability::TYPE_AVAILABLE,
        'source' => \App\Models\Availability::SOURCE_WORKING_HOURS,
    ]);

    $bookingRequest = BookingRequest::factory()->create([
        'bookable_type' => \App\Models\Tattooer::class,
        'bookable_id' => $tattooer->id,
        'client_id' => $client->id,
        'status' => \App\Models\BookingRequest::STATUS_PENDING,
        'preferred_date' => $scheduledDate,
    ]);

    actingAs($tattooerUser, 'sanctum');
    $response = postJson("/api/booking-requests/{$bookingRequest->id}/accept", [
        'scheduled_date' => $scheduledDate,
        'scheduled_start_time' => '14:00',
        'scheduled_duration_minutes' => 120,
        'total_price' => 300,
        'deposit_rate' => 30,
        'deposit_deadline_hours' => 48
    ]);

    $response->assertStatus(200);
    test()->assertDatabaseHas('booking_requests', [
        'id' => $bookingRequest->id,
        'status' => 'accepted'
    ]);
});

test('tattooer can reject booking request', function () {
    $tattooerUser = User::factory()->tattooer()->create();
    $tattooer = Tattooer::factory()->verified()->create(['user_id' => $tattooerUser->id]);

    $clientUser = User::factory()->client()->create();
    $client = Client::factory()->create(['user_id' => $clientUser->id]);

    $bookingRequest = BookingRequest::factory()->create([
        'bookable_type' => \App\Models\Tattooer::class,
        'bookable_id' => $tattooer->id,
        'client_id' => $client->id,
        'status' => \App\Models\BookingRequest::STATUS_PENDING
    ]);

    actingAs($tattooerUser, 'sanctum');
    $response = postJson("/api/booking-requests/{$bookingRequest->id}/reject", [
        'rejection_reason' => 'Pas de disponibilité'
    ]);

    $response->assertStatus(200);
    test()->assertDatabaseHas('booking_requests', [
        'id' => $bookingRequest->id,
        'status' => 'rejected'
    ]);
});

test('client cannot accept booking request', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->verified()->create(['user_id' => $tattooerUser->id]);

    $clientUser = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $clientUser->id]);

    $bookingRequest = BookingRequest::factory()->create([
        'bookable_type' => \App\Models\Tattooer::class,
        'bookable_id' => $tattooer->id,
        'client_id' => $client->id,
        'status' => \App\Models\BookingRequest::STATUS_PENDING
    ]);

    actingAs($clientUser, 'sanctum');
    $response = postJson("/api/booking-requests/{$bookingRequest->id}/accept");

    $response->assertStatus(403);
});

test('client can access their own booking request', function () {
    $bookingRequest = BookingRequest::factory()->create([
        'client_id' => test()->client->id
    ]);

    $response = getJson("/api/booking-requests/{$bookingRequest->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'description',
            'status',
            'created_at'
        ]);
});

test('cannot access other users booking requests', function () {
    $otherUser = User::factory()->create();
    $otherTattooer = Tattooer::factory()->verified()->create(['user_id' => $otherUser->id]);

    $otherClientUser = User::factory()->create();
    $otherClient = Client::factory()->create(['user_id' => $otherClientUser->id]);

    $bookingRequest = BookingRequest::factory()->create([
        'bookable_type' => \App\Models\Tattooer::class,
        'bookable_id' => $otherTattooer->id,
        'client_id' => $otherClient->id,
    ]);

    // Client différent qui essaie d'accéder
    $clientUser = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $clientUser->id]);

    actingAs($clientUser, 'sanctum');
    $response = getJson("/api/booking-requests/{$bookingRequest->id}");

    $response->assertStatus(403);
});
