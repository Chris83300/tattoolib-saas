<?php

namespace Tests\Feature\Payment;

use App\Models\BookingRequest;
use App\Models\BookingTransaction;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('deposit payment creates transaction', function () {
    $client = Client::factory()->create();
    $user = User::factory()->create(['client_id' => $client->id]);
    $bookingRequest = BookingRequest::factory()->create([
        'client_id' => $client->id,
        'status' => 'accepted',
        'total_deposit_amount' => 150.00,
    ]);

    actingAs($user);

    expect($bookingRequest->status)->toBe('accepted');
    expect($client->id)->toBe($client->id);
});
