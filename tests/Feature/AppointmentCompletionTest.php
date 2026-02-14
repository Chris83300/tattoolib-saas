<?php

use App\Models\User;
use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Enums\AppointmentStatus;
use App\Enums\BookingRequestStatus;
use App\Actions\CompleteAppointmentAction;
use App\Actions\ReportNoShowAction;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Artisan;

// === COMPLÉTION MANUELLE ===

test('tattooer can complete a past appointment', function () {
    Notification::fake();

    $user = User::factory()->create();
    $tattooer = \App\Models\Tattooer::factory()->create(['user_id' => $user->id]);

    $booking = BookingRequest::factory()->create([
        'status' => 'date_confirmed', // Utiliser la valeur string directe
        'bookable_type' => get_class($tattooer),
        'bookable_id' => $tattooer->id,
    ]);

    $appointment = Appointment::factory()->create([
        'booking_request_id' => $booking->id,
        'status' => AppointmentStatus::CONFIRMED,
        'end_datetime' => now()->subHours(2),
    ]);

    $action = new CompleteAppointmentAction();
    $result = $action->execute($appointment, 'tattooer', 'Très bien passé');

    expect($result->status)->toBe(AppointmentStatus::COMPLETED);
    expect($result->completed_by)->toBe('tattooer');
    expect($result->completed_at)->not->toBeNull();
    expect($result->completion_notes)->toBe('Très bien passé');
    expect($booking->fresh()->status)->toBe('completed');
});

test('cannot complete a future appointment', function () {
    $user = User::factory()->create();
    $tattooer = \App\Models\Tattooer::factory()->create(['user_id' => $user->id]);

    $booking = BookingRequest::factory()->create([
        'bookable_type' => get_class($tattooer),
        'bookable_id' => $tattooer->id,
    ]);

    $appointment = Appointment::factory()->create([
        'booking_request_id' => $booking->id,
        'status' => AppointmentStatus::CONFIRMED,
        'end_datetime' => now()->addHours(2),
    ]);

    actingAs($user)
        ->post(route('tattooer.appointments.complete', $appointment))
        ->assertRedirect()
        ->assertSessionHas('error');
});

// === AUTO-COMPLÉTION ===

test('scheduler auto-completes appointments after 24h', function () {
    Notification::fake();

    $booking = BookingRequest::factory()->create([
        'status' => 'date_confirmed', // Utiliser la valeur string directe
    ]);

    $old = Appointment::factory()->create([
        'booking_request_id' => $booking->id,
        'status' => AppointmentStatus::CONFIRMED,
        'end_datetime' => now()->subHours(25),
    ]);

    $recent = Appointment::factory()->create([
        'booking_request_id' => $booking->id,
        'status' => AppointmentStatus::CONFIRMED,
        'end_datetime' => now()->subHours(2),
    ]);

    Artisan::call('app:check-completed-appointments');

    expect($old->fresh()->status)->toBe(AppointmentStatus::COMPLETED);
    expect($old->fresh()->completed_by)->toBe('system');
    expect($recent->fresh()->status)->toBe(AppointmentStatus::CONFIRMED); // pas encore 24h
});

// === NO-SHOW ===

test('tattooer can report client no-show', function () {
    Notification::fake();

    $user = User::factory()->create();
    $tattooer = \App\Models\Tattooer::factory()->create(['user_id' => $user->id]);

    $booking = BookingRequest::factory()->create([
        'status' => 'date_confirmed', // Utiliser la valeur string directe
        'bookable_type' => get_class($tattooer),
        'bookable_id' => $tattooer->id,
    ]);

    $appointment = Appointment::factory()->create([
        'booking_request_id' => $booking->id,
        'status' => AppointmentStatus::CONFIRMED,
        'end_datetime' => now()->subHours(2),
    ]);

    $action = new ReportNoShowAction();
    $result = $action->execute($appointment, 'tattooer', 'Client ne s\'est pas présenté');

    expect($result->status)->toBe(AppointmentStatus::NO_SHOW_CLIENT);
    expect($result->no_show_reported_by)->toBe('tattooer');
    expect($result->no_show_reason)->toBe('Client ne s\'est pas présenté');
    expect($booking->fresh()->status)->toBe('no_show');
});

