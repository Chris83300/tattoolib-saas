<?php

use App\Actions\AcceptBookingRequest;
use App\Actions\RejectBookingRequest;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\User;
use App\Models\Tattooer;
use App\Models\Conversation;
use App\Models\Message;
use App\Enums\BookingRequestStatus;
use App\Enums\ConversationStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('tattooer can accept booking request with conditions', function () {
    // Setup
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client',
        'pseudo' => 'testclient',
        'email' => 'test@example.com',
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
        'status' => BookingRequestStatus::PENDING
    ]);

    // Acceptance data
    $acceptanceData = [
        'price_estimate_min' => 300.00,
        'price_estimate_max' => 500.00,
        'deposit_amount' => 150.00,
        'deposit_deadline_hours' => 72,
        'included_designs' => 2,
        'modifications_per_design' => 3,
        'proposed_dates' => [
            ['date' => '2026-03-15', 'period' => 'morning'],
            ['date' => '2026-03-16', 'period' => 'afternoon'],
            ['date' => '2026-03-17', 'period' => 'evening'],
        ],
        'message' => 'Je suis ravi de réaliser votre tatouage !'
    ];

    // Execute action
    $action = new AcceptBookingRequest();
    $action->execute($bookingRequest, $acceptanceData);

    // Assertions
    $bookingRequest->refresh();
    
    expect($bookingRequest->status)->toBe(BookingRequestStatus::ACCEPTED);
    expect($bookingRequest->accepted_at)->not->toBeNull();
    expect($bookingRequest->price_estimate_min)->toBe(300.00);
    expect($bookingRequest->price_estimate_max)->toBe(500.00);
    expect($bookingRequest->deposit_amount)->toBe(150.00);
    expect($bookingRequest->deposit_deadline_hours)->toBe(72);
    expect($bookingRequest->included_designs)->toBe(2);
    expect($bookingRequest->modifications_per_design)->toBe(3);
    expect($bookingRequest->proposed_dates)->toBe($acceptanceData['proposed_dates']);
    expect($bookingRequest->tattooer_acceptance_message)->toBe('Je suis ravi de réaliser votre tatouage !');
});

test('accepting creates conversation with correct deadline', function () {
    // Setup
    $user = User::create([
        'name' => 'Test User 2',
        'email' => 'test2@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client 2',
        'pseudo' => 'testclient2',
        'email' => 'test2@example.com',
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
        'status' => BookingRequestStatus::PENDING
    ]);

    // Acceptance data with 48h deadline
    $acceptanceData = [
        'price_estimate_min' => 400.00,
        'price_estimate_max' => 600.00,
        'deposit_amount' => 200.00,
        'deposit_deadline_hours' => 48,
        'included_designs' => 1,
        'modifications_per_design' => 2,
        'proposed_dates' => [
            ['date' => '2026-03-20', 'period' => 'morning'],
        ],
        'message' => null
    ];

    // Execute action
    $action = new AcceptBookingRequest();
    $action->execute($bookingRequest, $acceptanceData);

    // Check conversation
    $conversation = $bookingRequest->conversation;
    expect($conversation)->not->toBeNull();
    expect($conversation->status)->toBe(ConversationStatus::ACTIVE);
    expect($conversation->deposit_deadline_at)->not->toBeNull();
    expect($conversation->expires_at)->toEqual($conversation->deposit_deadline_at);
    
    // Check deadline synchronization
    $bookingRequest->refresh();
    expect($bookingRequest->deposit_deadline)->toEqual($conversation->deposit_deadline_at);
    expect($bookingRequest->client_payment_deadline)->toEqual($conversation->deposit_deadline_at);
});

test('accepting creates system message in conversation', function () {
    // Setup
    $user = User::create([
        'name' => 'Test User 3',
        'email' => 'test3@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client 3',
        'pseudo' => 'testclient3',
        'email' => 'test3@example.com',
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
        'status' => BookingRequestStatus::PENDING
    ]);

    $acceptanceData = [
        'price_estimate_min' => 250.00,
        'price_estimate_max' => 350.00,
        'deposit_amount' => 100.00,
        'deposit_deadline_hours' => 24,
        'included_designs' => 1,
        'modifications_per_design' => 1,
        'proposed_dates' => [
            ['date' => '2026-03-25', 'period' => 'afternoon'],
        ],
        'message' => 'Super projet !'
    ];

    // Execute action
    $action = new AcceptBookingRequest();
    $action->execute($bookingRequest, $acceptanceData);

    // Check system message
    $conversation = $bookingRequest->conversation;
    $messages = $conversation->messages;
    expect($messages)->toHaveCount(1);
    
    $systemMessage = $messages->first();
    expect($systemMessage->sender_type)->toBe('system');
    expect($systemMessage->content)->toContain('✅ Demande acceptée !');
    expect($systemMessage->content)->toContain('250.00€ - 350.00€');
    expect($systemMessage->content)->toContain('100.00€');
    expect($systemMessage->content)->toContain('24h');
    expect($systemMessage->content)->toContain('Super projet !');
});

