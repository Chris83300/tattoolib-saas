<?php

use App\Notifications\Client\BookingRequestAcceptedNotification;
use App\Notifications\Client\DepositExpiredNotification;
use App\Notifications\Client\DesignReceivedNotification;
use App\Notifications\Client\AppointmentReminderNotification;
use App\Notifications\Client\PostTattooCareNotification;
use App\Notifications\Tattooer\NewBookingRequestNotification;
use App\Notifications\Tattooer\DepositPaidNotification;
use App\Notifications\Tattooer\DateChosenNotification;
use App\Notifications\Tattooer\NoDesignSentNotification;
use App\Notifications\Tattooer\NoShowNotification;
use App\Models\BookingRequest;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use App\Models\Tattooer;
use App\Enums\BookingRequestStatus;
use App\Enums\AppointmentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

uses(RefreshDatabase::class);

test('client receives reminder 7 days before appointment', function () {
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
        'status' => BookingRequestStatus::DATE_CONFIRMED,
        'deposit_paid_at' => now(),
        'confirmed_date' => now()->addDays(7)->format('Y-m-d'),
        'confirmed_period' => 'morning',
        'appointment_datetime' => now()->addDays(7)->setTime(10, 0),
        'appointment_duration_minutes' => 120,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->addDays(7)->setTime(10, 0),
        'end_datetime' => now()->addDays(7)->setTime(12, 0),
        'duration_minutes' => 120,
        'status' => AppointmentStatus::SCHEDULED,
    ]);

    // Test: Envoyer le rappel J-7
    $notification = new AppointmentReminderNotification($appointment, '7_days');
    
    // Vérifier le contenu de la notification
    $arrayData = $notification->toArray($user);
    
    expect($arrayData['title'])->toBe('Rendez-vous dans 7 jours');
    expect($arrayData['type'])->toBe('info');
    expect($arrayData['icon'])->toBe('calendar');
    expect($arrayData['appointment_id'])->toBe($appointment->id);
    expect($arrayData['booking_request_id'])->toBe($bookingRequest->id);
    expect($arrayData['reminder_type'])->toBe('7_days');
    
    // Vérifier le sujet de l'email
    $mailMessage = $notification->toMail($user);
    expect($mailMessage->subject)->toBe('📅 Rappel : Votre rendez-vous dans 7 jours');
});

test('tattooer alerted when no design sent 7 days before', function () {
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
        'email' => 'tattooerer@example.com',
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
        'confirmed_date' => now()->addDays(7)->format('Y-m-d'),
        'confirmed_period' => 'morning',
        'appointment_datetime' => now()->addDays(7)->setTime(10, 0),
        'appointment_duration_minutes' => 120,
        'included_designs' => 2,
        'designs_sent_count' => 0,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->addDays(7)->setTime(10, 0),
        'end_datetime' => now()->addDays(7)->setTime(12, 0),
        'duration_minutes' => 120,
        'status' => AppointmentStatus::SCHEDULED,
    ]);

    // Test: Envoyer l'alerte design manquant
    $notification = new NoDesignSentNotification($appointment, 7);
    
    // Vérifier le contenu de la notification
    $arrayData = $notification->toArray($user);
    
    expect($arrayData['title'])->toBe('Alerte : Aucun design envoyé');
    expect($arrayData['type'])->toBe('warning');
    expect($arrayData['icon'])->toBe('exclamation-triangle');
    expect($arrayData['days_before'])->toBe(7);
    expect($arrayData['designs_sent'])->toBe(0);
    expect($arrayData['designs_included'])->toBe(2);
    
    // Vérifier le sujet de l'email
    $mailMessage = $notification->toMail($user);
    expect($mailMessage->subject)->toContain('Aucun design envoyé');
    expect($mailMessage->subject)->toContain('7 jours');
});

test('post-tattoo care notification sent 2 hours after', function () {
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
        'status' => BookingRequestStatus::COMPLETED,
        'deposit_paid_at' => now(),
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
        'status' => AppointmentStatus::COMPLETED,
    ]);

    // Test: Envoyer la notification 2h après
    $notification = new PostTattooCareNotification($appointment, '2_hours');
    
    // Vérifier le contenu de la notification
    $arrayData = $notification->toArray($user);
    
    expect($arrayData['title'])->toBe('Soins post-tatouage (2h)');
    expect($arrayData['type'])->toBe('info');
    expect($arrayData['icon'])->toBe('heart');
    expect($arrayData['care_type'])->toBe('2_hours');
    expect($arrayData['appointment_id'])->toBe($appointment->id);
    
    // Vérifier le sujet de l'email
    $mailMessage = $notification->toMail($user);
    expect($mailMessage->subject)->toBe('🩹 Instructions de soins post-tatouage (2h après)');
});

