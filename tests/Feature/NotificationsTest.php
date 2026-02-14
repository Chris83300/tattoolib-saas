<?php

use App\Models\User;
use App\Models\Appointment;
use App\Notifications\AppointmentReminderNotification;
use Illuminate\Support\Facades\Notification;

test('appointment reminder notification is sent correctly', function () {
    Notification::fake();

    $user = User::factory()->create();
    $appointment = \App\Models\Appointment::factory()->create([
        'start_datetime' => now()->addDays(7),
        'status' => 'confirmed',
    ]);

    $user->notify(new AppointmentReminderNotification($appointment, 7));

    Notification::assertSentTo($user, AppointmentReminderNotification::class);
});