test('client can report artist no-show', function () {
    Notification::fake();

    $client = User::factory()->create();
    $tattooer = \App\Models\Tattooer::factory()->create();

    $booking = BookingRequest::factory()->create([
        'status' => 'date_confirmed', // Utiliser la valeur string directe
        'client_id' => $client->id,
        'bookable_type' => get_class($tattooer),
        'bookable_id' => $tattooer->id,
    ]);

    $appointment = Appointment::factory()->create([
        'booking_request_id' => $booking->id,
        'status' => AppointmentStatus::CONFIRMED,
        'end_datetime' => now()->subHours(2),
    ]);

    $action = new ReportNoShowAction();
    $result = $action->execute($appointment, 'client');

    expect($result->status)->toBe(AppointmentStatus::NO_SHOW_ARTIST);
    expect($result->no_show_reported_by)->toBe('client');
    expect($booking->fresh()->status)->toBe('no_show');
});

// === BOOKING REQUEST STATUS ===

test('booking request transitions to completed when appointment completed', function () {
    Notification::fake();

    $booking = BookingRequest::factory()->create([
        'status' => 'date_confirmed', // Utiliser la valeur string directe
    ]);

    $appointment = Appointment::factory()->create([
        'booking_request_id' => $booking->id,
        'status' => AppointmentStatus::CONFIRMED,
        'end_datetime' => now()->subHours(2),
    ]);

    $action = new CompleteAppointmentAction();
    $action->execute($appointment, 'tattooer');

    expect($booking->fresh()->status)->toBe('completed');
});

// === NETTOYAGE AUTOMATIQUE ===

test('expired deposit booking is deleted after deadline', function () {
    Notification::fake();

    $clientUser = User::factory()->create(); // Utiliser User factory
    $booking = BookingRequest::factory()->create([
        'client_id' => $clientUser->id, // Associer au user
        'status' => 'deposit_requested', // Utiliser la valeur string directe
        'deposit_deadline' => now()->subHours(1), // deadline passée
    ]);

    Artisan::call('app:cleanup-expired-booking-requests');
    // Le BookingRequest ne doit plus exister
    expect(BookingRequest::find($booking->id))->toBeNull();
});

test('cancelled booking is deleted after 2 days', function () {
    $booking = BookingRequest::factory()->create([
        'status' => 'cancelled', // Utiliser la valeur string directe
        'updated_at' => now()->subDays(3), // annulé il y a 3 jours
    ]);

    Artisan::call('app:cleanup-expired-booking-requests');

    expect(BookingRequest::find($booking->id))->toBeNull();
});

test('rejected booking is deleted after 2 days', function () {
    $booking = BookingRequest::factory()->create([
        'status' => 'rejected', // Utiliser la valeur string directe
        'updated_at' => now()->subDays(3),
    ]);

    Artisan::call('app:cleanup-expired-booking-requests');

    expect(BookingRequest::find($booking->id))->toBeNull();
});

test('recently cancelled booking is NOT deleted', function () {
    $booking = BookingRequest::factory()->create([
        'status' => 'cancelled', // Utiliser la valeur string directe
        'updated_at' => now()->subHours(12), // annulé il y a 12h seulement
    ]);

    Artisan::call('app:cleanup-expired-booking-requests');

    expect(BookingRequest::find($booking->id))->not->toBeNull();
});

test('active booking with valid deposit deadline is NOT deleted', function () {
    $booking = BookingRequest::factory()->create([
        'status' => 'deposit_requested', // Utiliser la valeur string directe
        'deposit_deadline' => now()->addDays(3), // deadline dans 3 jours
    ]);

    Artisan::call('app:cleanup-expired-booking-requests');

    expect(BookingRequest::find($booking->id))->not->toBeNull();
});
