<?php

namespace Database\Factories;

use App\Models\Studio;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudioFactory extends Factory
{
    protected $model = Studio::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company . ' Tattoo Studio',
            'user_id' => \App\Models\User::factory(),
            'description' => $this->faker->paragraph(2),
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'postal_code' => $this->faker->postcode,
            'country' => 'France',
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->companyEmail,
            'website' => $this->faker->optional()->url,
            'social_media_links' => [
                'instagram' => 'https://instagram.com/' . $this->faker->userName,
                'facebook' => 'https://facebook.com/' . $this->faker->userName,
            ],
            'logo_url' => $this->faker->optional()->imageUrl(200, 200, 'business'),
            'latitude' => $this->faker->latitude(43, 49), // France approximativement
            'longitude' => $this->faker->longitude(-5, 8),
            'is_verified' => $this->faker->boolean(70),
            'opening_hours' => [
                'monday' => ['open' => '09:00', 'close' => '18:00'],
                'tuesday' => ['open' => '09:00', 'close' => '18:00'],
                'wednesday' => ['open' => '09:00', 'close' => '18:00'],
                'thursday' => ['open' => '09:00', 'close' => '18:00'],
                'friday' => ['open' => '09:00', 'close' => '18:00'],
                'saturday' => ['open' => '10:00', 'close' => '17:00'],
                'sunday' => ['closed' => true],
            ],
        ];
    }

    /**
     * Studio vérifié
     */
    public function verified()
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
        ]);
    }
}
