<?php

use App\Actions\CompleteAppointment;
use App\Actions\ReportNoShow;
use App\Actions\ReportTattooerAbsence;
use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\User;
use App\Models\Tattooer;
use App\Models\AccountingTransaction;
use App\Enums\AppointmentStatus;
use App\Enums\BookingRequestStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

uses(RefreshDatabase::class);

test('tattooer can mark appointment as completed', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $user->id,
        'name' => 'Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    $clientUser = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $clientUser->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::DATE_CONFIRMED,
        'deposit_paid_at' => now(),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'confirmed_date' => now()->subHours(2)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->subHours(2)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subHours(2)->setTime(15, 0),
        'end_datetime' => now()->subHours(2)->addMinutes(120),
        'duration_minutes' => 120,
        'status' => AppointmentStatus::SCHEDULED,
    ]);

    // Test: Marquer le rendez-vous comme terminé
    $action = new CompleteAppointment();
    $action->execute($appointment, 'cash', 150.00);

    // Assertions
    $appointment->refresh();
    expect($appointment->status)->toBe(AppointmentStatus::COMPLETED);
    expect($appointment->actual_end_time)->not->toBeNull();

    $bookingRequest->refresh();
    expect($bookingRequest->status)->toBe(BookingRequestStatus::COMPLETED);

    // Vérifier la transaction de paiement final
    $finalTransaction = AccountingTransaction::where('booking_request_id', $bookingRequest->id)
        ->where('type', 'final_payment')
        ->first();
    
    expect($finalTransaction)->not->BeNull();
    expect($finalTransaction->amount)->toBe(150.00);
    expect($finalTransaction->payment_method)->toBe('cash');
});

test('no-show increments client counter', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $user->id,
        'name' => 'Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    $clientUser = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $clientUser->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
        'no_show_count' => 1, // Déjà 1 no-show
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::DATE_CONFIRMED,
        'deposit_paid_at' => now(),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'confirmed_date' => now()->subHours(2)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->subHours(2)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subHours(2)->setTime(15, 0),
        'end_datetime' => now()->subHours(2)->addMinutes(120),
        'duration_minutes' => 120,
        'status' => AppointmentStatus::SCHEDULED,
    ]);

    // Test: Signaler le no-show
    $action = new ReportNoShow();
    $action->execute($appointment, 'Client ne s\'est pas présenté');

    // Assertions
    $appointment->refresh();
    expect($appointment->status)->toBe(AppointmentStatus::NO_SHOW);
    expect($appointment->no_show_reported_at)->not->BeNull();
    expect($appointment->no_show_reason)->toBe('Client ne s\'est pas présenté');

    $bookingRequest->refresh();
    expect($bookingRequest->status)->toBe(BookingRequestStatus::NO_SHOW);

    $client->refresh();
    expect($client->no_show_count)->toBe(2); // Incrémenté de 1
    expect($client->user->status)->toBe('active'); // Pas encore banni
});

test('3 no-shows bans client', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $user->id,
        'name' => 'Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    $clientUser = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $clientUser->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
        'no_show_count' => 2, // Déjà 2 no-shows
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::DATE_CONFIRMED,
        'deposit_paid_at' => now(),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'confirmed_date' => now()->subHours(2)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->subHours(2)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subHours(2)->setTime(15, 0),
        'end_datetime' => now()->subHours(2)->addMinutes(120),
        'duration_minutes' => 120,
        'status' => AppointmentStatus::SCHEDULED,
    ]);

    // Test: Signaler le 3ème no-show
    $action = new ReportNoShow();
    $action->execute($appointment);

    // Assertions
    $client->refresh();
    expect($client->no_show_count)->toBe(3); // Incrémenté à 3
    
    $clientUser->refresh();
    expect($clientUser->status)->toBe('banned'); // Banni !
    expect($clientUser->banned_at)->not->BeNull();
    expect($clientUser->banned_reason)->toBe('3 no-shows detected');
});

test('appointment auto-completes after J+1', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $user->id,
        'name' => 'Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    $clientUser = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $clientUser->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::DATE_CONFIRMED,
        'deposit_paid_at' => now(),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'confirmed_date' => now()->subDays(2)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->subDays(2)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subDays(2)->setTime(15, 0),
        'end_datetime' => now()->subDays(2)->addMinutes(120),
        'duration_minutes' => 120,
        'status' => AppointmentStatus::SCHEDULED,
    ]);

    // Test: Auto-complétion J+1
    $command = new \App\Console\Commands\AutoCompleteAppointments();
    $command->handle();

    // Assertions
    $appointment->refresh();
    expect($appointment->status)->toBe(AppointmentStatus::COMPLETED);
    expect($appointment->actual_end_time)->not->BeNull();

    $bookingRequest->refresh();
    expect($bookingRequest->status)->toBe(BookingRequestStatus::COMPLETED);
});

