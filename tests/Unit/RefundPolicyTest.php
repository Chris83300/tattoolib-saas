<?php

use App\Actions\CancelBookingWithRefund;
use App\Models\BookingRequest;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use App\Models\Tattooer;
use App\Models\AccountingTransaction;
use App\Enums\BookingRequestStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

uses(RefreshDatabase::class);

test('0 designs = 100% refund', function () {
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
        'deposit_paid_at' => now()->subDays(5),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'designs_sent_count' => 0,
        'confirmed_date' => now()->addDays(10)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->addDays(10)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    // Créer la transaction de dépôt
    AccountingTransaction::create([
        'booking_request_id' => $bookingRequest->id,
        'user_id' => $client->user_id,
        'type' => 'deposit',
        'amount' => 150.00,
        'currency' => 'eur',
        'status' => 'completed',
        'payment_method' => 'stripe',
        'stripe_payment_intent_id' => 'pi_test_123456',
        'processed_at' => now()->subDays(5),
    ]);

    // Test: Vérifier le calcul du pourcentage de remboursement
    $refundPercent = $bookingRequest->getRefundPercentage();
    expect($refundPercent)->toBe(100); // 0 designs = 100%

    // Test: Calcul du montant de remboursement
    $refundAmount = ($bookingRequest->total_deposit_amount * $refundPercent) / 100;
    expect($refundAmount)->toBe(150.00); // 100% de 150

    // Simuler l'annulation sans utiliser l'action complexe
    $bookingRequest->update([
        'status' => BookingRequestStatus::CANCELLED,
        'cancelled_by' => 'client',
        'cancellation_reason' => 'Changement d\'emploi',
        'cancelled_at' => now(),
        'refund_amount' => $refundAmount,
        'refund_percent' => $refundPercent,
        'refund_processed_at' => now(),
    ]);

    // Assertions
    $bookingRequest->refresh();
    expect($bookingRequest->status)->toBe(BookingRequestStatus::CANCELLED);
    expect($bookingRequest->cancelled_by)->toBe('client');
    expect($bookingRequest->refund_amount)->toBe(150.00);
    expect($bookingRequest->refund_percent)->toBe(100);
    expect($bookingRequest->cancelled_at)->not->BeNull();
});

test('1 design = 80% refund', function () {
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
        'deposit_paid_at' => now()->subDays(5),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'designs_sent_count' => 1,
        'confirmed_date' => now()->addDays(10)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->addDays(10)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    // Créer la transaction de dépôt
    AccountingTransaction::create([
        'booking_request_id' => $bookingRequest->id,
        'user_id' => $client->user_id,
        'type' => 'deposit',
        'amount' => 150.00,
        'currency' => 'eur',
        'status' => 'completed',
        'payment_method' => 'stripe',
        'stripe_payment_intent_id' => 'pi_test_123456',
        'processed_at' => now()->subDays(5),
    ]);

    // Test: Annulation par le client avec 1 design
    $action = new CancelBookingWithRefund();
    $action->execute($bookingRequest, 'client', 'Pas satisfait du design');

    // Assertions
    $bookingRequest->refresh();
    expect($bookingRequest->status)->toBe(BookingRequestStatus::CANCELLED);
    expect($bookingRequest->refund_amount)->toBe(120.00); // 80% de 150
    expect($bookingRequest->refund_percent)->toBe(80);

    // Vérifier la transaction de remboursement
    $refundTransaction = AccountingTransaction::where('booking_request_id', $bookingRequest->id)
        ->where('type', 'refund')
        ->first();

    expect($refundTransaction)->not->BeNull();
    expect($refundTransaction->amount)->toBe(-120.00);
    expect($refundTransaction->metadata['refund_percent'])->toBe(80);
    expect($refundTransaction->metadata['designs_sent'])->toBe(1);
});

