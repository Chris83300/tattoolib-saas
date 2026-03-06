<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('piercers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('studio_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('pseudo')->nullable();
            $table->string('siret', 14)->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('specialization', ['pierceur', 'bodemodeur', 'pierceur_bodemodeur'])->nullable();
            $table->text('bio')->nullable();
            $table->json('styles')->nullable();
            $table->json('custom_styles')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->decimal('minimum_price', 8, 2)->nullable();
            $table->integer('wait_time_weeks_min')->nullable();
            $table->integer('wait_time_weeks_max')->nullable();
            $table->text('working_hours')->nullable();
            $table->text('aftercare_sheet')->nullable();
            $table->boolean('aftercare_reminder_2h')->default(true);
            $table->boolean('aftercare_reminder_7d')->default(true);
            $table->boolean('aftercare_reminder_14d')->default(true);
            $table->json('pricing_grid')->nullable();
            $table->string('custom_pricing_note')->nullable();
            $table->json('piercing_types')->nullable();
            $table->unsignedInteger('default_appointment_duration')->default(45);
            $table->string('city');
            $table->string('postal_code');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('subscription_plan', 20)->default('starter');
            $table->boolean('is_subscribed')->default(false);
            $table->boolean('is_blocked')->default(false);
            $table->timestamp('trial_ends_at')->nullable();
            $table->string('stripe_connect_id')->nullable();
            $table->boolean('has_compliance_badge')->default(false);
            $table->timestamp('admin_verified_at')->nullable();
            $table->boolean('siret_verified')->default(false);
            $table->boolean('is_decision_maker')->default(false);
            $table->string('compliance_status', 50)->nullable();
            $table->timestamp('last_compliance_check_at')->nullable();
            $table->string('studio_name')->nullable();
            $table->string('stripe_connect_account_id')->nullable()->unique();
            $table->boolean('stripe_onboarding_complete')->default(false);
            $table->string('stripe_connect_status')->nullable();
            $table->timestamp('stripe_connect_activated_at')->nullable();
            $table->timestamp('stripe_connect_last_transaction_at')->nullable();
            $table->timestamp('stripe_connect_deactivated_at')->nullable();
            $table->boolean('has_accepted_payment_terms')->default(false);
            $table->timestamp('payment_terms_accepted_at')->nullable();
            $table->decimal('minimum_deposit', 8, 2)->default(50.00);
            $table->decimal('default_deposit_rate', 5, 2)->default(30.00);
            $table->integer('default_client_payment_deadline_days')->default(7);
            $table->integer('default_design_versions_included')->default(3);
            $table->integer('weekday_wait_days')->default(7);
            $table->integer('weekend_wait_days')->default(14);
            $table->string('current_plan')->default('starter');
            $table->timestamp('upgraded_to_pro_at')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('studio_id');
            $table->index('siret_verified');
            $table->index('stripe_connect_status');
            $table->index(['first_name', 'last_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('piercers');
    }
};