test('client receives design received notification', function () {
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
        'included_designs' => 2,
        'designs_sent_count' => 1,
    ]);

    $conversation = \App\Models\Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => 'full_access',
    ]);

    // Simuler l'envoi de la notification
    $action = new \App\Console\Commands\SendPostTattooNotifications();
    $action->sendDesignReceivedNotification($bookingRequest, 1);

    // Vérifier que la notification a été créée
    $notifications = $user->notifications()->where('type', 'App\Notifications\Client\DesignReceivedNotification')->get();
    expect($notifications)->toHaveCount(1);
    
    $notification = $notifications->first();
    expect($notification->data['title'])->toBe('Nouveau design reçu');
    expect($notification->data['design_number'])->toBe(1);
});

test('tattooer receives new booking request notification', function () {
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
        'deposit_paid_at' => now(),
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
        'status' => BookingRequestStatus::PENDING,
    ]);

    // Test: Envoyer la notification
    $notification = new NewBookingRequestNotification($bookingRequest);
    
    // Vérifier le contenu de la notification
    $arrayData = $notification->toArray($user);
    
    expect($arrayData['title'])->toBe('Nouvelle demande reçue');
    expect($arrayData['type'])->toBe('info');
    expect($arrayData['icon'])->toBe('plus-circle');
    expect($arrayData['booking_request_id'])->toBe($bookingRequest->id);
    expect($arrayData['client_name'])->toBe('Test Client');
    expect($arrayData['tattoo_size'])->toBe('medium');
    expect($arrayData['body_zone'])->toBe('arm');
    
    // Vérifier le sujet de l'email
    $mailMessage = $notification->toMail($user);
    expect($mailMessage->subject)->toBe('🆕 Nouvelle demande de tatouage reçue !');
});

test('tattooer receives deposit paid notification', function () {
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
        'status' => BookingRequestStatus::ACCEPTED,
        'total_deposit_amount' => 150.00,
    ]);

    // Simuler le paiement
    $bookingRequest->update([
        'status' => BookingRequestStatus::DEPOSIT_PAID,
        'deposit_paid_at' => now(),
    ]);

    // Test: Envoyer la notification
    $notification = new DepositPaidNotification($bookingRequest);
    
    // Vérifier le contenu de la notification
    $arrayData = $notification->toArray($user);
    
    expect($arrayData['title'])->toBe('Acompte payé');
    expect($arrayData['type'])->toBe('success');
    expect($arrayData['icon'])->toBe('check-circle');
    expect($arrayData['deposit_amount'])->toBe(150.0);
    expect($arrayData['deposit_paid_at'])->not->toBeNull();
    
    // Vérifier le sujet de l'email
    $mailMessage = $notification->toMail($user);
    expect($mailMessage->subject)->toBe('💰 Acompte payé - Demande de tatouage confirmée !');
});

test('client receives date chosen notification', function () {
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
        'proposed_dates' => [
            ['date' => '2026-03-15', 'period' => 'morning'],
            ['date' => '2026-03-16', 'period' => 'afternoon'],
            ['date' => '2026-03-17', 'period' => 'evening'],
        ],
    ]);

    // Simuler la sélection de date par le client
    $bookingRequest->update([
        'confirmed_date' => '2026-03-15',
        'confirmed_period' => 'morning',
        'appointment_datetime' => '2026-03-15 10:00:00',
        'appointment_duration_minutes' => 120,
    ]);

    // Test: Envoyer la notification au tattooer
    $notification = new DateChosenNotification($bookingRequest);
    
    // Vérifier le contenu de la notification
    $arrayData = $notification->toArray($tattooerUser);
    
    expect($arrayData['title'])->toBe('Date choisie par le client');
    expect($arrayData['type'])->toBe('info');
    expect($arrayData['icon'])->toBe('calendar-check');
    expect($arrayData['confirmed_date'])->toBe('2026-03-15');
    expect($arrayData['confirmed_period'])->toBe('morning');
    expect($arrayData['appointment_datetime'])->toBe('2026-03-15 10:00:00');
});

test('no-show notification sent 1 hour after appointment', function () {
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
        'confirmed_date' => '2026-03-15',
        'confirmed_period' => 'morning',
        'appointment_datetime' => '2026-03-15 10:00:00',
        'appointment_duration_minutes' => 120,
        'total_price' => 300.00,
        'deposit_amount' => 150.00,
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

    // Simuler le no-show (1h après la fin)
    $appointment->update([
        'status' => AppointmentStatus::NO_SHOW,
    ]);

    // Test: Envoyer la notification de no-show
    $notification = new NoShowNotification($appointment);
    
    // Vérifier le contenu de la notification
    $arrayData = $notification->toArray($user);
    
    expect($arrayData['title'])->toBe('Client absent');
    expect($arrayData['type'])->toBe('error');
    expect($arrayData['icon'])->toBe('user-x');
    expect($arrayData['deposit_amount'])->toBe(150.0);
    expect($arrayData['total_price'])->toBe(300.00);
    
    // Vérifier le sujet de l'email
    $mailMessage = $notification->toMail($user);
    expect($mailMessage->subject)->toBe('⚠️ Client absent au rendez-vous');
});