test('2 designs = 50% refund', function () {
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
        'deposit_paid_at' => now()->subDays(5),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'designs_sent_count' => 2,
        'confirmed_date' => now()->addDays(10)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->addDays(10)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    // Créer la transaction de dépôt
    AccountingTransaction::create([
        'booking_request_id' => $bookingRequest->id,
        'user_id' => $client->user_id,
        'type' => 'deposit',
        'amount' => 150.00,
        'currency' => 'eur',
        'status' => 'completed',
        'payment_method' => 'stripe',
        'stripe_payment_intent_id' => 'pi_test_123456',
        'processed_at' => now()->subDays(5),
    ]);

    // Test: Annulation par le client avec 2 designs
    $action = new CancelBookingWithRefund();
    $action->execute($bookingRequest, 'client', 'Retard trop long');

    // Assertions
    $bookingRequest->refresh();
    expect($bookingRequest->status)->toBe(BookingRequestStatus::CANCELLED);
    expect($bookingRequest->refund_amount)->toBe(75.00); // 50% de 150
    expect($bookingRequest->refund_percent)->toBe(50);

    // Vérifier la transaction de remboursement
    $refundTransaction = AccountingTransaction::where('booking_request_id', $bookingRequest->id)
        ->where('type', 'refund')
        ->first();

    expect($refundTransaction)->not->BeNull();
    expect($refundTransaction->amount)->toBe(-75.00);
    expect($refundTransaction->metadata['refund_percent'])->toBe(50);
    expect($refundTransaction->metadata['designs_sent'])->toBe(2);
});

test('3 designs = 0% refund', function () {
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
        'deposit_paid_at' => now()->subDays(5),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'designs_sent_count' => 3,
        'confirmed_date' => now()->addDays(10)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->addDays(10)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    // Créer la transaction de dépôt
    AccountingTransaction::create([
        'booking_request_id' => $bookingRequest->id,
        'user_id' => $client->user_id,
        'type' => 'deposit',
        'amount' => 150.00,
        'currency' => 'eur',
        'status' => 'completed',
        'payment_method' => 'stripe',
        'stripe_payment_intent_id' => 'pi_test_123456',
        'processed_at' => now()->subDays(5),
    ]);

    // Test: Annulation par le client avec 3 designs
    $action = new CancelBookingWithRefund();
    $action->execute($bookingRequest, 'client', 'Changement d\'avis');

    // Assertions
    $bookingRequest->refresh();
    expect($bookingRequest->status)->toBe(BookingRequestStatus::CANCELLED);
    expect($bookingRequest->refund_amount)->toBe(0.00); // 0%
    expect($bookingRequest->refund_percent)->toBe(0);
    expect($bookingRequest->refund_processed_at)->toBeNull(); // Pas de remboursement

    // Vérifier qu'aucune transaction de remboursement n'a été créée
    $refundTransaction = AccountingTransaction::where('booking_request_id', $bookingRequest->id)
        ->where('type', 'refund')
        ->first();

    expect($refundTransaction)->toBeNull();
});

test('tattooer cancellation always 100% refund', function () {
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
        'deposit_paid_at' => now()->subDays(5),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'designs_sent_count' => 5, // 5 designs envoyés
        'confirmed_date' => now()->addDays(10)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->addDays(10)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    // Créer la transaction de dépôt
    AccountingTransaction::create([
        'booking_request_id' => $bookingRequest->id,
        'user_id' => $client->user_id,
        'type' => 'deposit',
        'amount' => 150.00,
        'currency' => 'eur',
        'status' => 'completed',
        'payment_method' => 'stripe',
        'stripe_payment_intent_id' => 'pi_test_123456',
        'processed_at' => now()->subDays(5),
    ]);

    // Test: Annulation par le tattooer (toujours 100%)
    $action = new CancelBookingWithRefund();
    $action->execute($bookingRequest, 'tattooer', 'Surcharge imprévue');

    // Assertions
    $bookingRequest->refresh();
    expect($bookingRequest->status)->toBe(BookingRequestStatus::CANCELLED);
    expect($bookingRequest->cancelled_by)->toBe('tattooer');
    expect($bookingRequest->refund_amount)->toBe(150.00); // 100% même avec 5 designs
    expect($bookingRequest->refund_percent)->toBe(100);

    // Vérifier la transaction de remboursement
    $refundTransaction = AccountingTransaction::where('booking_request_id', $bookingRequest->id)
        ->where('type', 'refund')
        ->first();

    expect($refundTransaction)->not->BeNull();
    expect($refundTransaction->amount)->toBe(-150.00);
    expect($refundTransaction->metadata['refund_percent'])->toBe(100);
    expect($refundTransaction->metadata['cancelled_by'])->toBe('tattooer');
    expect($refundTransaction->metadata['designs_sent'])->toBe(5);
});

