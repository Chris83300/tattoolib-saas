<?php

use App\Models\User;
use App\Models\BookingRequest;
use App\Models\Tattooer;
use App\Enums\BookingRequestStatus;

test('balance remaining calculation works correctly', function () {
    $booking = BookingRequest::factory()->create([
        'bookable_type' => 'App\Models\Tattooer',
        'status' => BookingRequestStatus::COMPLETED,
        'total_price' => 300,
        'total_deposit_amount' => 100,
        'balance_amount' => 50, // déjà payé
    ]);

    expect($booking->balance_remaining)->toBe(150.0); // 300 - 100 - 50
});

test('balance remaining returns 0 when no amounts set', function () {
    $booking = BookingRequest::factory()->create([
        'bookable_type' => 'App\Models\Tattooer',
        'status' => BookingRequestStatus::COMPLETED,
        'total_price' => null,
        'total_deposit_amount' => null,
    ]);

    expect($booking->balance_remaining)->toBe(0.0);
});

test('balance remaining cannot be negative', function () {
    $booking = BookingRequest::factory()->create([
        'bookable_type' => 'App\Models\Tattooer',
        'status' => BookingRequestStatus::COMPLETED,
        'total_price' => 200,
        'total_deposit_amount' => 100,
        'balance_amount' => 150, // plus que le solde normal
    ]);

    expect($booking->balance_remaining)->toBe(0.0); // max(0, 200-100-150)
});

test('tattooer can confirm offline payment', function () {
    $tattooerUser = User::factory()->tattooer()->create();
    $booking = BookingRequest::factory()->create([
        'bookable_type' => 'App\Models\Tattooer',
        'status' => BookingRequestStatus::COMPLETED,
        'total_price' => 300,
        'total_deposit_amount' => 100,
    ]);

    $this->actingAs($tattooerUser)
        ->post(route('tattooer.balance-payment.confirm-offline', $booking), [
            'payment_method' => 'cash',
            'amount' => 200,
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect($booking->fresh()->status)->toBe(BookingRequestStatus::FULLY_COMPLETED);
    expect($booking->fresh()->balance_amount)->toBe('200.00');
    expect($booking->fresh()->balance_payment_method)->toBe('cash');
});
