<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\Tattooer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentExtendedFactory extends Factory
{
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $appointmentDate = $this->faker->dateTimeBetween('now', '+6 months');
        $startTime = $this->faker->time('H:i', '16:00');
        $duration = $this->faker->numberBetween(60, 240);
        $endTime = \Carbon\Carbon::parse($startTime)->addMinutes($duration)->format('H:i');

        return [
            'booking_request_id' => BookingRequest::factory(),
            'bookable_type' => Tattooer::class,
            'bookable_id' => Tattooer::factory(),
            'client_id' => Client::factory(),

            // ⭐ NOUVEAUX CHAMPS
            'appointment_date' => $appointmentDate->format('Y-m-d'),
            'source' => $this->faker->randomElement([
                'platform',
                'external_walk_in',
                'external_phone',
                'external_social'
            ]),
            'external_source_notes' => $this->faker->optional(0.3)->sentence(),

            // Champs existants (renommés)
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_minutes' => $duration,

            'deposit_amount' => $this->faker->randomFloat(2, 30, 300),
            'total_price' => $this->faker->randomFloat(2, 100, 1500),
            'remaining_amount' => $this->faker->randomFloat(2, 50, 1200),

            'status' => Appointment::STATUS_CONFIRMED,

            'cancelled_by' => null,
            'cancelled_at' => null,
            'cancellation_reason' => null,
            'days_before_appointment' => null,

            'refunded' => false,
            'refund_amount' => null,
            'refunded_at' => null,
            'stripe_refund_id' => null,

            'tattooer_confirmation_status' => null,
            'tattooer_confirmation_note' => null,
            'tattooer_confirmed_at' => null,

            'client_reported_issue' => false,
            'client_issue_description' => null,
            'client_reported_at' => null,

            'requires_manual_review' => false,
        ];
    }

    /**
     * ⭐ NOUVEAU : RDV depuis la plateforme
     */
    public function fromPlatform(): static
    {
        return $this->state(fn (array $attributes) => [
            'source' => 'platform',
            'external_source_notes' => null,
        ]);
    }

    /**
     * ⭐ NOUVEAU : RDV externe walk-in
     */
    public function externalWalkIn(): static
    {
        return $this->state(fn (array $attributes) => [
            'source' => 'external_walk_in',
            'external_source_notes' => $this->faker->randomElement([
                'Client entré directement en boutique',
                'Walk-in spontané',
                'Pris sur place',
            ]),
        ]);
    }

    /**
     * ⭐ NOUVEAU : RDV externe téléphone
     */
    public function externalPhone(): static
    {
        return $this->state(fn (array $attributes) => [
            'source' => 'external_phone',
            'external_source_notes' => $this->faker->randomElement([
                'Pris par téléphone - ' . $this->faker->name(),
                'Appel client direct',
                'Réservation téléphonique',
            ]),
        ]);
    }

    /**
     * ⭐ NOUVEAU : RDV externe réseaux sociaux
     */
    public function externalSocial(): static
    {
        return $this->state(fn (array $attributes) => [
            'source' => 'external_social',
            'external_source_notes' => $this->faker->randomElement([
                'Pris via Instagram - ' . $this->faker->userName(),
                'Contact Facebook Messenger',
                'DM Instagram',
                'Message TikTok',
            ]),
        ]);
    }

    /**
     * ⭐ NOUVEAU : RDV pour aujourd'hui
     */
    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'appointment_date' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * ⭐ NOUVEAU : RDV pour demain
     */
    public function tomorrow(): static
    {
        return $this->state(fn (array $attributes) => [
            'appointment_date' => now()->addDay()->format('Y-m-d'),
        ]);
    }

    /**
     * ⭐ NOUVEAU : RDV de longue durée
     */
    public function longDuration(): static
    {
        $duration = $this->faker->numberBetween(180, 480); // 3h à 8h
        $startTime = $this->faker->time('H:i', '10:00');
        $endTime = \Carbon\Carbon::parse($startTime)->addMinutes($duration)->format('H:i');

        return $this->state(fn (array $attributes) => [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_minutes' => $duration,
            'total_price' => $this->faker->randomFloat(2, 500, 3000),
        ]);
    }

    /**
     * ⭐ NOUVEAU : RDV annulé
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Appointment::STATUS_CANCELLED,
            'cancelled_by' => $this->faker->randomElement(['client', 'tattooer']),
            'cancelled_at' => now()->subDays(rand(1, 10)),
            'cancellation_reason' => $this->faker->sentence(10),
            'days_before_appointment' => $this->faker->numberBetween(1, 30),
        ]);
    }

    /**
     * ⭐ NOUVEAU : RDV terminé
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Appointment::STATUS_COMPLETED,
            'tattooer_confirmation_status' => 'completed',
            'tattooer_confirmed_at' => now()->subHours(rand(1, 24)),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Client absent
     */
    public function clientNoShow(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Appointment::STATUS_CLIENT_NO_SHOW,
            'tattooer_confirmation_status' => 'client_no_show',
            'tattooer_confirmed_at' => now()->subHours(rand(1, 6)),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Tatoueur absent
     */
    public function tattooerNoShow(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Appointment::STATUS_TATTOOER_NO_SHOW,
            'tattooer_confirmation_status' => 'other_issue',
            'tattooer_confirmation_note' => 'Tatoueur absent pour raison imprévue',
            'tattooer_confirmed_at' => now()->subHours(rand(1, 6)),
        ]);
    }

    /**
     * ⭐ NOUVEAU : RDV avec litige
     */
    public function disputed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Appointment::STATUS_DISPUTED,
            'client_reported_issue' => true,
            'client_issue_description' => $this->faker->sentence(15),
            'client_reported_at' => now()->subHours(rand(1, 48)),
            'requires_manual_review' => true,
        ]);
    }

    /**
     * ⭐ NOUVEAU : RDV remboursé
     */
    public function refunded(): static
    {
        return $this->cancelled()->state(fn (array $attributes) => [
            'refunded' => true,
            'refund_amount' => $attributes['deposit_amount'] * 0.5, // Remboursement partiel
            'refunded_at' => now()->subHours(rand(1, 24)),
            'stripe_refund_id' => 're_' . $this->faker->sha256,
        ]);
    }

    /**
     * ⭐ NOUVEAU : Créneau matin
     */
    public function morning(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_time' => $this->faker->time('H:i', '11:00'),
            'end_time' => $this->faker->time('H:i', '12:00'),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Créneau après-midi
     */
    public function afternoon(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_time' => $this->faker->time('H:i', '14:00', '16:00'),
            'end_time' => $this->faker->time('H:i', '17:00', '18:00'),
        ]);
    }

    /**
     * Pour un tatoueur spécifique
     */
    public function forTattooer(int $tattooerId): static
    {
        return $this->state(fn (array $attributes) => [
            'bookable_type' => Tattooer::class,
            'bookable_id' => $tattooerId,
        ]);
    }

    /**
     * Pour un client spécifique
     */
    public function forClient(int $clientId): static
    {
        return $this->state(fn (array $attributes) => [
            'client_id' => $clientId,
        ]);
    }

    /**
     * Pour une date spécifique
     */
    public function onDate(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'appointment_date' => $date,
        ]);
    }

    /**
     * ⭐ NOUVEAU : RDV avec prix élevé
     */
    public function highValue(): static
    {
        return $this->state(fn (array $attributes) => [
            'total_price' => $this->faker->randomFloat(2, 1000, 5000),
            'deposit_amount' => $this->faker->randomFloat(2, 300, 1500),
            'duration_minutes' => $this->faker->numberBetween(180, 360),
        ]);
    }
}
