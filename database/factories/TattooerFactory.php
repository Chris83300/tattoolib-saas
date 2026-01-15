<?php

namespace Database\Factories;

use App\Models\Studio;
use App\Models\Tattooer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TattooerFactory extends Factory
{
    protected $model = Tattooer::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'studio_id' => $this->faker->boolean(70) ? Studio::factory() : null, // 70% ont un studio
            'siret' => $this->faker->numerify('###########'),
            'siret_verified' => $this->faker->boolean(80), // 80% vérifiés
            'name' => $this->faker->name,
            'studio_name' => $this->faker->boolean(30) ? $this->faker->company : null, // Pour les indépendants
            'bio' => $this->faker->paragraph(3),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'postal_code' => $this->faker->postcode,
            'email' => $this->faker->unique()->safeEmail,

            // Stripe Connect
            'stripe_connect_account_id' => $this->faker->boolean(60) ? 'acct_' . $this->faker->regexify('[A-Za-z0-9]{16}') : null,
            'stripe_onboarding_complete' => $this->faker->boolean(60),

            // Réseaux sociaux
            'instagram' => $this->faker->userName,
            'facebook' => $this->faker->userName,
            'tiktok' => $this->faker->optional()->userName,
            'website' => $this->faker->optional()->url,

            // Paramètres par défaut
            'minimum_deposit' => $this->faker->randomFloat(2, 30, 100),
            'default_deposit_rate' => $this->faker->numberBetween(30, 50),
            'default_client_payment_deadline_days' => 7,
            'default_tattooer_design_deadline_days' => 7,
            'default_design_versions_included' => 3,

            // Délais d'attente
            'weekday_wait_days' => $this->faker->numberBetween(0, 60),
            'weekend_wait_days' => $this->faker->numberBetween(0, 90),
        ];
    }

    /**
     * Tatoueur vérifié et actif
     */
    public function verified()
    {
        return $this->state(fn (array $attributes) => [
            'siret_verified' => true,
            'stripe_onboarding_complete' => true,
            'stripe_connect_account_id' => 'acct_' . $this->faker->regexify('[A-Za-z0-9]{16}'),
        ]);
    }

    /**
     * Tatoueur indépendant (sans studio)
     */
    public function independent()
    {
        return $this->state(fn (array $attributes) => [
            'studio_id' => null,
            'studio_name' => $this->faker->company . ' Tattoo',
        ]);
    }

    /**
     * Tatoueur avec studio
     */
    public function withStudio()
    {
        return $this->state(fn (array $attributes) => [
            'studio_id' => Studio::factory(),
            'studio_name' => null,
        ]);
    }
}
