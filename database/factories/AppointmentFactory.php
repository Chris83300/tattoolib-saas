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
            'tattooer_id' => Tattooer::factory(),
            'opening_time' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'closing_time' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
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
