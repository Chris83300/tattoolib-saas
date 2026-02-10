<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'phone' => $this->faker->optional()->phoneNumber,
            'birth_date' => $this->faker->optional()->date('Y-m-d', '-18 years'),
            'email' => $this->faker->optional()->safeEmail,
            'no_show_count' => 0,
            'is_blacklisted' => false,
            'blacklist_reason' => null,
        ];
    }

    public function blacklisted()
    {
        return $this->state(fn (array $attributes) => [
            'is_blacklisted' => true,
            'blacklist_reason' => $this->faker->sentence,
        ]);
    }

    public function withNoShows(int $count = 1)
    {
        return $this->state(fn (array $attributes) => [
            'no_show_count' => $count,
        ]);
    }
}
