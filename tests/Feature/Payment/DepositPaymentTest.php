<?php

namespace Tests\Feature\Payment;

use App\Models\BookingRequest;
use App\Models\BookingTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('deposit payment basic functionality', function () {
    $user = User::factory()->create();
    $bookingRequest = BookingRequest::factory()->create([
        'status' => 'accepted',
        'total_deposit_amount' => 150.00,
    ]);

    $this->actingAs($user);

    expect($bookingRequest->status->value)->toBe('accepted');
    expect($bookingRequest->total_deposit_amount)->toBe('150.00');
});

test('booking transaction creation test', function () {
    $user = User::factory()->create();
    $bookingRequest = BookingRequest::factory()->create([
        'status' => 'accepted',
        'total_deposit_amount' => 200.00,
    ]);

    $this->actingAs($user);

    // Test simple de création de transaction
    $transaction = new BookingTransaction([
        'booking_request_id' => $bookingRequest->id,
        'user_id' => $user->id,
        'type' => 'deposit',
        'amount' => 200.00,
        'status' => 'completed',
    ]);
    $transaction->save();

    expect($transaction)->not->toBeNull();
    expect($transaction->amount)->toBe('200.00');
    expect($transaction->type)->toBe('deposit');
});
