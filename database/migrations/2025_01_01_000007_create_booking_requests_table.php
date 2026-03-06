<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            // Polymorphique — bookable_type/bookable_id (tattooer ou piercer) — pas de FK SQL
            $table->string('bookable_type');
            $table->unsignedBigInteger('bookable_id');
            $table->string('tattoo_size');
            $table->string('body_zone');
            $table->string('tattoo_style')->nullable();
            $table->text('description');
            $table->text('tattooer_notes')->nullable();
            $table->decimal('estimated_price', 10, 2)->nullable();
            $table->decimal('estimated_budget', 8, 2)->nullable();
            $table->enum('preferred_timeframe', ['asap', '3-4months', '5-6months', '6plus'])->nullable();
            $table->json('preferred_days')->nullable();
            $table->text('date_notes')->nullable();
            $table->date('preferred_date')->nullable();
            $table->enum('preferred_time_slot', ['morning', 'afternoon', 'evening', 'anytime'])->nullable();
            $table->text('preferred_time_notes')->nullable();
            $table->json('proposed_dates')->nullable();
            $table->json('client_selected_dates')->nullable();
            $table->timestamp('date_selection_deadline')->nullable();
            $table->timestamp('client_dates_selected_at')->nullable();
            $table->date('confirmed_date')->nullable();
            $table->string('confirmed_period')->nullable();
            $table->text('tattooer_acceptance_message')->nullable();
            $table->decimal('total_deposit_amount', 8, 2)->nullable();
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->decimal('estimated_total_price', 8, 2)->nullable();
            $table->decimal('price_estimate_min', 10, 2)->nullable();
            $table->decimal('price_estimate_max', 10, 2)->nullable();
            $table->integer('client_payment_deadline_days')->default(7);
            $table->unsignedInteger('deposit_deadline_hours')->default(72);
            $table->integer('tattooer_design_deadline_days')->default(7);
            $table->timestamp('client_payment_deadline')->nullable();
            $table->timestamp('tattooer_design_deadline')->nullable();
            $table->timestamp('design_sent_at')->nullable();
            $table->timestamp('deposit_deadline')->nullable();
            $table->boolean('is_long_term_booking')->default(false);
            $table->timestamp('design_preparation_starts_at')->nullable();
            $table->boolean('design_preparation_notified')->default(false);
            $table->integer('included_design_versions')->default(3);
            $table->unsignedTinyInteger('included_designs')->default(1);
            $table->unsignedTinyInteger('modifications_per_design')->default(2);
            $table->integer('design_versions_used')->default(0);
            $table->unsignedTinyInteger('designs_sent_count')->default(0);
            $table->json('design_modifications_tracker')->nullable();
            $table->unsignedTinyInteger('current_design_modifications_count')->default(0);
            $table->string('stripe_payment_intent_id')->nullable()->unique();
            $table->enum('status', [
                'pending', 'accepted', 'deposit_requested', 'deposit_paid', 'date_confirmed',
                'completed', 'balance_paid', 'balance_paid_offline', 'fully_completed',
                'rejected', 'cancelled', 'expired', 'no_show',
            ]);
            $table->timestamp('deposit_paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->time('scheduled_start_time')->nullable();
            $table->time('scheduled_end_time')->nullable();
            $table->integer('scheduled_duration_minutes')->nullable();
            $table->decimal('total_price', 8, 2)->nullable();
            $table->decimal('balance_amount', 10, 2)->nullable();
            $table->timestamp('balance_paid_at')->nullable();
            $table->string('balance_payment_method')->nullable();
            $table->string('balance_stripe_session_id')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->unsignedTinyInteger('refund_percent')->nullable();
            $table->timestamp('refund_processed_at')->nullable();
            $table->boolean('tattooer_missed_deadline')->default(false);
            $table->boolean('client_missed_deadline')->default(false);
            $table->timestamp('appointment_datetime')->nullable();
            $table->integer('appointment_duration_minutes')->nullable();
            $table->string('overage_decision')->nullable();
            $table->decimal('surcharge_amount', 10, 2)->nullable();
            $table->timestamp('surcharge_paid_at')->nullable();
            $table->text('overage_reason')->nullable();
            $table->string('cancelled_by')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['bookable_type', 'bookable_id']);
            $table->index(['client_id', 'bookable_type', 'bookable_id']);
            $table->index('client_payment_deadline');
            $table->index('tattooer_design_deadline');
            $table->index('status');
            $table->index(['confirmed_date', 'confirmed_period']);
            $table->index('deposit_deadline_hours');
            $table->index('refund_processed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_requests');
    }
};
