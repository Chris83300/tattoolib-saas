<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tattooer_subscriptions', function (Blueprint $table) {
            $table->id();

            // ===========================================
            // POLYMORPHIC (Tattooer OU Studio)
            // ===========================================
            $table->morphs('subscribable');

            // ===========================================
            // PLAN
            // ===========================================
            $table->enum('plan', ['free', 'pro', 'studio'])
                ->default('free')
                ->comment('free=7% commission, pro=49.99€/mois, studio=79.99€+39.99€/artiste');

            // ===========================================
            // STATUT
            // ===========================================
            $table->enum('status', [
                'active',      // Actif
                'past_due',    // Paiement échoué
                'canceled',    // Annulé
                'unpaid'       // Impayé
            ])->default('active');

            // ===========================================
            // STRIPE BILLING (Plan PRO uniquement)
            // ===========================================
            $table->string('stripe_subscription_id')->nullable()->unique()
                ->comment('ID abonnement Stripe (null pour FREE)');

            $table->string('stripe_price_id')->nullable()
                ->comment('ID prix Stripe');

            // ===========================================
            // DATES (Plan PRO uniquement)
            // ===========================================
            $table->timestamp('current_period_start')->nullable()
                ->comment('Début période facturation');

            $table->timestamp('current_period_end')->nullable()
                ->comment('Fin période facturation');

            $table->timestamp('canceled_at')->nullable()
                ->comment('Date annulation');

            $table->timestamp('ends_at')->nullable()
                ->comment('Fin effective abonnement');

            // ===========================================
            // PRICING
            // ===========================================
            $table->decimal('price_monthly', 8, 2)->nullable()
                ->comment('Prix mensuel en euros (49.99 pour PRO, null pour FREE)');

            // ===========================================
            // COMMISSION (Plan FREE uniquement)
            // ===========================================
            $table->decimal('commission_rate', 5, 2)->default(7.00)
                ->comment('Taux commission en % (7.00 pour FREE, 0.00 pour PRO)');

            // ===========================================
            // FEATURES
            // ===========================================
            $table->json('features')->nullable()
                ->comment('Features activées selon plan');

            // ===========================================
            // AUDIT
            // ===========================================
            $table->timestamps();
            $table->softDeletes();

            // ===========================================
            // INDEX
            // ===========================================
            $table->index(['subscribable_type', 'subscribable_id'], 'subscribable_index');
            $table->index('status');
            $table->index('plan');
            $table->index('stripe_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tattooer_subscriptions');
    }
};
