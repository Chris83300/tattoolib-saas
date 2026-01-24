<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\Tattooer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        return [
            'booking_request_id' => BookingRequest::factory(),
            'client_id' => Client::factory(),
            'bookable_type' => Tattooer::class,
            'bookable_id' => Tattooer::factory(),
            'start_time' => '2026-01-15 10:00',
            'end_time' => '2026-01-15 12:00',
            'appointment_date' => '2026-01-15',
            'duration_minutes' => $this->faker->numberBetween(60, 240), // minutes
            'total_price' => $this->faker->numberBetween(100, 800),
            'deposit_amount' => $this->faker->numberBetween(20, 200),
            'status' => $this->faker->randomElement(['confirmed', 'completed', 'cancelled', 'client_no_show', 'tattooer_no_show', 'disputed']),
        ];
    }

    public function pendingConfirmation()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending_confirmation',
        ]);
    }

    public function confirmed()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
        ]);
    }

    public function completed()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function cancelled()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    public function noShow()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'no_show',
        ]);
    }
}
