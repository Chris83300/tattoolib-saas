<?php

use App\Enums\ConversationStatus;

test('conversation status enum has correct values', function () {
    expect(ConversationStatus::PENDING->value)->toBe('pending');
    expect(ConversationStatus::ACTIVE->value)->toBe('active');
    expect(ConversationStatus::FULL_ACCESS->value)->toBe('full_access');
    expect(ConversationStatus::CLOSING->value)->toBe('closing');
    expect(ConversationStatus::CLOSED->value)->toBe('closed');
});

test('conversation status labels return non-empty strings', function () {
    foreach (ConversationStatus::cases() as $status) {
        expect($status->label())->toBeString();
        expect($status->label())->not->toBeEmpty();
    }
});

test('conversation status colors return valid color strings', function () {
    foreach (ConversationStatus::cases() as $status) {
        expect($status->color())->toBeString();
        expect($status->color())->not->toBeEmpty();
        expect(in_array($status->color(), ['gray', 'green', 'blue', 'yellow', 'red']))->toBeTrue();
    }
});

test('conversation can transition from pending to active', function () {
    expect(ConversationStatus::PENDING->canTransitionTo(ConversationStatus::ACTIVE))->toBeTrue();
});

test('conversation cannot transition from pending to completed', function () {
    expect(ConversationStatus::PENDING->canTransitionTo(ConversationStatus::FULL_ACCESS))->toBeFalse();
});

test('conversation transitions follow correct workflow', function () {
    // pending → active
    expect(ConversationStatus::PENDING->canTransitionTo(ConversationStatus::ACTIVE))->toBeTrue();
    expect(ConversationStatus::PENDING->canTransitionTo(ConversationStatus::CLOSED))->toBeTrue();
    
    // active → full_access
    expect(ConversationStatus::ACTIVE->canTransitionTo(ConversationStatus::FULL_ACCESS))->toBeTrue();
    expect(ConversationStatus::ACTIVE->canTransitionTo(ConversationStatus::CLOSING))->toBeTrue();
    expect(ConversationStatus::ACTIVE->canTransitionTo(ConversationStatus::CLOSED))->toBeTrue();
    
    // full_access → closing
    expect(ConversationStatus::FULL_ACCESS->canTransitionTo(ConversationStatus::CLOSING))->toBeTrue();
    expect(ConversationStatus::FULL_ACCESS->canTransitionTo(ConversationStatus::CLOSED))->toBeTrue();
    
    // closing → closed
    expect(ConversationStatus::CLOSING->canTransitionTo(ConversationStatus::CLOSED))->toBeTrue();
});

test('conversation terminal status has no transitions', function () {
    expect(ConversationStatus::CLOSED->isTerminal())->toBeTrue();
    expect(ConversationStatus::CLOSED->getPossibleTransitions())->toBeEmpty();
});

test('conversation active statuses are correctly identified', function () {
    $activeStatuses = [
        ConversationStatus::ACTIVE,
        ConversationStatus::FULL_ACCESS
    ];
    
    foreach ($activeStatuses as $status) {
        expect($status->isActive())->toBeTrue();
    }
    
    $nonActiveStatuses = [
        ConversationStatus::PENDING,
        ConversationStatus::CLOSING,
        ConversationStatus::CLOSED
    ];
    
    foreach ($nonActiveStatuses as $status) {
        expect($status->isActive())->toBeFalse();
    }
});

test('conversation messaging permissions', function () {
    $messagingAllowedStatuses = [
        ConversationStatus::ACTIVE,
        ConversationStatus::FULL_ACCESS
    ];
    
    foreach ($messagingAllowedStatuses as $status) {
        expect($status->allowsMessaging())->toBeTrue();
    }
    
    $messagingNotAllowedStatuses = [
        ConversationStatus::PENDING,
        ConversationStatus::CLOSING,
        ConversationStatus::CLOSED
    ];
    
    foreach ($messagingNotAllowedStatuses as $status) {
        expect($status->allowsMessaging())->toBeFalse();
    }
});

test('conversation image permissions', function () {
    expect(ConversationStatus::FULL_ACCESS->allowsImages())->toBeTrue();
    
    $imageNotAllowedStatuses = [
        ConversationStatus::PENDING,
        ConversationStatus::ACTIVE,
        ConversationStatus::CLOSING,
        ConversationStatus::CLOSED
    ];
    
    foreach ($imageNotAllowedStatuses as $status) {
        expect($status->allowsImages())->toBeFalse();
    }
});

test('conversation read only status', function () {
    expect(ConversationStatus::CLOSING->isReadOnly())->toBeTrue();
    
    $nonReadOnlyStatuses = [
        ConversationStatus::PENDING,
        ConversationStatus::ACTIVE,
        ConversationStatus::FULL_ACCESS,
        ConversationStatus::CLOSED
    ];
    
    foreach ($nonReadOnlyStatuses as $status) {
        expect($status->isReadOnly())->toBeFalse();
    }
});

test('conversation closed status', function () {
    expect(ConversationStatus::CLOSED->isClosed())->toBeTrue();
    
    $nonClosedStatuses = [
        ConversationStatus::PENDING,
        ConversationStatus::ACTIVE,
        ConversationStatus::FULL_ACCESS,
        ConversationStatus::CLOSING
    ];
    
    foreach ($nonClosedStatuses as $status) {
        expect($status->isClosed())->toBeFalse();
    }
});

test('conversation archive permissions', function () {
    $archivableStatuses = [
        ConversationStatus::CLOSING,
        ConversationStatus::CLOSED
    ];
    
    foreach ($archivableStatuses as $status) {
        expect($status->canBeArchived())->toBeTrue();
    }
    
    $nonArchivableStatuses = [
        ConversationStatus::PENDING,
        ConversationStatus::ACTIVE,
        ConversationStatus::FULL_ACCESS
    ];
    
    foreach ($nonArchivableStatuses as $status) {
        expect($status->canBeArchived())->toBeFalse();
    }
});

test('conversation deletion permissions', function () {
    expect(ConversationStatus::CLOSED->canBeDeleted())->toBeTrue();
    
    $nonDeletableStatuses = [
        ConversationStatus::PENDING,
        ConversationStatus::ACTIVE,
        ConversationStatus::FULL_ACCESS,
        ConversationStatus::CLOSING
    ];
    
    foreach ($nonDeletableStatuses as $status) {
        expect($status->canBeDeleted())->toBeFalse();
    }
});

test('conversation invalid transitions are rejected', function () {
    // Cannot go backwards in workflow
    expect(ConversationStatus::ACTIVE->canTransitionTo(ConversationStatus::PENDING))->toBeFalse();
    expect(ConversationStatus::FULL_ACCESS->canTransitionTo(ConversationStatus::ACTIVE))->toBeFalse();
    expect(ConversationStatus::CLOSING->canTransitionTo(ConversationStatus::FULL_ACCESS))->toBeFalse();
    expect(ConversationStatus::CLOSED->canTransitionTo(ConversationStatus::CLOSING))->toBeFalse();
    
    // Cannot skip steps
    expect(ConversationStatus::PENDING->canTransitionTo(ConversationStatus::FULL_ACCESS))->toBeFalse();
    expect(ConversationStatus::ACTIVE->canTransitionTo(ConversationStatus::CLOSING))->toBeTrue(); // This is allowed
    expect(ConversationStatus::FULL_ACCESS->canTransitionTo(ConversationStatus::CLOSED))->toBeTrue(); // This is allowed
});
