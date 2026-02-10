<?php

use App\Models\Conversation;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\User;
use App\Models\Tattooer;
use App\Models\Appointment;
use App\Models\Message;
use App\Policies\ConversationPolicy;
use App\Services\TrackDesignDelivery;
use App\Enums\ConversationStatus;
use App\Enums\BookingRequestStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('client cannot send images before deposit payment', function () {
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
        'status' => BookingRequestStatus::ACCEPTED,
        'deposit_deadline_hours' => 72,
        'included_designs' => 2,
        'modifications_per_design' => 3,
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => ConversationStatus::ACTIVE,
        'deposit_deadline_at' => now()->addHours(72),
    ]);

    $policy = new ConversationPolicy();

    // Test: Client ne peut pas envoyer d'images en statut ACTIVE
    expect($policy->sendImage($user, $conversation))->toBeFalse();
});

test('client can send images after appointment for follow-up', function () {
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
        'status' => BookingRequestStatus::DATE_CONFIRMED,
        'deposit_paid_at' => now(),
        'included_designs' => 2,
        'modifications_per_design' => 3,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subDays(1), // RDV terminé hier
        'end_datetime' => now()->subDays(1)->addHours(2),
        'duration_minutes' => 120,
        'status' => 'completed',
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => ConversationStatus::FULL_ACCESS,
        'appointment_completed_at' => $appointment->end_datetime,
    ]);

    $policy = new ConversationPolicy();

    // Test: Client peut envoyer des images après RDV en FULL_ACCESS
    expect($policy->sendImage($user, $conversation))->toBeTrue();
});

test('client limited to 4 post-appointment images', function () {
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
        'status' => BookingRequestStatus::DATE_CONFIRMED,
        'deposit_paid_at' => now(),
        'included_designs' => 2,
        'modifications_per_design' => 3,
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subDays(1),
        'end_datetime' => now()->subDays(1)->addHours(2),
        'duration_minutes' => 120,
        'status' => 'completed',
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => ConversationStatus::FULL_ACCESS,
        'appointment_completed_at' => $appointment->end_datetime,
    ]);

    // Créer 4 messages avec images post-RDV
    for ($i = 1; $i <= 4; $i++) {
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'sender_type' => 'client',
            'content' => "Image post-RDV #{$i}",
            'attachments' => json_encode(['image.jpg']),
            'created_at' => $appointment->end_datetime->addMinutes($i * 10),
        ]);
    }

    $policy = new ConversationPolicy();

    // Test: Client ne peut plus envoyer d'images (limite de 4 atteinte)
    expect($policy->sendImage($user, $conversation))->toBeFalse();
});

test('tattooer image increments design count', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Tattooer 4',
        'email' => 'tattooer4@example.com',
        'password' => Hash::make('password'),
        'role' => 'tattooer',
        'is_active' => true,
    ]);
    
    $clientUser = User::create([
        'name' => 'Test Client 4',
        'email' => 'client4@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $clientUser->id,
        'first_name' => 'Test',
        'last_name' => 'Client 4',
        'pseudo' => 'testclient4',
        'email' => 'client4@example.com',
    ]);
    
    $tattooer = Tattooer::create([
        'user_id' => $user->id,
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
        'included_designs' => 3,
        'modifications_per_design' => 2,
        'designs_sent_count' => 0,
        'current_design_modifications_count' => 0,
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => ConversationStatus::FULL_ACCESS,
    ]);

    $message = Message::create([
        'conversation_id' => $conversation->id,
        'sender_id' => $user->id,
        'sender_type' => 'tattooer',
        'content' => 'Voici le premier design',
        'attachments' => json_encode(['design.jpg']),
    ]);

    $service = new TrackDesignDelivery();
    $service->handleTattooerImage($conversation, $message);

    // Vérifier que le compteur a été incrémenté
    $bookingRequest->refresh();
    expect($bookingRequest->designs_sent_count)->toBe(1);
    expect($bookingRequest->current_design_modifications_count)->toBe(0);
});

test('modification request increments modification count', function () {
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
        'status' => BookingRequestStatus::DEPOSIT_PAID,
        'deposit_paid_at' => now(),
        'included_designs' => 2,
        'modifications_per_design' => 3,
        'designs_sent_count' => 1,
        'current_design_modifications_count' => 0,
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => ConversationStatus::FULL_ACCESS,
    ]);

    $message = Message::create([
        'conversation_id' => $conversation->id,
        'sender_id' => $user->id,
        'sender_type' => 'client',
        'content' => 'Pourriez-vous modifier la couleur ?',
    ]);

    $service = new TrackDesignDelivery();
    $service->handleModificationRequest($conversation, $message);

    // Vérifier que le compteur de modifications a été incrémenté
    $bookingRequest->refresh();
    expect($bookingRequest->current_design_modifications_count)->toBe(1);
});

test('chat closes automatically when deposit deadline expires', function () {
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
        'status' => BookingRequestStatus::ACCEPTED,
        'deposit_deadline_hours' => 72,
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => ConversationStatus::ACTIVE,
        'deposit_deadline_at' => now()->subHours(1), // Expiré il y a 1h
    ]);

    // Simuler le command de fermeture
    $command = new \App\Console\Commands\CloseExpiredConversations();
    $command->handle();

    // Vérifier que la conversation a été fermée
    $conversation->refresh();
    expect($conversation->status)->toBe(ConversationStatus::CLOSED);

    // Vérifier que la booking request est expirée
    $bookingRequest->refresh();
    expect($bookingRequest->status)->toBe(BookingRequestStatus::EXPIRED);
    expect($bookingRequest->expired_at)->not->toBeNull();
});

test('chat closes 30 days after appointment', function () {
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
        'status' => BookingRequestStatus::COMPLETED,
        'deposit_paid_at' => now(),
    ]);

    $appointment = Appointment::create([
        'booking_request_id' => $bookingRequest->id,
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'start_datetime' => now()->subDays(35), // RDV terminé il y a 35 jours
        'end_datetime' => now()->subDays(35)->addHours(2),
        'duration_minutes' => 120,
        'status' => 'completed',
    ]);

    $conversation = Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => ConversationStatus::FULL_ACCESS,
        'appointment_completed_at' => $appointment->end_datetime,
    ]);

    // Simuler le command de fermeture
    $command = new \App\Console\Commands\CloseExpiredConversations();
    $command->handle();

    // Vérifier que la conversation est en statut CLOSING (préavis de 7 jours)
    $conversation->refresh();
    expect($conversation->status)->toBe(ConversationStatus::CLOSING);
    expect($conversation->expires_at)->not->toBeNull();
});
