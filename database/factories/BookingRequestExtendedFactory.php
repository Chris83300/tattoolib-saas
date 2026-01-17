<?php

namespace Database\Factories;

use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\Tattooer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookingRequest>
 */
class BookingRequestFactory extends Factory
{
    protected $model = BookingRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'tattooer_id' => Tattooer::factory(),
            'tattoo_size' => $this->faker->randomElement(['petit', 'moyen', 'grand', 'très grand']),
            'body_zone' => $this->faker->randomElement([
                'bras', 'jambe', 'dos', 'épaule', 'avant-bras', 'mollet',
                'cuisse', 'poignet', 'cheville', 'cou', 'nuque', 'main', 'pied'
            ]),
            'description' => $this->faker->sentence(15),
            'estimated_budget' => $this->faker->optional(0.7)->randomFloat(2, 50, 2000),

            // ⭐ NOUVEAUX CHAMPS
            'preferred_date' => $this->faker->optional(0.6)->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'preferred_time_slot' => $this->faker->randomElement(['morning', 'afternoon', 'evening', 'anytime']),
            'preferred_time_notes' => $this->faker->optional(0.4)->sentence(10),

            'scheduled_start_time' => null,
            'scheduled_end_time' => null,
            'scheduled_duration_minutes' => null,

            'total_price' => null,
            'total_deposit_amount' => null,
            'deposit_deadline' => null,

            'accepted_at' => null,
            'deposit_requested_at' => null,
            'deposit_paid_at' => null,
            'expired_at' => null,

            // Champs existants conservés
            'preferred_timeframe' => $this->faker->optional(0.3)->randomElement(['asap', '3-4months', '5-6months', '6plus']),
            'preferred_days' => $this->faker->optional(0.3)->randomElements(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'], 3),
            'date_notes' => $this->faker->optional(0.3)->sentence(),

            'client_payment_deadline_days' => 7,
            'tattooer_design_deadline_days' => 7,
            'client_payment_deadline' => null,
            'tattooer_design_deadline' => null,
            'design_sent_at' => null,

            'is_long_term_booking' => false,
            'design_preparation_starts_at' => null,
            'design_preparation_notified' => false,

            'included_design_versions' => 3,
            'design_versions_used' => 0,

            'stripe_payment_intent_id' => null,

            'status' => BookingRequest::STATUS_PENDING,

            'tattooer_missed_deadline' => false,
            'client_missed_deadline' => false,

            'appointment_datetime' => null,
            'appointment_duration_minutes' => null,
        ];
    }

    /**
     * ⭐ NOUVEAU : Demande avec préférences de date/horaire
     */
    public function withPreferences(): static
    {
        return $this->state(fn (array $attributes) => [
            'preferred_date' => $this->faker->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'preferred_time_slot' => $this->faker->randomElement(['morning', 'afternoon', 'evening']),
            'preferred_time_notes' => $this->faker->sentence(8),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Demande acceptée avec horaire fixé
     */
    public function accepted(): static
    {
        $scheduledDate = $this->faker->dateTimeBetween('now', '+3 months');
        $startTime = $this->faker->time('H:i', '16:00');
        $duration = $this->faker->numberBetween(60, 240);
        $endTime = \Carbon\Carbon::parse($startTime)->addMinutes($duration)->format('H:i');
        $totalPrice = $this->faker->randomFloat(2, 100, 1000);
        $depositAmount = $totalPrice * 0.3;

        return $this->state(fn (array $attributes) => [
            'status' => BookingRequest::STATUS_ACCEPTED,
            'accepted_at' => now(),
            'scheduled_start_time' => $startTime,
            'scheduled_end_time' => $endTime,
            'scheduled_duration_minutes' => $duration,
            'total_price' => $totalPrice,
            'total_deposit_amount' => $depositAmount,
            'deposit_deadline' => now()->addHours(72),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Demande avec acompte payé
     */
    public function depositPaid(): static
    {
        return $this->accepted()->state(fn (array $attributes) => [
            'status' => BookingRequest::STATUS_DEPOSIT_PAID,
            'deposit_paid_at' => now(),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Demande expirée
     */
    public function expired(): static
    {
        return $this->accepted()->state(fn (array $attributes) => [
            'status' => BookingRequest::STATUS_EXPIRED,
            'deposit_deadline' => now()->subHours(24),
            'expired_at' => now()->subHours(12),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Demande rejetée
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingRequest::STATUS_REJECTED,
        ]);
    }

    /**
     * ⭐ NOUVEAU : Demande confirmée (RDV final)
     */
    public function confirmed(): static
    {
        $appointmentDate = $this->faker->dateTimeBetween('now', '+3 months');

        return $this->depositPaid()->state(fn (array $attributes) => [
            'status' => BookingRequest::STATUS_CONFIRMED,
            'appointment_datetime' => $appointmentDate,
            'appointment_duration_minutes' => $attributes['scheduled_duration_minutes'] ?? 120,
        ]);
    }

    /**
     * ⭐ NOUVEAU : Demande pour aujourd'hui
     */
    public function forToday(): static
    {
        return $this->state(fn (array $attributes) => [
            'preferred_date' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Demande pour demain
     */
    public function forTomorrow(): static
    {
        return $this->state(fn (array $attributes) => [
            'preferred_date' => now()->addDay()->format('Y-m-d'),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Demande avec budget élevé
     */
    public function highBudget(): static
    {
        return $this->state(fn (array $attributes) => [
            'estimated_budget' => $this->faker->randomFloat(2, 800, 3000),
            'tattoo_size' => $this->faker->randomElement(['grand', 'très grand']),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Demande long terme
     */
    public function longTerm(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_long_term_booking' => true,
            'preferred_timeframe' => '6plus',
            'preferred_date' => $this->faker->dateTimeBetween('+6 months', '+12 months')->format('Y-m-d'),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Demande en attente de paiement
     */
    public function awaitingDeposit(): static
    {
        return $this->accepted()->state(fn (array $attributes) => [
            'status' => BookingRequest::STATUS_AWAITING_DEPOSIT,
            'deposit_requested_at' => now(),
        ]);
    }

    /**
     * ⭐ NOUVEAU : Demande avec design envoyé
     */
    public function designSent(): static
    {
        return $this->depositPaid()->state(fn (array $attributes) => [
            'status' => BookingRequest::STATUS_DESIGN_SENT,
            'design_sent_at' => now(),
            'design_versions_used' => 1,
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
     * Pour un tatoueur spécifique
     */
    public function forTattooer(int $tattooerId): static
    {
        return $this->state(fn (array $attributes) => [
            'tattooer_id' => $tattooerId,
        ]);
    }

    /**
     * Pour une date spécifique
     */
    public function forDate(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'preferred_date' => $date,
        ]);
    }

    /**
     * Avec un statut spécifique
     */
    public function withStatus(string $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }

    /**
     * ⭐ NOUVEAU : Demande urgente (ASAP)
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'preferred_timeframe' => 'asap',
            'preferred_date' => $this->faker->dateTimeBetween('now', '+7 days')->format('Y-m-d'),
            'preferred_time_slot' => 'anytime',
        ]);
    }
}
