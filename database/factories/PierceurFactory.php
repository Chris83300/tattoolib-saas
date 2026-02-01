<?php

namespace Database\Factories;

use App\Models\Studio;
use App\Models\Pierceur;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PierceurFactory extends Factory
{
    protected $model = Pierceur::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create(['role' => 'pierceur']),
            'studio_id' => $this->faker->boolean(70) ? Studio::factory() : null, // 70% ont un studio
            'siret' => $this->faker->numerify('###########'),
            'siret_verified' => $this->faker->boolean(80), // 80% vérifiés
            'name' => $this->faker->name,
            'slug' => $this->faker->unique()->slug(),
            'specialization' => $this->faker->randomElement(['pierceur', 'bodemodeur', 'pierceur_bodemodeur']),
            'studio_name' => $this->faker->boolean(30) ? $this->faker->company . ' Piercing' : null, // Pour les indépendants
            'bio' => $this->faker->paragraph(3),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'postal_code' => $this->faker->postcode,
            'email' => $this->faker->unique()->safeEmail,

            // Configuration
            'minimum_deposit' => $this->faker->numberBetween(30, 100),
            'default_deposit_rate' => $this->faker->numberBetween(20, 40),
            'default_client_payment_deadline_days' => $this->faker->numberBetween(3, 14),
            'default_design_versions_included' => $this->faker->numberBetween(1, 5),
            'weekday_wait_days' => $this->faker->numberBetween(3, 14),
            'weekend_wait_days' => $this->faker->numberBetween(7, 21),

            // Réseaux sociaux
            'instagram' => $this->faker->optional()->userName,
            'facebook' => $this->faker->optional()->userName,
            'tiktok' => $this->faker->optional()->userName,
            'website' => $this->faker->optional()->url,

            // Abonnement
            'current_plan' => $this->faker->randomElement(['free', 'pro']),
            'is_subscribed' => $this->faker->boolean(30),

            // Stripe Connect
            'stripe_connect_account_id' => $this->faker->optional()->uuid,
            'stripe_onboarding_complete' => $this->faker->boolean(50),
            'has_accepted_payment_terms' => $this->faker->boolean(70),

            // Conformité
            'has_compliance_badge' => $this->faker->boolean(60),
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'siret_verified' => true,
            'has_compliance_badge' => true,
            'stripe_onboarding_complete' => true,
        ]);
    }

    public function withStudio(): static
    {
        return $this->state(fn (array $attributes) => [
            'studio_id' => Studio::factory(),
        ]);
    }

    public function pro(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_plan' => 'pro',
            'is_subscribed' => true,
            'upgraded_to_pro_at' => now()->subMonths(rand(1, 12)),
        ]);
    }

    public function pierceurSpecialization(): static
    {
        return $this->state(fn (array $attributes) => [
            'specialization' => 'pierceur',
        ]);
    }

    public function bodemodeur(): static
    {
        return $this->state(fn (array $attributes) => [
            'specialization' => 'bodemodeur',
        ]);
    }

    public function both(): static
    {
        return $this->state(fn (array $attributes) => [
            'specialization' => 'pierceur_bodemodeur',
        ]);
    }
}
