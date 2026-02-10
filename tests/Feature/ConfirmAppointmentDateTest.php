<?php

use App\Actions\ConfirmAppointmentDate;
use App\Actions\RequestAlternativeDate;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\User;
use App\Models\Tattooer;
use App\Models\Appointment;
use App\Models\Conversation;
use App\Models\Message;
use App\Enums\BookingRequestStatus;
use App\Enums\AppointmentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

uses(RefreshDatabase::class);

test('client can select a proposed date', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'client@example.com',
    ]);
    
    $tattooerUser = User::create([
        'name' => 'Test Tattooer',
        'email' => 'tattooer@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $tattooerUser->id,
        'name' => 'Test Tattooer',
        'slug' => 'test-tattooer',
        'email' => 'tattooer@example.com',
        'siret' => '12345678901234',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::DEPOSIT_PAID,
        'deposit_paid_at' => now(),
        'total_deposit_amount' => 150.00,
        'proposed_dates' => [
            ['date' => '2026-03-15', 'period' => 'morning'],
            ['date' => '2026-03-16', 'period' => 'afternoon'],
            ['date' => '2026-03-17', 'period' => 'evening'],
        ],
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => 'full_access',
    ]);

    $action = new ConfirmAppointmentDate();

    // Test: Sélectionner une date proposée
    $action->execute($bookingRequest, '2026-03-15', 'morning', '10:00', 120);

    // Assertions
    $bookingRequest->refresh();
    expect($bookingRequest->confirmed_date)->toBe('2026-03-15');
    expect($bookingRequest->confirmed_period)->toBe('morning');
    expect($bookingRequest->appointment_datetime)->toBe('2026-03-15 10:00:00');
    expect($bookingRequest->appointment_duration_minutes)->toBe(120);
    expect($bookingRequest->status)->toBe(BookingRequestStatus::DATE_CONFIRMED);

    // Vérifier que l'appointment a été créé
    $appointment = $bookingRequest->appointment;
    expect($appointment)->not->toBeNull();
    expect($appointment->start_datetime->format('Y-m-d H:i:s'))->toBe('2026-03-15 10:00:00');
    expect($appointment->end_datetime->format('Y-m-d H:i:s'))->toBe('2026-03-15 12:00:00');
    expect($appointment->status)->toBe(AppointmentStatus::SCHEDULED);
});

test('selecting date creates appointment', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client 2',
        'email' => 'client2@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client 2',
        'pseudo' => 'testclient2',
        'email' => 'client2@example.com',
    ]);
    
    $tattooerUser = User::create([
        'name' => 'Test Tattooer 2',
        'email' => 'tattooer2@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $tattooerUser->id,
        'name' => 'Test Tattooer 2',
        'slug' => 'test-tattooer-2',
        'email' => 'tattooer2@example.com',
        'siret' => '12345678901235',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::DEPOSIT_PAID,
        'deposit_paid_at' => now(),
        'total_deposit_amount' => 150.00,
        'proposed_dates' => [
            ['date' => '2026-03-20', 'period' => 'afternoon'],
        ],
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => 'full_access',
    ]);

    $action = new ConfirmAppointmentDate();

    // Test: Créer appointment avec heure par défaut (après-midi = 14:00)
    $action->execute($bookingRequest, '2026-03-20', 'afternoon');

    // Assertions
    $bookingRequest->refresh();
    expect($bookingRequest->appointment_datetime)->toBe('2026-03-20 14:00:00');

    $appointment = $bookingRequest->appointment;
    expect($appointment->start_datetime->format('Y-m-d H:i:s'))->toBe('2026-03-20 14:00:00');
    expect($appointment->end_datetime->format('Y-m-d H:i:s'))->toBe('2026-03-20 16:00:00'); // 2h par défaut
});

