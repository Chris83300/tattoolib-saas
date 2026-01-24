<?php

use App\Models\Tattooer;
use App\Models\Availability;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

test('it can generate availabilities from working hours', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

    // Créer un seul working hour pour simplifier
    \App\Models\WorkingHour::factory()->create([
        'owner_type' => Tattooer::class,
        'owner_id' => $tattooer->id,
        'day_of_week' => 1, // Lundi
        'is_open' => true,
        'start_time' => '09:00',
        'end_time' => '18:00',
        'break_start' => '12:00',
        'break_end' => '13:00',
    ]);

    $startDate = now()->startOfDay();
    $endDate = now()->addDays(7)->endOfDay();

    $generated = Availability::generateFromWorkingHours(
        $tattooer->id,
        $startDate,
        $endDate
    );

    // Devrait générer au moins 1 availability
    expect($generated)->toBeGreaterThanOrEqual(1);

    // Vérifier en base
    expect(Availability::count())->toBeGreaterThanOrEqual(1);
});

test('it can block availability for appointment', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

    $availability = Availability::factory()->create([
        'owner_type' => Tattooer::class,
        'owner_id' => $tattooer->user_id,
        'date' => now()->toDateString(),
        'start_time' => '14:00',
        'end_time' => '16:00',
        'type' => Availability::TYPE_AVAILABLE,
    ]);

    $appointment = \App\Models\Appointment::factory()->create([
        'client_id' => \App\Models\Client::factory()->create()->id,
        'start_time' => now()->setTime(14, 0),
        'end_time' => now()->setTime(16, 0),
    ]);

    $availability->blockForAppointment($appointment);

    $availability->refresh();

    expect($availability->type)->toEqual(Availability::TYPE_BUSY);
    expect($availability->appointment_id)->toEqual($appointment->id);
    expect($availability->notes)->toContain("Rendez-vous avec");
});

test('it can release availability', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

    $availability = Availability::factory()->create([
        'owner_type' => Tattooer::class,
        'owner_id' => $tattooer->user_id,
        'date' => now()->toDateString(),
        'start_time' => '14:00',
        'end_time' => '16:00',
        'type' => Availability::TYPE_BUSY,
        'appointment_id' => \App\Models\Appointment::factory()->create()->id,
        'notes' => 'Rendez-vous avec Client',
    ]);

    $availability->release();

    $availability->refresh();

    expect($availability->type)->toEqual(Availability::TYPE_AVAILABLE);
    expect($availability->appointment_id)->toBeNull();
    expect($availability->notes)->toBeNull();
});

test('it can split availability into slots', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

    $availability = Availability::factory()->create([
        'owner_type' => Tattooer::class,
        'owner_id' => $tattooer->user_id,
        'date' => now()->toDateString(),
        'start_time' => '09:00',
        'end_time' => '12:00', // 3h
        'type' => Availability::TYPE_AVAILABLE,
    ]);

    $slots = $availability->splitIntoSlots(60, 15); // 1h slots, 15min buffer

    // Devrait générer 2 créneaux : 09:00-10:00 et 10:15-11:15
    expect($slots)->toHaveCount(2);
    expect($slots[0]['start'])->toEqual('09:00');
    expect($slots[1]['start'])->toEqual('10:15');
    expect($slots[0]['end'])->toEqual('10:00'); // La clé est 'end' pas 'duration'
});

test('it can generate recurring availabilities', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

    $startDate = now()->startOfDay();
    $endDate = now()->addDays(6)->endOfDay();

    $generated = Availability::generateRecurring(
        $tattooer->id,
        $startDate,
        $endDate,
        '10:00',
        '12:00',
        'weekly',
        [1, 3, 5], // Lundi, Mercredi, Vendredi
        Availability::TYPE_AVAILABLE
    );

    // Devrait générer 3 availabilities (lundi, mercredi, vendredi)
    expect($generated)->toEqual(3);

    // Vérifier en base
    expect(Availability::count())->toEqual(3);
});

test('it can get available slots for date', function () {
    $tattooerUser = User::factory()->create();
    $tattooer = Tattooer::factory()->create(['user_id' => $tattooerUser->id]);

    // Créer quelques availabilities
    Availability::factory()->count(3)->create([
        'owner_type' => Tattooer::class,
        'owner_id' => $tattooer->user_id,
        'date' => now()->toDateString(),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'type' => Availability::TYPE_AVAILABLE,
    ]);

    $slots = Availability::getAvailableSlotsForDay(
        $tattooer->user_id,
        now()->toDateString()
    );

    expect($slots)->toBeArray();
    expect($slots)->not->toBeEmpty();
});
