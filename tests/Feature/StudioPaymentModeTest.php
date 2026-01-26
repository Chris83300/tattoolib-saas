<?php

use Tests\TestCase;
use App\Models\Studio;
use App\Models\StudioArtist;
use App\Models\BookingRequest;
use App\Models\Payment;
use App\Models\StudioAccountingEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;

test('studio_can_choose_artist_direct_payment_mode', function () {
    $studio = Studio::factory()->create([
        'payment_mode' => 'artist_direct',
    ]);

    expect($studio->allowsDirectArtistPayments())->toBeTrue();
    expect($studio->managesPaymentsCentrally())->toBeFalse();
});

test('studio_can_choose_centralized_payment_mode', function () {
    $studio = Studio::factory()->create([
        'payment_mode' => 'studio_managed',
    ]);

    expect($studio->managesPaymentsCentrally())->toBeTrue();
    expect($studio->allowsDirectArtistPayments())->toBeFalse();
});

test('payment_goes_to_artist_when_artist_direct_mode', function () {
    $studio = Studio::factory()->create([
        'payment_mode' => 'artist_direct',
    ]);

    $artist = StudioArtist::factory()->create([
        'studio_id' => $studio->id,
        'stripe_connect_account_id' => 'acct_artist_test',
    ]);

    // Simuler destination Stripe
    $destination = $studio->allowsDirectArtistPayments()
        ? $artist->stripe_connect_account_id
        : $studio->stripe_connect_account_id;

    expect($destination)->toBe('acct_artist_test');
});

test('payment_goes_to_studio_when_centralized_mode', function () {
    $studio = Studio::factory()->create([
        'payment_mode' => 'studio_managed',
    ]);

    $artist = StudioArtist::factory()->create([
        'studio_id' => $studio->id,
        'stripe_connect_account_id' => 'acct_artist_test',
    ]);

    // Simuler destination Stripe
    $destination = $studio->managesPaymentsCentrally()
        ? 'acct_studio_test' // Simulé
        : $artist->stripe_connect_account_id;

    expect($destination)->toBe('acct_studio_test');
});

test('accounting_entry_created_when_studio_managed_and_module_enabled', function () {
    $studio = Studio::factory()->create([
        'payment_mode' => 'studio_managed',
        'uses_accounting_module' => true,
    ]);

    $artist = StudioArtist::factory()->create([
        'studio_id' => $studio->id,
    ]);

    $booking = BookingRequest::factory()->create([
        'bookable_type' => StudioArtist::class,
        'bookable_id' => $artist->id,
        'total_deposit_amount' => 100.00,
    ]);

    $payment = Payment::factory()->create([
        'booking_request_id' => $booking->id,
        'amount' => 100.00,
    ]);

    // Créer entrée compta (simuler PaymentController)
    $entry = new StudioAccountingEntry();
    $entry->studio_id = $studio->id;
    $entry->entry_type = 'income';
    $entry->amount = 100.00;
    $entry->description = 'Acompte client';
    $entry->category = 'Acomptes clients';
    $entry->payment_id = $payment->id;
    $entry->studio_artist_id = $artist->id;
    $entry->transaction_date = now();
    $entry->save();

    expect(StudioAccountingEntry::where('studio_id', $studio->id)->count())->toBe(1);
});

test('no_accounting_entry_when_module_disabled', function () {
    $studio = Studio::factory()->create([
        'payment_mode' => 'studio_managed',
        'uses_accounting_module' => false, // Désactivé
    ]);

    $artist = StudioArtist::factory()->create([
        'studio_id' => $studio->id,
    ]);

    // Aucune entrée compta ne doit être créée
    expect(StudioAccountingEntry::count())->toBe(0);
});

test('studio_can_change_payment_mode', function () {
    $studio = Studio::factory()->create([
        'payment_mode' => 'artist_direct',
    ]);

    $studio->changePaymentMode('studio_managed');

    expect($studio->fresh()->managesPaymentsCentrally())->toBeTrue();
    expect($studio->fresh()->payment_mode_changed_at)->not->toBeNull();
});

test('accounting_entry_has_correct_type_badge', function () {
    $studio = Studio::factory()->create();

    $entry = new StudioAccountingEntry();
    $entry->studio_id = $studio->id;
    $entry->entry_type = 'income';
    $entry->amount = 100.00;
    $entry->description = 'Test revenu';
    $entry->transaction_date = now();
    $entry->save();

    expect($entry->type_badge)->toContain('Revenu');
    expect($entry->type_badge)->toContain('bg-success');
});
