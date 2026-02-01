<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'timezone' => 'Europe/Paris',
            'last_login_at' => null,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model has two-factor authentication configured.
     */
    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1'])),
            'two_factor_confirmed_at' => now(),
        ]);
    }

    /**
     * Create a client user.
     */
    public function client(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'client',
        ]);
    }

    /**
     * Create a tattooer user.
     */
    public function tattooer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'tattooer',
        ]);
    }

    /**
     * Create a studio owner user.
     */
    public function studioOwner(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'studio',
        ]);
    }

    /**
     * Create a studio artist user.
     */
    public function studioArtist(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'studio_artist',
        ]);
    }

    /**
     * Create a piercer user.
     */
    public function piercer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'pierceur',
        ]);
    }
}
