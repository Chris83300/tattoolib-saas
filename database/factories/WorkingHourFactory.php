<?php

namespace Database\Factories;

use App\Models\Tattooer;
use App\Models\WorkingHour;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkingHourFactory extends Factory
{
    protected $model = WorkingHour::class;

    public function definition()
    {
        return [
            'tattooer_id' => Tattooer::factory(),
            'day_of_week' => $this->faker->numberBetween(0, 6), // 0 = Dimanche, 6 = Samedi
            'is_open' => $this->faker->boolean(80), // 80% de chance d'être ouvert
            'opening_time' => $this->faker->time('H:i'),
            'closing_time' => $this->faker->time('H:i'),
            'is_break' => false,
            'break_start' => null,
            'break_end' => null,
        ];
    }

    /**
     * Crée des horaires pour un jour spécifique
     */
    public function forDay(int $dayOfWeek)
    {
        return $this->state(fn (array $attributes) => [
            'day_of_week' => $dayOfWeek,
        ]);
    }

    /**
     * Horaires ouverts
     */
    public function open()
    {
        return $this->state(fn (array $attributes) => [
            'is_open' => true,
        ]);
    }

    /**
     * Horaires fermés
     */
    public function closed()
    {
        return $this->state(fn (array $attributes) => [
            'is_open' => false,
        ]);
    }

    /**
     * Horaires standards (9h-18h)
     */
    public function standardHours()
    {
        return $this->state(fn (array $attributes) => [
            'opening_time' => '09:00',
            'closing_time' => '18:00',
        ]);
    }

    /**
     * Horaires de week-end (10h-17h)
     */
    public function weekendHours()
    {
        return $this->state(fn (array $attributes) => [
            'opening_time' => '10:00',
            'closing_time' => '17:00',
        ]);
    }
}
