<?php

use App\Enums\BookingRequestStatus;

test('booking request status enum has correct values', function () {
    expect(BookingRequestStatus::PENDING->value)->toBe('pending');
    expect(BookingRequestStatus::ACCEPTED->value)->toBe('accepted');
    expect(BookingRequestStatus::DEPOSIT_REQUESTED->value)->toBe('deposit_requested');
    expect(BookingRequestStatus::DEPOSIT_PAID->value)->toBe('deposit_paid');
    expect(BookingRequestStatus::DATE_CONFIRMED->value)->toBe('date_confirmed');
    expect(BookingRequestStatus::COMPLETED->value)->toBe('completed');
    expect(BookingRequestStatus::CANCELLED->value)->toBe('cancelled');
    expect(BookingRequestStatus::EXPIRED->value)->toBe('expired');
    expect(BookingRequestStatus::NO_SHOW->value)->toBe('no_show');
});

test('booking request status labels return non-empty strings', function () {
    foreach (BookingRequestStatus::cases() as $status) {
        expect($status->label())->toBeString();
        expect($status->label())->not->toBeEmpty();
    }
});

test('booking request status colors return valid color strings', function () {
    foreach (BookingRequestStatus::cases() as $status) {
        expect($status->color())->toBeString();
        expect($status->color())->not->toBeEmpty();
        expect(in_array($status->color(), ['gray', 'blue', 'yellow', 'green', 'indigo', 'emerald', 'red', 'orange']))->toBeTrue();
    }
});

test('booking request can transition from pending to accepted', function () {
    expect(BookingRequestStatus::PENDING->canTransitionTo(BookingRequestStatus::ACCEPTED))->toBeTrue();
});

test('booking request cannot transition from pending to completed', function () {
    expect(BookingRequestStatus::PENDING->canTransitionTo(BookingRequestStatus::COMPLETED))->toBeFalse();
});

test('booking request transitions follow correct workflow', function () {
    // pending → accepted
    expect(BookingRequestStatus::PENDING->canTransitionTo(BookingRequestStatus::ACCEPTED))->toBeTrue();
    expect(BookingRequestStatus::PENDING->canTransitionTo(BookingRequestStatus::CANCELLED))->toBeTrue();
    
    // accepted → deposit_requested
    expect(BookingRequestStatus::ACCEPTED->canTransitionTo(BookingRequestStatus::DEPOSIT_REQUESTED))->toBeTrue();
    
    // deposit_requested → deposit_paid
    expect(BookingRequestStatus::DEPOSIT_REQUESTED->canTransitionTo(BookingRequestStatus::DEPOSIT_PAID))->toBeTrue();
    
    // deposit_paid → date_confirmed
    expect(BookingRequestStatus::DEPOSIT_PAID->canTransitionTo(BookingRequestStatus::DATE_CONFIRMED))->toBeTrue();
    
    // date_confirmed → completed
    expect(BookingRequestStatus::DATE_CONFIRMED->canTransitionTo(BookingRequestStatus::COMPLETED))->toBeTrue();
});

test('booking request terminal statuses have no transitions', function () {
    $terminalStatuses = [
        BookingRequestStatus::COMPLETED,
        BookingRequestStatus::CANCELLED,
        BookingRequestStatus::EXPIRED,
        BookingRequestStatus::NO_SHOW
    ];
    
    foreach ($terminalStatuses as $status) {
        expect($status->isTerminal())->toBeTrue();
        expect($status->getPossibleTransitions())->toBeEmpty();
    }
});

test('booking request active statuses are correctly identified', function () {
    $activeStatuses = [
        BookingRequestStatus::ACCEPTED,
        BookingRequestStatus::DEPOSIT_REQUESTED,
        BookingRequestStatus::DEPOSIT_PAID,
        BookingRequestStatus::DATE_CONFIRMED
    ];
    
    foreach ($activeStatuses as $status) {
        expect($status->isActive())->toBeTrue();
    }
    
    $nonActiveStatuses = [
        BookingRequestStatus::PENDING,
        BookingRequestStatus::COMPLETED,
        BookingRequestStatus::CANCELLED,
        BookingRequestStatus::EXPIRED,
        BookingRequestStatus::NO_SHOW
    ];
    
    foreach ($nonActiveStatuses as $status) {
        expect($status->isActive())->toBeFalse();
    }
});

test('booking request deposit payment permissions', function () {
    expect(BookingRequestStatus::DEPOSIT_REQUESTED->allowsDepositPayment())->toBeTrue();
    
    $nonDepositStatuses = [
        BookingRequestStatus::PENDING,
        BookingRequestStatus::ACCEPTED,
        BookingRequestStatus::DEPOSIT_PAID,
        BookingRequestStatus::DATE_CONFIRMED,
        BookingRequestStatus::COMPLETED,
        BookingRequestStatus::CANCELLED,
        BookingRequestStatus::EXPIRED,
        BookingRequestStatus::NO_SHOW
    ];
    
    foreach ($nonDepositStatuses as $status) {
        expect($status->allowsDepositPayment())->toBeFalse();
    }
});

test('booking request design sending permissions', function () {
    $designAllowedStatuses = [
        BookingRequestStatus::DEPOSIT_PAID,
        BookingRequestStatus::DATE_CONFIRMED
    ];
    
    foreach ($designAllowedStatuses as $status) {
        expect($status->allowsDesignSending())->toBeTrue();
    }
    
    $designNotAllowedStatuses = [
        BookingRequestStatus::PENDING,
        BookingRequestStatus::ACCEPTED,
        BookingRequestStatus::DEPOSIT_REQUESTED,
        BookingRequestStatus::COMPLETED,
        BookingRequestStatus::CANCELLED,
        BookingRequestStatus::EXPIRED,
        BookingRequestStatus::NO_SHOW
    ];
    
    foreach ($designNotAllowedStatuses as $status) {
        expect($status->allowsDesignSending())->toBeFalse();
    }
});

test('booking request date confirmation permissions', function () {
    expect(BookingRequestStatus::DEPOSIT_PAID->allowsDateConfirmation())->toBeTrue();
    
    $nonConfirmationStatuses = [
        BookingRequestStatus::PENDING,
        BookingRequestStatus::ACCEPTED,
        BookingRequestStatus::DEPOSIT_REQUESTED,
        BookingRequestStatus::DATE_CONFIRMED,
        BookingRequestStatus::COMPLETED,
        BookingRequestStatus::CANCELLED,
        BookingRequestStatus::EXPIRED,
        BookingRequestStatus::NO_SHOW
    ];
    
    foreach ($nonConfirmationStatuses as $status) {
        expect($status->allowsDateConfirmation())->toBeFalse();
    }
});

test('booking request invalid transitions are rejected', function () {
    // Cannot go backwards in workflow
    expect(BookingRequestStatus::ACCEPTED->canTransitionTo(BookingRequestStatus::PENDING))->toBeFalse();
    expect(BookingRequestStatus::DEPOSIT_PAID->canTransitionTo(BookingRequestStatus::ACCEPTED))->toBeFalse();
    expect(BookingRequestStatus::COMPLETED->canTransitionTo(BookingRequestStatus::DATE_CONFIRMED))->toBeFalse();
    
    // Cannot skip steps
    expect(BookingRequestStatus::PENDING->canTransitionTo(BookingRequestStatus::DEPOSIT_PAID))->toBeFalse();
    expect(BookingRequestStatus::ACCEPTED->canTransitionTo(BookingRequestStatus::DATE_CONFIRMED))->toBeFalse();
});
