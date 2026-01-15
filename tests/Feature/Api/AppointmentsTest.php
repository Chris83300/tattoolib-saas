<?php

use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\Tattooer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

beforeEach(function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['user_id' => $user->id]);
    $tattooer = Tattooer::factory()->create();
    actingAs($user, 'sanctum');

    // Rendre les variables accessibles dans les tests
    test()->client = $client;
    test()->tattooer = $tattooer;
    test()->user = $user;
});

// Tests de liste des rendez-vous
test('client can see their appointments', function () {
    $appointment = Appointment::factory()->create([
        'client_id' => test()->client->id
    ]);

    $response = getJson('/api/appointments');

    $response->assertStatus(200)
        ->assertJsonStructure([
            '*' => [
                'id',
                'opening_time',
                'closing_time',
                'status',
                'duration_minutes',
                'total_price'
            ]
        ]);
});

test('tattooer can see their appointments', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

    $appointment = Appointment::factory()->create([
        'tattooer_id' => $tattooer->id
    ]);

    actingAs($tattooerUser, 'sanctum');
    $response = getJson('/api/appointments');

    $response->assertStatus(200)
        ->assertJsonCount(1);
});

// Tests des rendez-vous à venir
test('user can see upcoming appointments', function () {
    $appointment = Appointment::factory()->create([
        'client_id' => test()->client->id,
        'opening_time' => Carbon::now()->addDays(2),
        'closing_time' => Carbon::now()->addDays(2)->addHours(2),
        'status' => 'confirmed'
    ]);

    $response = getJson('/api/appointments/upcoming');

    $response->assertStatus(200)
        ->assertJsonCount(1);
});

test('user can see past appointments', function () {
    $appointment = Appointment::factory()->create([
        'client_id' => test()->client->id,
        'opening_time' => Carbon::now()->subDays(2),
        'closing_time' => Carbon::now()->subDays(2)->addHours(2),
        'status' => 'completed'
    ]);

    $response = getJson('/api/appointments/past');

    $response->assertStatus(200)
        ->assertJsonCount(1);
});

// Tests de confirmation de rendez-vous
test('client can confirm appointment', function () {
    $bookingRequest = BookingRequest::factory()->create([
        'client_id' => test()->client->id,
        'status' => 'accepted'
    ]);

    $appointment = Appointment::factory()->create([
        'client_id' => test()->client->id,
        'booking_request_id' => $bookingRequest->id,
        'status' => 'pending_confirmation'
    ]);

    $response = postJson("/api/booking-requests/{$bookingRequest->id}/confirm-appointment");

    $response->assertStatus(200);
    test()->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'status' => 'confirmed'
    ]);
});

// Tests d'annulation
test('client can cancel appointment', function () {
    $appointment = Appointment::factory()->create([
        'client_id' => test()->client->id,
        'status' => 'confirmed'
    ]);

    $response = postJson("/api/appointments/{$appointment->id}/cancel");

    $response->assertStatus(200);
    test()->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'status' => 'cancelled'
    ]);
});

test('tattooer can cancel appointment', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

    $appointment = Appointment::factory()->create([
        'tattooer_id' => $tattooer->id,
        'status' => 'confirmed'
    ]);

    actingAs($tattooerUser, 'sanctum');
    $response = postJson("/api/appointments/{$appointment->id}/cancel");

    $response->assertStatus(200);
});

// Tests de complétion
test('tattooer can confirm appointment completion', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

    $appointment = Appointment::factory()->create([
        'tattooer_id' => $tattooer->id,
        'status' => 'confirmed'
    ]);

    actingAs($tattooerUser, 'sanctum');
    $response = postJson("/api/appointments/{$appointment->id}/confirm-completion");

    $response->assertStatus(200);
    test()->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'status' => 'completed'
    ]);
});

// Tests de signalement
test('tattooer can report no-show', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

    $appointment = Appointment::factory()->create([
        'tattooer_id' => $tattooer->id,
        'status' => 'confirmed',
        'opening_time' => Carbon::now()->subHours(1),
        'closing_time' => Carbon::now()->subHours(1)->addHours(2),
    ]);

    actingAs($tattooerUser, 'sanctum');
    $response = postJson("/api/appointments/{$appointment->id}/report-no-show");

    $response->assertStatus(200);
});

test('tattooer can report issue', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

    $appointment = Appointment::factory()->create([
        'tattooer_id' => $tattooer->id,
        'status' => 'confirmed'
    ]);

    actingAs($tattooerUser, 'sanctum');
    $response = postJson("/api/appointments/{$appointment->id}/report-issue", [
        'reason' => 'Client was aggressive',
        'description' => 'The client was disrespectful during the session'
    ]);

    $response->assertStatus(200);
});

// Tests d'autorisation
test('user cannot see other users appointments', function () {
    $otherUser = User::factory()->create();
    $otherClient = Client::factory()->create(['user_id' => $otherUser->id]);
    $appointment = Appointment::factory()->create([
        'client_id' => $otherClient->id
    ]);

    $response = getJson("/api/appointments/{$appointment->id}");
    $response->assertStatus(403);
});

test('user cannot cancel other users appointment', function () {
    $otherUser = User::factory()->create();
    $otherClient = Client::factory()->create(['user_id' => $otherUser->id]);
    $appointment = Appointment::factory()->create([
        'client_id' => $otherClient->id,
        'status' => 'confirmed'
    ]);

    $response = postJson("/api/appointments/{$appointment->id}/cancel");
    $response->assertStatus(403);
});

// Tests de calendrier
test('user can get calendar view', function () {
    Appointment::factory()->count(3)->create([
        'client_id' => test()->client->id,
        'opening_time' => '2026-01-15 10:00:00',
        'closing_time' => '2026-01-15 12:00:00'
    ]);

    $year = 2026;
    $month = 1;
    $response = getJson("/api/appointments/calendar?year={$year}&month={$month}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            '*' => [
                'date',
                'appointments'
            ]
        ]);
});

test('user can get appointment statistics', function () {
    Appointment::factory()->count(5)->create([
        'client_id' => test()->client->id,
        'status' => 'completed'
    ]);
    Appointment::factory()->count(2)->create([
        'client_id' => test()->client->id,
        'status' => 'cancelled'
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