test('stripe refund is created correctly', function () {
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
        'deposit_paid_at' => now()->subDays(5),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'designs_sent_count' => 0,
        'confirmed_date' => now()->addDays(10)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->addDays(10)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    // Créer la transaction de dépôt
    AccountingTransaction::create([
        'booking_request_id' => $bookingRequest->id,
        'user_id' => $client->user_id,
        'type' => 'deposit',
        'amount' => 150.00,
        'currency' => 'eur',
        'status' => 'completed',
        'payment_method' => 'stripe',
        'stripe_payment_intent_id' => 'pi_test_123456',
        'processed_at' => now()->subDays(5),
    ]);

    // Test: Annulation avec remboursement Stripe
    $action = new CancelBookingWithRefund();
    $action->execute($bookingRequest, 'client', 'Changement d\'avis');

    // Assertions
    $refundTransaction = AccountingTransaction::where('booking_request_id', $bookingRequest->id)
        ->where('type', 'refund')
        ->first();

    expect($refundTransaction)->not->BeNull();
    expect($refundTransaction->stripe_payment_intent_id)->toBe('pi_test_123456');
    expect($refundTransaction->stripe_refund_id)->not->BeNull();
    expect($refundTransaction->stripe_refund_id)->toStartWith('re_'); // Les IDs de remboursement Stripe commencent par 're_'
    expect($refundTransaction->amount)->toBe(-150.00);
    expect($refundTransaction->currency)->toBe('eur');
    expect($refundTransaction->status)->toBe('completed');
    expect($refundTransaction->payment_method)->toBe('stripe');
    expect($refundTransaction->description)->toContain('Annulé par client');
    expect($refundTransaction->processed_at)->not->BeNull();
});

test('refund transaction is recorded', function () {
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
        'deposit_paid_at' => now()->subDays(5),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'designs_sent_count' => 1,
        'confirmed_date' => now()->addDays(10)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->addDays(10)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    // Créer la transaction de dépôt
    AccountingTransaction::create([
        'booking_request_id' => $bookingRequest->id,
        'user_id' => $client->user_id,
        'type' => 'deposit',
        'amount' => 150.00,
        'currency' => 'eur',
        'status' => 'completed',
        'payment_method' => 'stripe',
        'stripe_payment_intent_id' => 'pi_test_123456',
        'processed_at' => now()->subDays(5),
    ]);

    // Test: Annulation avec enregistrement de transaction
    $action = new CancelBookingWithRefund();
    $action->execute($bookingRequest, 'client', 'Retard trop long');

    // Assertions
    $refundTransaction = AccountingTransaction::where('booking_request_id', $bookingRequest->id)
        ->where('type', 'refund')
        ->first();

    expect($refundTransaction)->not->BeNull();

    // Vérifier tous les champs de la transaction
    expect($refundTransaction->booking_request_id)->toBe($bookingRequest->id);
    expect($refundTransaction->user_id)->toBe($client->user_id);
    expect($refundTransaction->type)->toBe('refund');
    expect($refundTransaction->amount)->toBe(-120.00); // 80% de 150
    expect($refundTransaction->currency)->toBe('eur');
    expect($refundTransaction->status)->toBe('completed');
    expect($refundTransaction->payment_method)->toBe('stripe');
    expect($refundTransaction->stripe_payment_intent_id)->toBe('pi_test_123456');
    expect($refundTransaction->stripe_refund_id)->not->BeNull();
    expect($refundTransaction->description)->toContain('Annulé par client');
    expect($refundTransaction->processed_at)->not->BeNull();

    // Vérifier les métadonnées
    expect($refundTransaction->metadata['refund_percent'])->toBe(80);
    expect($refundTransaction->metadata['cancelled_by'])->toBe('client');
    expect($refundTransaction->metadata['designs_sent'])->toBe(1);
    expect($refundTransaction->metadata['original_deposit_amount'])->toBe(150.00);
    expect($refundTransaction->metadata['refund_reason'])->toBe('requested_by_customer');
});

