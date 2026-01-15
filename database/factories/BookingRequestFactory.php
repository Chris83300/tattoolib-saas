<?php

namespace Database\Factories;

use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\Tattooer;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingRequestFactory extends Factory
{
    protected $model = BookingRequest::class;

    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'tattooer_id' => Tattooer::factory(),
            'tattoo_size' => $this->faker->randomElement(['small', 'medium', 'large']),
            'body_zone' => $this->faker->randomElement(['arm', 'leg', 'back', 'chest', 'neck']),
            'description' => $this->faker->paragraph,
            'estimated_budget' => $this->faker->numberBetween(50, 1000),
            'preferred_timeframe' => $this->faker->randomElement(['asap', '3-4months', '5-6months', '6plus']),
            'preferred_days' => json_encode([$this->faker->numberBetween(1, 7)]),
            'date_notes' => $this->faker->optional()->paragraph,
            'total_deposit_amount' => $this->faker->numberBetween(50, 300),
            'estimated_total_price' => $this->faker->numberBetween(300, 1000),
            'client_payment_deadline_days' => 7,
            'tattooer_design_deadline_days' => 7,
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
        ];
    }

    public function pending()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function accepted()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
        ]);
    }

    public function rejected()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
        ]);
    }
}
