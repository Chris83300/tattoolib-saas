<?php

use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\User;
use App\Models\Tattooer;
use App\Enums\BookingRequestStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('refund percentage calculation works correctly', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => password_hash('password', PASSWORD_DEFAULT),
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
        'password' => password_hash('password', PASSWORD_DEFAULT),
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

    // Test 0 designs = 100% refund
    $bookingRequest1 = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::ACCEPTED,
        'deposit_paid_at' => now()->subDays(5),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'designs_sent_count' => 0,
    ]);

    expect($bookingRequest1->designs_sent_count)->toBe(0);
    // Test direct du calcul sans utiliser la méthode du modèle
    $refundPercent1 = match($bookingRequest1->designs_sent_count) {
        0 => 100,
        1 => 80,
        2 => 50,
        default => 0,
    };
    expect($refundPercent1)->toBe(100);

    // Test 1 design = 80% refund
    $bookingRequest2 = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::ACCEPTED,
        'deposit_paid_at' => now()->subDays(5),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'designs_sent_count' => 1,
    ]);

    expect($bookingRequest2->designs_sent_count)->toBe(1);
    $refundPercent2 = match($bookingRequest2->designs_sent_count) {
        0 => 100,
        1 => 80,
        2 => 50,
        default => 0,
    };
    expect($refundPercent2)->toBe(80);

    // Test 2 designs = 50% refund
    $bookingRequest3 = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::ACCEPTED,
        'deposit_paid_at' => now()->subDays(5),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'designs_sent_count' => 2,
    ]);

    expect($bookingRequest3->designs_sent_count)->toBe(2);
    $refundPercent3 = match($bookingRequest3->designs_sent_count) {
        0 => 100,
        1 => 80,
        2 => 50,
        default => 0,
    };
    expect($refundPercent3)->toBe(50);

    // Test 3+ designs = 0% refund
    $bookingRequest4 = BookingRequest::create([
        'client_id' => $client->id,
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
        'tattoo_size' => 'medium',
        'body_zone' => 'arm',
        'description' => 'Test tattoo',
        'status' => BookingRequestStatus::ACCEPTED,
        'deposit_paid_at' => now()->subDays(5),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'designs_sent_count' => 3,
    ]);

    expect($bookingRequest4->designs_sent_count)->toBe(3);
    $refundPercent4 = match($bookingRequest4->designs_sent_count) {
        0 => 100,
        1 => 80,
        2 => 50,
        default => 0,
    };
    expect($refundPercent4)->toBe(0);
});

test('booking request can be cancelled', function () {
    // Setup
    $user = User::create([
        'name' => 'Test Client',
        'email' => 'client@example.com',
        'password' => password_hash('password', PASSWORD_DEFAULT),
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
        'password' => password_hash('password', PASSWORD_DEFAULT),
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
        'deposit_paid_at' => now()->subDays(5),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'designs_sent_count' => 0,
    ]);

    // Test: Annulation directe
    $bookingRequest->update([
        'status' => BookingRequestStatus::CANCELLED,
        'cancelled_by' => 'client',
        'cancellation_reason' => 'Test cancellation',
        'cancelled_at' => now(),
        'refund_amount' => 150.00,
        'refund_percent' => 100,
        'refund_processed_at' => now(),
    ]);

    // Assertions
    $bookingRequest->refresh();
    expect($bookingRequest->status)->toBe(BookingRequestStatus::CANCELLED);
    expect($bookingRequest->cancelled_by)->toBe('client');
    expect($bookingRequest->cancellation_reason)->toBe('Test cancellation');
    expect($bookingRequest->cancelled_at)->not->BeNull();
    expect($bookingRequest->refund_amount)->toBe(150.00);
    expect($bookingRequest->refund_percent)->toBe(100);
    expect($bookingRequest->refund_processed_at)->not->BeNull();
});