test('banned client cannot create new booking requests', function () {
    // Setup
    $clientUser = User::create([
        'name' => 'Banned Client',
        'email' => 'banned@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
        'status' => 'banned', // Client déjà banni
    ]);
    
    $client = Client::create([
        'user_id' => $clientUser->id,
        'first_name' => 'Banned',
        'last_name' => 'Client',
        'pseudo' => 'bannedclient',
        'email' => 'banned@example.com',
    ]);

    // Test: Vérifier que le client banni ne peut pas créer de demande
    expect($clientUser->status)->toBe('banned');
    
    // Simuler une tentative de création de booking request
    $bookingRequestData = [
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => 1,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo from banned client',
        'status' => BookingRequestStatus::PENDING,
    ];

    // Vérifier que la validation empêche la création
    expect(fn() => BookingRequest::create($bookingRequestData))
        ->toThrow(\Exception::class); // Devrait lever une exception de validation
});

test('tattooer absence triggers full refund', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $user->id,
        'name' => 'Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    $clientUser = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $clientUser->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::DATE_CONFIRMED,
        'deposit_paid_at' => now(),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'confirmed_date' => now()->subHours(2)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->subHours(2)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subHours(2)->setTime(15, 0),
        'end_datetime' => now()->subHours(2)->addMinutes(120),
        'duration_minutes' => 120,
        'status' => AppointmentStatus::SCHEDULED,
    ]);

    // Test: Signaler l'absence du tattooer
    $action = new ReportTattooerAbsence();
    $action->execute($appointment, 'Tattooer absent du salon');

    // Assertions
    $appointment->refresh();
    expect($appointment->status)->toBe('tattooer_absent'); // Sera remplacé par le bon statut
    expect($appointment->tattooer_absence_reported_at)->not->BeNull();
    expect($appointment->tattooer_absence_reason)->toBe('Tattooer absent du salon');

    $bookingRequest->refresh();
    expect($bookingRequest->status)->toBe('tattooer_absent'); // Sera remplacé par le bon statut

    // Vérifier le remboursement
    expect($bookingRequest->refund_amount)->toBe(150.00);
    expect($bookingRequest->refund_processed_at)->not->BeNull();

    // Vérifier la transaction de remboursement
    $refundTransaction = AccountingTransaction::where('booking_request_id', $bookingRequest->id)
        ->where('type', 'refund')
        ->first();
    
    expect($refundTransaction)->not->BeNull();
    expect($refundTransaction->amount)->toBe(-150.00); // Négatif pour remboursement
    expect($refundTransaction->metadata['refund_reason'])->toBe('tattooer_absent');
});

test('client can report tattooer absence 15 minutes after start', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $user->id,
        'name' => 'Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    $clientUser = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $clientUser->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::DATE_CONFIRMED,
        'deposit_paid_at' => now(),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'confirmed_date' => now()->subMinutes(20)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->subMinutes(20)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subMinutes(20)->setTime(15, 0),
        'end_datetime' => now()->subMinutes(20)->addMinutes(120),
        'duration_minutes' => 120,
        'status' => AppointmentStatus::SCHEDULED,
    ]);

    // Test: Vérifier que le client peut signaler l'absence
    $action = new ReportTattooerAbsence();
    $canReport = $action->canReportTattooerAbsence($appointment);
    
    expect($canReport)->toBeTrue(); // 20 minutes > 15 minutes
    
    // Test: Signaler l'absence
    $action->execute($appointment, 'Tattooer pas au rendez-vous');
    
    // Assertions
    $appointment->refresh();
    expect($appointment->tattooer_absence_reported_at)->not->BeNull();
    expect($appointment->tattooer_absence_reason)->toBe('Tattooer pas au rendez-vous');
});

test('tattooer can report no-show 1 hour after appointment end', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $user->id,
        'name' => 'Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    $clientUser = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $clientUser->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::DATE_CONFIRMED,
        'deposit_paid_at' => now(),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'confirmed_date' => now()->subHours(2)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->subHours(2)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subHours(2)->setTime(15, 0),
        'end_datetime' => now()->subHours(2)->addMinutes(120),
        'duration_minutes' => 120,
        'status' => AppointmentStatus::SCHEDULED,
    ]);

    // Test: Vérifier que le tattooer peut signaler le no-show
    $action = new ReportNoShow();
    $canReport = $action->canReportNoShow($appointment);
    
    expect($canReport)->toBeTrue(); // 2 heures > 1 heure
    
    // Test: Signaler le no-show
    $action->execute($appointment, 'Client absent');
    
    // Assertions
    $appointment->refresh();
    expect($appointment->status)->toBe(AppointmentStatus::NO_SHOW);
    expect($appointment->no_show_reported_at)->not->BeNull();
    expect($appointment->no_show_reason)->toBe('Client absent');
});