test('booking transitions to date_confirmed', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client 3',
        'email' => 'client3@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client 3',
        'pseudo' => 'testclient3',
        'email' => 'client3@example.com',
    ]);
    
    $tattooerUser = User::create([
        'name' => 'Test Tattooer 3',
        'email' => 'tattooer3@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $tattooerUser->id,
        'name' => 'Test Tattooer 3',
        'slug' => 'test-tattooer-3',
        'email' => 'tattooer3@example.com',
        'siret' => '12345678901236',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::DEPOSIT_PAID,
        'deposit_paid_at' => now(),
        'total_deposit_amount' => 150.00,
        'proposed_dates' => [
            ['date' => '2026-03-25', 'period' => 'evening'],
        ],
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => 'full_access',
    ]);

    $action = new ConfirmAppointmentDate();

    // Test: Transition vers date_confirmed
    $action->execute($bookingRequest, '2026-03-25', 'evening', '18:30', 180);

    // Assertions
    $bookingRequest->refresh();
    expect($bookingRequest->status)->toBe(BookingRequestStatus::DATE_CONFIRMED);
    expect($bookingRequest->appointment_datetime)->toBe('2026-03-25 18:30:00');
    expect($bookingRequest->appointment_duration_minutes)->toBe(180);
});

test('client can request alternative date', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client 4',
        'email' => 'client4@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client 4',
        'pseudo' => 'testclient4',
        'email' => 'client4@example.com',
    ]);
    
    $tattooerUser = User::create([
        'name' => 'Test Tattooer 4',
        'email' => 'tattooer4@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $tattooerUser->id,
        'name' => 'Test Tattooer 4',
        'slug' => 'test-tattooer-4',
        'email' => 'tattooer4@example.com',
        'siret' => '12345678901237',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::DEPOSIT_PAID,
        'deposit_paid_at' => now(),
        'total_deposit_amount' => 150.00,
        'proposed_dates' => [
            ['date' => '2026-03-10', 'period' => 'morning'],
            ['date' => '2026-03-11', 'period' => 'afternoon'],
        ],
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => 'full_access',
    ]);

    $action = new RequestAlternativeDate();

    // Test: Demander une date alternative
    $action->execute($bookingRequest, 'Les dates proposées ne me conviennent pas, seriez-vous disponible le week-end du 15 mars ?');

    // Assertions
    $messages = $conversation->messages()->get();
    expect($messages)->toHaveCount(2); // Message client + message système

    $clientMessage = $messages->firstWhere('sender_type', 'client');
    expect($clientMessage)->not->toBeNull();
    expect($clientMessage->content)->toContain('Les dates proposées ne me conviennent pas');

    $systemMessage = $messages->firstWhere('sender_type', 'system');
    expect($systemMessage)->not->BeNull();
    expect($systemMessage->content)->toContain('🔄 Demande de nouvelle date');
});

test('tattooer can add appointment to calendar in one click', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client 5',
        'email' => 'client5@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client 5',
        'pseudo' => 'testclient5',
        'email' => 'client5@example.com',
    ]);
    
    $tattooerUser = User::create([
        'name' => 'Test Tattooer 5',
        'email' => 'tattooer5@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $tattooerUser->id,
        'name' => 'Test Tattooer 5',
        'slug' => 'test-tattooer-5',
        'email' => 'tattooer5@example.com',
        'siret' => '12345678901238',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::DATE_CONFIRMED,
        'confirmed_date' => '2026-03-15',
        'confirmed_period' => 'morning',
        'appointment_datetime' => '2026-03-15 10:00:00',
        'appointment_duration_minutes' => 120,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => '2026-03-15 10:00:00',
        'end_datetime' => '2026-03-15 12:00:00',
        'duration_minutes' => 120,
        'status' => AppointmentStatus::SCHEDULED,
    ]);

    // Test: L'appointment est déjà créé et prêt pour le calendrier
    expect($appointment->status)->toBe(AppointmentStatus::SCHEDULED);
    expect($appointment->start_datetime->format('Y-m-d H:i'))->toBe('2026-03-15 10:00');
    expect($appointment->end_datetime->format('Y-m-d H:i'))->toBe('2026-03-15 12:00');
    expect($appointment->duration_minutes)->toBe(120);

    // Vérifier les informations pour le calendrier
    $calendarData = [
        'title' => "Tattoo - {$client->first_name} {$client->last_name} - medium",
        'start' => $appointment->start_datetime,
        'end' => $appointment->end_datetime,
        'extendedProps' => [
            'booking_request_id' => $bookingRequest->id,
            'client_id' => $client->id,
            'tattoo_size' => $bookingRequest->tattoo_size,
            'body_zone' => $bookingRequest->body_zone,
        ],
    ];

    expect($calendarData['title'])->toContain('Test Client');
    expect($calendarData['title'])->toContain('medium');
    expect($calendarData['extendedProps']['booking_request_id'])->toBe($bookingRequest->id);
});

