<?php

namespace Database\Factories;

use App\Models\StudioArtist;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudioArtistFactory extends Factory
{
    protected $model = StudioArtist::class;

    public function definition(): array
    {
        return [
            'studio_id' => \App\Models\Studio::factory(),
            'user_id' => \App\Models\User::factory(),
            'artist_name' => fake()->name(),
            'slug' => fake()->slug(),
            'bio' => fake()->sentence(),
            'specialties' => json_encode(['realism', 'traditional']),
            'stripe_connect_account_id' => 'acct_test_' . fake()->unique()->randomNumber(),
            'status' => 'active',
            'is_active' => true,
            'joined_at' => now(),
            'total_appointments' => 0,
            'total_revenue' => '0.00',
            'credentials_managed_by_studio' => true,
        ];
    }
}