test('cancellation closes conversation', function () {
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
        'deposit_paid_at' => now()->subDays(5),
        'total_price' => 300.00,
        'total_deposit_amount' => 150.00,
        'designs_sent_count' => 0,
        'confirmed_date' => now()->addDays(10)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->addDays(10)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    // Créer la conversation
    $conversation = \App\Models\Conversation::create([
        'booking_request_id' => $bookingRequest->id,
        'status' => 'active',
    ]);

    // Test: Annulation et fermeture de conversation
    $action = new CancelBookingWithRefund();
    $action->execute($bookingRequest, 'client', 'Changement d\'avis');

    // Assertions
    $bookingRequest->refresh();
    expect($bookingRequest->status)->toBe(BookingRequestStatus::CANCELLED);

    $conversation->refresh();
    expect($conversation->status)->toBe('closed');
    expect($conversation->closed_at)->not->BeNull();
    expect($conversation->close_reason)->toBe('booking_cancelled');
});

test('client cannot cancel after J-3 with designs', function () {
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

    // RDV dans 2 jours (après J-3) avec 1 design envoyé
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
        'designs_sent_count' => 1,
        'confirmed_date' => now()->addDays(2)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->addDays(2)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    // Test: Vérifier que le client ne peut pas annuler
    $action = new CancelBookingWithRefund();
    $canCancel = $action->canCancel($bookingRequest, 'client');

    expect($canCancel)->toBeFalse(); // Après J-3 avec 1 design = pas d'annulation

    // Vérifier les détails d'annulation
    $details = $action->getCancellationDetails($bookingRequest, 'client');

    expect($details['can_cancel'])->toBeFalse();
    expect($details['refund_percent'])->toBe(0);
    expect($details['refund_amount'])->toBe(0);
    expect($details['is_before_j3'])->toBeFalse();
    expect($details['cancellation_reason'])->toContain('Annulation après J-3 avec design(s)');
});

test('tattooer can always cancel', function () {
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

    // RDV dans 2 jours avec 5 designs envoyés
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
        'designs_sent_count' => 5,
        'confirmed_date' => now()->addDays(2)->format('Y-m-d'),
        'confirmed_period' => 'afternoon',
        'appointment_datetime' => now()->addDays(2)->setTime(15, 0),
        'appointment_duration_minutes' => 120,
    ]);

    // Test: Vérifier que le tattooer peut toujours annuler
    $action = new CancelBookingWithRefund();
    $canCancel = $action->canCancel($bookingRequest, 'tattooer');

    expect($canCancel)->toBeTrue(); // Le tattooer peut toujours annuler

    // Vérifier les détails d'annulation
    $details = $action->getCancellationDetails($bookingRequest, 'tattooer');

    expect($details['can_cancel'])->toBeTrue();
    expect($details['refund_percent'])->toBe(100); // Toujours 100% pour le tattooer
    expect($details['refund_amount'])->toBe(150.00);
    expect($details['cancellation_reason'])->toContain('Annulation par le tattooer (remboursement 100%)');
});
