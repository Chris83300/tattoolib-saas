<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_requests', function (Blueprint $table) {
            $table->id();
            // Relations
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');

            // Relations polymorphiques (Tattooer ou StudioArtist)
            $table->morphs('bookable');

            // Infos demande
            $table->string('tattoo_size');
            $table->string('body_zone');
            $table->text('description');
            $table->decimal('estimated_budget', 8, 2)->nullable();

            // Préférences date
            $table->enum('preferred_timeframe', [
                'asap', '3-4months', '5-6months', '6plus'
            ])->nullable();
            $table->json('preferred_days')->nullable();
            $table->text('date_notes')->nullable();

            // ⭐ NOUVEAU : Champ preferred_date pour compatibilité
            $table->date('preferred_date')->nullable();
            $table->enum('preferred_time_slot', ['morning', 'afternoon', 'evening', 'anytime'])->nullable();
            $table->text('preferred_time_notes')->nullable();

            // Montants
            $table->decimal('total_deposit_amount', 8, 2)->nullable();
            $table->decimal('estimated_total_price', 8, 2)->nullable();

            // Délais (en jours)
            $table->integer('client_payment_deadline_days')->default(7);
            $table->integer('tattooer_design_deadline_days')->default(7);

            // Timestamps calculés
            $table->timestamp('client_payment_deadline')->nullable();
            $table->timestamp('tattooer_design_deadline')->nullable();
            $table->timestamp('design_sent_at')->nullable();

            // ⭐ NOUVEAU : Champ deposit_deadline pour compatibilité
            $table->timestamp('deposit_deadline')->nullable();

            // Gestion dessin long délai
            $table->boolean('is_long_term_booking')->default(false);
            $table->timestamp('design_preparation_starts_at')->nullable(); // J-21
            $table->boolean('design_preparation_notified')->default(false);

            // Versions dessin
            $table->integer('included_design_versions')->default(3);
            $table->integer('design_versions_used')->default(0);

            // Stripe
            $table->string('stripe_payment_intent_id')->nullable()->unique();

            // Statuts
            $table->enum('status', [
                'pending',
                'accepted',
                'awaiting_deposit',
                'deposit_paid',
                'design_sent',
                'confirmed',
                'rejected',
                'expired',
                'cancelled'
            ])->default('pending');

            // ⭐ NOUVEAU : Champ expired_at pour compatibilité
            $table->timestamp('expired_at')->nullable();

            // ⭐ NOUVEAU : Champ accepted_at pour compatibilité
            $table->timestamp('accepted_at')->nullable();

            // ⭐ NOUVEAU : Champs scheduled pour compatibilité
            $table->time('scheduled_start_time')->nullable();
            $table->time('scheduled_end_time')->nullable();
            $table->integer('scheduled_duration_minutes')->nullable();

            // ⭐ NOUVEAU : Champ total_price pour compatibilité
            $table->decimal('total_price', 8, 2)->nullable();

            // Flags
            $table->boolean('tattooer_missed_deadline')->default(false);
            $table->boolean('client_missed_deadline')->default(false);

            // Date/heure RDV
            $table->timestamp('appointment_datetime')->nullable();
            $table->integer('appointment_duration_minutes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_requests');
    }
};