test('invalid date selection throws exception', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client 6',
        'email' => 'client6@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client 6',
        'pseudo' => 'testclient6',
        'email' => 'client6@example.com',
    ]);
    
    $tattooerUser = User::create([
        'name' => 'Test Tattooer 6',
        'email' => 'tattooer6@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $tattooerUser->id,
        'name' => 'Test Tattooer 6',
        'slug' => 'test-tattooer-6',
        'email' => 'tattooer6@example.com',
        'siret' => '12345678901239',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::DEPOSIT_PAID,
        'deposit_paid_at' => now(),
        'total_deposit_amount' => 150.00,
        'proposed_dates' => [
            ['date' => '2026-03-15', 'period' => 'morning'],
        ],
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => 'full_access',
    ]);

    $action = new ConfirmAppointmentDate();

    // Test: Date passée doit lever une exception
    expect(fn() => $action->execute($bookingRequest, '2020-01-01', 'morning'))
        ->toThrow(\InvalidArgumentException::class, 'La date doit être dans le futur');

    // Test: Période invalide doit lever une exception
    expect(fn() => $action->execute($bookingRequest, '2026-03-15', 'invalid_period'))
        ->toThrow(\InvalidArgumentException::class, 'Période invalide');
});

test('formatted proposed dates are correctly displayed', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client 7',
        'email' => 'client7@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client 7',
        'pseudo' => 'testclient7',
        'email' => 'client7@example.com',
    ]);
    
    $tattooerUser = User::create([
        'name' => 'Test Tattooer 7',
        'email' => 'tattooer7@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $tattooerUser->id,
        'name' => 'Test Tattooer 7',
        'slug' => 'test-tattooer-7',
        'email' => 'tattooer7@example.com',
        'siret' => '12345678901240',
    ]);

    $bookingRequest = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::DEPOSIT_PAID,
        'deposit_paid_at' => now(),
        'total_deposit_amount' => 150.00,
        'proposed_dates' => [
            ['date' => Carbon::now()->addDays(1)->format('Y-m-d'), 'period' => 'morning'],
            ['date' => Carbon::now()->addDays(2)->format('Y-m-d'), 'period' => 'afternoon'],
            ['date' => Carbon::now()->addDays(3)->format('Y-m-d'), 'period' => 'evening'],
        ],
    ]);

    $action = new ConfirmAppointmentDate();

    // Test: Formatage des dates proposées
    $formattedDates = $action->getFormattedProposedDates($bookingRequest);

    expect($formattedDates)->toHaveCount(3);
    expect($formattedDates[0]['period_label'])->toBe('Matin');
    expect($formattedDates[1]['period_label'])->toBe('Après-midi');
    expect($formattedDates[2]['period_label'])->toBe('Soirée');
    expect($formattedDates[0]['is_tomorrow'])->toBeTrue();
    expect($formattedDates[0]['is_today'])->toBeFalse();
});
