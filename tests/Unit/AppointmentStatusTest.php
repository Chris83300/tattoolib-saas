<?php

use App\Enums\AppointmentStatus;

test('appointment status enum has correct values', function () {
    expect(AppointmentStatus::SCHEDULED->value)->toBe('scheduled');
    expect(AppointmentStatus::CONFIRMED->value)->toBe('confirmed');
    expect(AppointmentStatus::IN_PROGRESS->value)->toBe('in_progress');
    expect(AppointmentStatus::COMPLETED->value)->toBe('completed');
    expect(AppointmentStatus::CANCELLED->value)->toBe('cancelled');
    expect(AppointmentStatus::NO_SHOW->value)->toBe('no_show');
});

test('appointment status labels return non-empty strings', function () {
    foreach (AppointmentStatus::cases() as $status) {
        expect($status->label())->toBeString();
        expect($status->label())->not->toBeEmpty();
    }
});

test('appointment status colors return valid color strings', function () {
    foreach (AppointmentStatus::cases() as $status) {
        expect($status->color())->toBeString();
        expect($status->color())->not->toBeEmpty();
        expect(in_array($status->color(), ['blue', 'green', 'yellow', 'emerald', 'red']))->toBeTrue();
    }
});

test('appointment can transition from scheduled to confirmed', function () {
    expect(AppointmentStatus::SCHEDULED->canTransitionTo(AppointmentStatus::CONFIRMED))->toBeTrue();
});

test('appointment cannot transition from scheduled to completed', function () {
    expect(AppointmentStatus::SCHEDULED->canTransitionTo(AppointmentStatus::COMPLETED))->toBeFalse();
});

test('appointment transitions follow correct workflow', function () {
    // scheduled → confirmed
    expect(AppointmentStatus::SCHEDULED->canTransitionTo(AppointmentStatus::CONFIRMED))->toBeTrue();
    expect(AppointmentStatus::SCHEDULED->canTransitionTo(AppointmentStatus::CANCELLED))->toBeTrue();
    
    // confirmed → in_progress
    expect(AppointmentStatus::CONFIRMED->canTransitionTo(AppointmentStatus::IN_PROGRESS))->toBeTrue();
    expect(AppointmentStatus::CONFIRMED->canTransitionTo(AppointmentStatus::CANCELLED))->toBeTrue();
    expect(AppointmentStatus::CONFIRMED->canTransitionTo(AppointmentStatus::NO_SHOW))->toBeTrue();
    
    // in_progress → completed
    expect(AppointmentStatus::IN_PROGRESS->canTransitionTo(AppointmentStatus::COMPLETED))->toBeTrue();
    expect(AppointmentStatus::IN_PROGRESS->canTransitionTo(AppointmentStatus::CANCELLED))->toBeTrue();
});

test('appointment terminal statuses have no transitions', function () {
    $terminalStatuses = [
        AppointmentStatus::COMPLETED,
        AppointmentStatus::CANCELLED,
        AppointmentStatus::NO_SHOW
    ];
    
    foreach ($terminalStatuses as $status) {
        expect($status->isTerminal())->toBeTrue();
        expect($status->getPossibleTransitions())->toBeEmpty();
    }
});

test('appointment active statuses are correctly identified', function () {
    $activeStatuses = [
        AppointmentStatus::SCHEDULED,
        AppointmentStatus::CONFIRMED,
        AppointmentStatus::IN_PROGRESS
    ];
    
    foreach ($activeStatuses as $status) {
        expect($status->isActive())->toBeTrue();
    }
    
    $nonActiveStatuses = [
        AppointmentStatus::COMPLETED,
        AppointmentStatus::CANCELLED,
        AppointmentStatus::NO_SHOW
    ];
    
    foreach ($nonActiveStatuses as $status) {
        expect($status->isActive())->toBeFalse();
    }
});

test('appointment past statuses are correctly identified', function () {
    $pastStatuses = [
        AppointmentStatus::COMPLETED,
        AppointmentStatus::CANCELLED,
        AppointmentStatus::NO_SHOW
    ];
    
    foreach ($pastStatuses as $status) {
        expect($status->isPast())->toBeTrue();
    }
    
    $nonPastStatuses = [
        AppointmentStatus::SCHEDULED,
        AppointmentStatus::CONFIRMED,
        AppointmentStatus::IN_PROGRESS
    ];
    
    foreach ($nonPastStatuses as $status) {
        expect($status->isPast())->toBeFalse();
    }
});

test('appointment confirmation requirements', function () {
    expect(AppointmentStatus::SCHEDULED->needsConfirmation())->toBeTrue();
    
    $nonConfirmationStatuses = [
        AppointmentStatus::CONFIRMED,
        AppointmentStatus::IN_PROGRESS,
        AppointmentStatus::COMPLETED,
        AppointmentStatus::CANCELLED,
        AppointmentStatus::NO_SHOW
    ];
    
    foreach ($nonConfirmationStatuses as $status) {
        expect($status->needsConfirmation())->toBeFalse();
    }
});

test('appointment cancellation permissions', function () {
    $cancellableStatuses = [
        AppointmentStatus::SCHEDULED,
        AppointmentStatus::CONFIRMED
    ];
    
    foreach ($cancellableStatuses as $status) {
        expect($status->isCancellable())->toBeTrue();
    }
    
    $nonCancellableStatuses = [
        AppointmentStatus::IN_PROGRESS,
        AppointmentStatus::COMPLETED,
        AppointmentStatus::CANCELLED,
        AppointmentStatus::NO_SHOW
    ];
    
    foreach ($nonCancellableStatuses as $status) {
        expect($status->isCancellable())->toBeFalse();
    }
});

test('appointment completion permissions', function () {
    expect(AppointmentStatus::IN_PROGRESS->canBeCompleted())->toBeTrue();
    
    $nonCompletableStatuses = [
        AppointmentStatus::SCHEDULED,
        AppointmentStatus::CONFIRMED,
        AppointmentStatus::COMPLETED,
        AppointmentStatus::CANCELLED,
        AppointmentStatus::NO_SHOW
    ];
    
    foreach ($nonCompletableStatuses as $status) {
        expect($status->canBeCompleted())->toBeFalse();
    }
});

test('appointment no show reporting permissions', function () {
    expect(AppointmentStatus::CONFIRMED->canReportNoShow())->toBeTrue();
    
    $nonReportableStatuses = [
        AppointmentStatus::SCHEDULED,
        AppointmentStatus::IN_PROGRESS,
        AppointmentStatus::COMPLETED,
        AppointmentStatus::CANCELLED,
        AppointmentStatus::NO_SHOW
    ];
    
    foreach ($nonReportableStatuses as $status) {
        expect($status->canReportNoShow())->toBeFalse();
    }
});

test('appointment invalid transitions are rejected', function () {
    // Cannot go backwards in workflow
    expect(AppointmentStatus::CONFIRMED->canTransitionTo(AppointmentStatus::SCHEDULED))->toBeFalse();
    expect(AppointmentStatus::IN_PROGRESS->canTransitionTo(AppointmentStatus::CONFIRMED))->toBeFalse();
    expect(AppointmentStatus::COMPLETED->canTransitionTo(AppointmentStatus::IN_PROGRESS))->toBeFalse();
    
    // Cannot skip steps
    expect(AppointmentStatus::SCHEDULED->canTransitionTo(AppointmentStatus::IN_PROGRESS))->toBeFalse();
    expect(AppointmentStatus::CONFIRMED->canTransitionTo(AppointmentStatus::COMPLETED))->toBeFalse();
});
