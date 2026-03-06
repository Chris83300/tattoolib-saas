<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            // ===========================================
            // ÉTAT STRIPE CONNECT
            // ===========================================
            $table->enum('stripe_connect_status', [
                'not_connected',    // Pas de compte créé
                'onboarding',       // En cours onboarding
                'active',           // Actif (facturable 2€/mois)
                'inactive',         // Inactif (0€/mois)
                'reactivating'      // En cours réactivation
            ])->default('not_connected')
                ->after('stripe_connect_account_id')
                ->comment('État activation Stripe Connect');

            // ===========================================
            // DATES CRITIQUES
            // ===========================================
            $table->timestamp('stripe_connect_activated_at')->nullable()
                ->after('stripe_connect_status')
                ->comment('Date activation Connect (début facturation 2€)');

            $table->timestamp('stripe_connect_last_transaction_at')->nullable()
                ->after('stripe_connect_activated_at')
                ->comment('Dernière transaction encaissée');

            $table->timestamp('stripe_connect_deactivated_at')->nullable()
                ->after('stripe_connect_last_transaction_at')
                ->comment('Date désactivation (fin facturation 2€)');

            // ===========================================
            // ACCEPTATIONS LÉGALES
            // ===========================================
            $table->boolean('has_accepted_payment_terms')->default(false)
                ->after('stripe_connect_deactivated_at')
                ->comment('CGU paiements acceptées');

            $table->timestamp('payment_terms_accepted_at')->nullable()
                ->after('has_accepted_payment_terms')
                ->comment('Date acceptation CGU');

            // Index
            $table->index('stripe_connect_status');
            $table->index('stripe_connect_last_transaction_at');
        });

        // Idem pour StudioArtist
        Schema::table('studio_artists', function (Blueprint $table) {
            $table->enum('stripe_connect_status', [
                'not_connected',
                'onboarding',
                'active',
                'inactive',
                'reactivating'
            ])->default('not_connected')
                ->after('stripe_connect_account_id');

            $table->timestamp('stripe_connect_activated_at')->nullable()
                ->after('stripe_connect_status');

            $table->timestamp('stripe_connect_last_transaction_at')->nullable()
                ->after('stripe_connect_activated_at');

            $table->timestamp('stripe_connect_deactivated_at')->nullable()
                ->after('stripe_connect_last_transaction_at');

            $table->boolean('has_accepted_payment_terms')->default(false)
                ->after('stripe_connect_deactivated_at');

            $table->timestamp('payment_terms_accepted_at')->nullable()
                ->after('has_accepted_payment_terms');

            $table->index('stripe_connect_status');
            $table->index('stripe_connect_last_transaction_at');
        });
    }

    public function down(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_connect_status',
                'stripe_connect_activated_at',
                'stripe_connect_last_transaction_at',
                'stripe_connect_deactivated_at',
                'has_accepted_payment_terms',
                'payment_terms_accepted_at',
            ]);
        });

        Schema::table('studio_artists', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_connect_status',
                'stripe_connect_activated_at',
                'stripe_connect_last_transaction_at',
                'stripe_connect_deactivated_at',
                'has_accepted_payment_terms',
                'payment_terms_accepted_at',
            ]);
        });
    }
};
