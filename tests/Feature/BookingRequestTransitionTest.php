<?php

use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\User;
use App\Models\Tattooer;
use App\Enums\BookingRequestStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('booking request can transition using transitionTo method', function () {
    // Créer manuellement pour éviter les problèmes de factory
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

    // Valid transition
    $result = $bookingRequest->transitionTo(BookingRequestStatus::ACCEPTED);

    expect($result)->toBeTrue();
    expect($bookingRequest->fresh()->status)->toBe(BookingRequestStatus::ACCEPTED);
});

test('booking request transitionTo returns false for invalid transition', function () {
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

    // Invalid transition (skip step)
    $result = $bookingRequest->transitionTo(BookingRequestStatus::DEPOSIT_PAID);

    expect($result)->toBeFalse();
    expect($bookingRequest->fresh()->status)->toBe(BookingRequestStatus::PENDING);
});

test('booking request cannot transition from terminal status', function () {
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
        'status' => BookingRequestStatus::COMPLETED
    ]);

    // Try to transition from terminal status
    $result = $bookingRequest->transitionTo(BookingRequestStatus::CANCELLED);

    expect($result)->toBeFalse();
    expect($bookingRequest->fresh()->status)->toBe(BookingRequestStatus::COMPLETED);
});

test('booking request status helper methods work correctly', function () {
    // Test active status
    $activeBooking = new BookingRequest(['status' => BookingRequestStatus::ACCEPTED]);

    expect($activeBooking->isStatusActive())->toBeTrue();
    expect($activeBooking->isStatusTerminal())->toBeFalse();
    expect($activeBooking->allowsDepositPayment())->toBeFalse();
    expect($activeBooking->allowsDesignSending())->toBeFalse();
    expect($activeBooking->allowsDateConfirmation())->toBeFalse();

    // Test deposit requested status
    $depositBooking = new BookingRequest(['status' => BookingRequestStatus::DEPOSIT_REQUESTED]);

    expect($depositBooking->isStatusActive())->toBeTrue();
    expect($depositBooking->allowsDepositPayment())->toBeTrue();
    expect($depositBooking->allowsDesignSending())->toBeFalse();
    expect($depositBooking->allowsDateConfirmation())->toBeFalse();

    // Test deposit paid status
    $paidBooking = new BookingRequest(['status' => BookingRequestStatus::DEPOSIT_PAID]);

    expect($paidBooking->isStatusActive())->toBeTrue();
    expect($paidBooking->allowsDepositPayment())->toBeFalse();
    expect($paidBooking->allowsDesignSending())->toBeTrue();
    expect($paidBooking->allowsDateConfirmation())->toBeTrue();

    // Test terminal status
    $terminalBooking = new BookingRequest(['status' => BookingRequestStatus::COMPLETED]);

    expect($terminalBooking->isStatusActive())->toBeFalse();
    expect($terminalBooking->isStatusTerminal())->toBeTrue();
    expect($terminalBooking->allowsDepositPayment())->toBeFalse();
    expect($terminalBooking->allowsDesignSending())->toBeFalse();
    expect($terminalBooking->allowsDateConfirmation())->toBeFalse();
});

test('booking request can get possible transitions', function () {
    $bookingRequest = new BookingRequest(['status' => BookingRequestStatus::PENDING]);

    $transitions = $bookingRequest->getPossibleTransitions();

    expect($transitions)->toBeArray();
    expect($transitions)->toHaveCount(2); // ACCEPTED and CANCELLED
    expect($transitions)->toContain(BookingRequestStatus::ACCEPTED);
    expect($transitions)->toContain(BookingRequestStatus::CANCELLED);
});
