<?php

use Tests\TestCase;
use App\Models\Conversation;
use App\Models\Tattooer;
use App\Models\BookingRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

test('new conversation starts_in_deposit_pending_phase', function () {
    $conversation = Conversation::factory()->create([
        'expiry_type' => Conversation::EXPIRY_DEPOSIT_PENDING,
        'expires_at' => now()->addDays(5),
    ]);

    expect($conversation->isDepositPending())->toBeTrue();
    expect($conversation->expires_at)->not->toBeNull();
    expect($conversation->getDaysUntilExpiry())->toBeGreaterThanOrEqual(4);
});

test('conversation_transitions_to_permanent_after_deposit', function () {
    $conversation = Conversation::factory()->create([
        'expiry_type' => Conversation::EXPIRY_DEPOSIT_PENDING,
    ]);

    $conversation->transitionToPermanentPhase();

    expect($conversation->isPermanent())->toBeTrue();
    expect($conversation->expires_at)->toBeNull();
});

test('free_plan_conversation_deleted_after_appointment', function () {
    $tattooer = Tattooer::factory()->create();
    $tattooer->createFreeSubscription();

    $booking = BookingRequest::factory()->create([
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
    ]);

    $conversation = Conversation::factory()->create([
        'booking_request_id' => $booking->id,
        'expiry_type' => Conversation::EXPIRY_PERMANENT,
    ]);

    $conversation->transitionToPostAppointmentPhase();

    expect($conversation->isPostAppointment())->toBeTrue();
    expect($conversation->expires_at)->not->toBeNull();
    expect($conversation->getDaysUntilExpiry())->toBe(0); // Suppression immédiate
});

test('pro_plan_conversation_archived_after_appointment', function () {
    $tattooer = Tattooer::factory()->create();
    $tattooer->upgradeToPro('sub_test', 'price_test');

    $booking = BookingRequest::factory()->create([
        'bookable_type' => Tattooer::class,
        'bookable_id' => $tattooer->id,
    ]);

    $conversation = Conversation::factory()->create([
        'booking_request_id' => $booking->id,
        'expiry_type' => Conversation::EXPIRY_PERMANENT,
    ]);

    $conversation->transitionToPostAppointmentPhase();

    expect($conversation->isArchived())->toBeTrue();
    expect($conversation->expires_at)->toBeNull(); // Jamais supprimé
    expect($conversation->images_preserved)->toBeTrue();
});

test('expired_conversations_are_detected', function () {
    $conversation = Conversation::factory()->create([
        'expiry_type' => Conversation::EXPIRY_DEPOSIT_PENDING,
        'expires_at' => now()->subDay(),
    ]);

    expect($conversation->shouldExpire())->toBeTrue();

    $conversation->markAsExpired();

    expect($conversation->isExpired())->toBeTrue();
});

test('conversation_warning_message_is_correct', function () {
    $conversation = Conversation::factory()->create([
        'expiry_type' => Conversation::EXPIRY_DEPOSIT_PENDING,
        'expires_at' => now()->addHours(23), // Moins de 1 jour
    ]);

    $message = $conversation->getExpiryWarningMessage();

    expect($message)->toContain('URGENT');
    expect($message)->toContain('jour(s)');
});