test('tattooer can reject with optional reason', function () {
    // Setup
    $user = User::create([
        'name' => 'Test User 4',
        'email' => 'test4@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client 4',
        'pseudo' => 'testclient4',
        'email' => 'test4@example.com',
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
        'status' => BookingRequestStatus::PENDING
    ]);

    // Execute rejection with reason
    $action = new RejectBookingRequest();
    $action->execute($bookingRequest, 'Désolé, je suis trop occupé pour cette période');

    // Assertions
    $bookingRequest->refresh();
    
    expect($bookingRequest->status)->toBe(BookingRequestStatus::CANCELLED);
    expect($bookingRequest->cancelled_at)->not->toBeNull();
    expect($bookingRequest->cancelled_by)->toBe('tattooer');
    expect($bookingRequest->cancellation_reason)->toBe('Désolé, je suis trop occupé pour cette période');
});

test('tattooer can reject without reason', function () {
    // Setup
    $user = User::create([
        'name' => 'Test User 5',
        'email' => 'test5@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client 5',
        'pseudo' => 'testclient5',
        'email' => 'test5@example.com',
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
        'status' => BookingRequestStatus::PENDING
    ]);

    // Execute rejection without reason
    $action = new RejectBookingRequest();
    $action->execute($bookingRequest);

    // Assertions
    $bookingRequest->refresh();
    
    expect($bookingRequest->status)->toBe(BookingRequestStatus::CANCELLED);
    expect($bookingRequest->cancelled_at)->not->toBeNull();
    expect($bookingRequest->cancelled_by)->toBe('tattooer');
    expect($bookingRequest->cancellation_reason)->toBe('Demande refusée par le tatoueur');
});

test('proposed dates are stored as JSON array', function () {
    // Setup
    $user = User::create([
        'name' => 'Test User 6',
        'email' => 'test6@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client 6',
        'pseudo' => 'testclient6',
        'email' => 'test6@example.com',
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
        'status' => BookingRequestStatus::PENDING
    ]);

    $proposedDates = [
        ['date' => '2026-04-01', 'period' => 'morning'],
        ['date' => '2026-04-02', 'period' => 'afternoon'],
        ['date' => '2026-04-03', 'period' => 'evening'],
        ['date' => '2026-04-04', 'period' => 'anytime'],
    ];

    $acceptanceData = [
        'price_estimate_min' => 200.00,
        'price_estimate_max' => 400.00,
        'deposit_amount' => 100.00,
        'deposit_deadline_hours' => 72,
        'included_designs' => 2,
        'modifications_per_design' => 2,
        'proposed_dates' => $proposedDates,
        'message' => null
    ];

    // Execute action
    $action = new AcceptBookingRequest();
    $action->execute($bookingRequest, $acceptanceData);

    // Check JSON storage
    $bookingRequest->refresh();
    expect($bookingRequest->proposed_dates)->toBeArray();
    expect($bookingRequest->proposed_dates)->toHaveCount(4);
    expect($bookingRequest->proposed_dates[0]['date'])->toBe('2026-04-01');
    expect($bookingRequest->proposed_dates[0]['period'])->toBe('morning');
    expect($bookingRequest->proposed_dates[3]['period'])->toBe('anytime');
});

test('acceptance validation throws exception for invalid data', function () {
    // Setup
    $user = User::create([
        'name' => 'Test User 7',
        'email' => 'test7@example.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_active' => true,
    ]);
    
    $client = Client::create([
        'user_id' => $user->id,
        'first_name' => 'Test',
        'last_name' => 'Client 7',
        'pseudo' => 'testclient7',
        'email' => 'test7@example.com',
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
        'status' => BookingRequestStatus::PENDING
    ]);

    // Test invalid price range
    $invalidData = [
        'price_estimate_min' => 500.00,
        'price_estimate_max' => 300.00, // Invalid: min >= max
        'deposit_amount' => 100.00,
        'deposit_deadline_hours' => 72,
        'included_designs' => 1,
        'modifications_per_design' => 1,
        'proposed_dates' => [
            ['date' => '2026-03-15', 'period' => 'morning'],
        ],
        'message' => null
    ];

    $action = new AcceptBookingRequest();
    
    expect(fn() => $action->execute($bookingRequest, $invalidData))
        ->toThrow(\InvalidArgumentException::class, 'Price estimate min must be less than max');
});
