<?php

namespace Database\Factories;

use App\Models\Availability;
use App\Models\StudioArtist;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvailabilityFactory extends Factory
{
    protected $model = Availability::class;

    public function definition()
    {
        return [
            'owner_type' => StudioArtist::class,
            'owner_id' => StudioArtist::factory(),
            'date' => $this->faker->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'start_time' => $this->faker->time('H:i'),
            'end_time' => $this->faker->time('H:i', '18:00'),
            'type' => $this->faker->randomElement([
                Availability::TYPE_AVAILABLE,
                Availability::TYPE_BUSY,
                Availability::TYPE_BREAK,
                Availability::TYPE_HOLIDAY,
                Availability::TYPE_EXTERNAL_BOOKING, // ⭐ NOUVEAU
                Availability::TYPE_BLOCKED, // ⭐ NOUVEAU
            ]),
            'source' => $this->faker->randomElement([
                Availability::SOURCE_WORKING_HOURS,
                Availability::SOURCE_MANUAL,
                Availability::SOURCE_APPOINTMENT,
                Availability::SOURCE_EXTERNAL,
            ]),
            'notes' => $this->faker->optional(0.3)->sentence(),
            'is_recurring' => false,
            'recurring_pattern' => null,
            'recurring_end_date' => null,
            'appointment_id' => null,
        ];
    }

    /**
     * Disponibilité
     */
    public function available()
    {
        return $this->state(fn (array $attributes) => [
            'type' => Availability::TYPE_AVAILABLE,
            'source' => Availability::SOURCE_WORKING_HOURS,
            'appointment_id' => null,
            'notes' => null,
        ]);
    }

    /**
     * Occupé
     */
    public function busy()
    {
        return $this->state(fn (array $attributes) => [
            'type' => Availability::TYPE_BUSY,
            'source' => Availability::SOURCE_APPOINTMENT,
            'appointment_id' => null, // Pas de contrainte FK dans les tests
        ]);
    }

    /**
     * Pause
     */
    public function break()
    {
        return $this->state(fn (array $attributes) => [
            'type' => Availability::TYPE_BREAK,
            'source' => Availability::SOURCE_WORKING_HOURS,
        ]);
    }

    /**
     * ⭐ NOUVEAU : RDV externe
     */
    public function externalBooking()
    {
        return $this->state(fn (array $attributes) => [
            'type' => Availability::TYPE_EXTERNAL_BOOKING,
            'source' => Availability::SOURCE_EXTERNAL,
            'notes' => $this->faker->randomElement([
                'Pris en boutique',
                'Pris par téléphone',
                'Pris sur Instagram',
                'Client walk-in',
            ]),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Bloqué manuellement
     */
    public function blocked()
    {
        return $this->state(fn (array $attributes) => [
            'type' => Availability::TYPE_BLOCKED,
            'source' => Availability::SOURCE_MANUAL,
            'notes' => $this->faker->randomElement([
                'Rendez-vous personnel',
                'Formation',
                'Maintenance',
                'Congés exceptionnels',
            ]),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Journée complète de travail
     */
    public function fullWorkDay()
    {
        return $this->state(fn (array $attributes) => [
            'start_time' => '09:00',
            'end_time' => '18:00',
            'type' => Availability::TYPE_AVAILABLE,
            'source' => Availability::SOURCE_WORKING_HOURS,
        ]);
    }

    /**
     * ⭐ NOUVEAU : Pause déjeuner
     */
    public function lunchBreak()
    {
        return $this->state(fn (array $attributes) => [
            'start_time' => '12:00',
            'end_time' => '13:00',
            'type' => Availability::TYPE_BREAK,
            'source' => Availability::SOURCE_WORKING_HOURS,
        ]);
    }

    /**
     * ⭐ NOUVEAU : Congés
     */
    public function holiday()
    {
        return $this->state(fn (array $attributes) => [
            'type' => Availability::TYPE_HOLIDAY,
            'source' => Availability::SOURCE_MANUAL,
            'start_time' => '00:00',
            'end_time' => '23:59',
            'notes' => $this->faker->randomElement([
                'Vacances d\'été',
                'Vacances d\'hiver',
                'Férié',
                'Congés maladie',
            ]),
        ]);
    }

    /**
     * Pour une date spécifique
     */
    public function forDate(string $date)
    {
        return $this->state(fn (array $attributes) => [
            'date' => $date,
        ]);
    }

    /**
     * ⭐ NOUVEAU : Pour aujourd'hui
     */
    public function today()
    {
        return $this->state(fn (array $attributes) => [
            'date' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Pour demain
     */
    public function tomorrow()
    {
        return $this->state(fn (array $attributes) => [
            'date' => now()->addDay()->format('Y-m-d'),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Créneau matin
     */
    public function morning()
    {
        return $this->state(fn (array $attributes) => [
            'start_time' => '09:00',
            'end_time' => '12:00',
        ]);
    }

    /**
     * ⭐ NOUVEAU : Créneau après-midi
     */
    public function afternoon()
    {
        return $this->state(fn (array $attributes) => [
            'start_time' => '14:00',
            'end_time' => '18:00',
        ]);
    }

    /**
     * ⭐ NOUVEAU : Durée spécifique (minutes)
     */
    public function duration(int $minutes)
    {
        $startTime = $this->faker->time('H:i', '16:00');
        $endTime = \Carbon\Carbon::parse($startTime)->addMinutes($minutes)->format('H:i');

        return $this->state(fn (array $attributes) => [
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
    }

    /**
     * Pour un tatoueur spécifique
     */
    public function forTattooer(int $tattooerId)
    {
        return $this->state(fn (array $attributes) => [
            'tattooer_id' => $tattooerId,
        ]);
    }

    /**
     * ⭐ NOUVEAU : Récurrent
     */
    public function recurring(string $pattern = 'weekly')
    {
        return $this->state(fn (array $attributes) => [
            'is_recurring' => true,
            'recurring_pattern' => $pattern,
            'recurring_end_date' => $this->faker->dateTimeBetween('+1 month', '+6 months'),
        ]);
    }
}
