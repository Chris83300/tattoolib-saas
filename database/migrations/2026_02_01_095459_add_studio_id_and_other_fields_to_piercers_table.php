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
        Schema::table('piercers', function (Blueprint $table) {
            // Ajouter studio_id pour les relations polymorphiques
            $table->foreignId('studio_id')->nullable()->constrained()->onDelete('cascade');

            // Ajouter siret_verified pour la vérification
            $table->boolean('siret_verified')->default(false);

            // Ajouter studio_name pour les indépendants
            $table->string('studio_name')->nullable();

            // Ajouter les champs Stripe Connect
            $table->string('stripe_connect_account_id')->nullable();
            $table->boolean('stripe_onboarding_complete')->default(false);
            $table->string('stripe_connect_status')->nullable();
            $table->timestamp('stripe_connect_activated_at')->nullable();
            $table->timestamp('stripe_connect_last_transaction_at')->nullable();
            $table->timestamp('stripe_connect_deactivated_at')->nullable();
            $table->boolean('has_accepted_payment_terms')->default(false);
            $table->timestamp('payment_terms_accepted_at')->nullable();

            // Ajouter les champs de configuration
            $table->decimal('minimum_deposit', 8, 2)->default(50);
            $table->decimal('default_deposit_rate', 5, 2)->default(30);
            $table->integer('default_client_payment_deadline_days')->default(7);
            $table->integer('default_design_versions_included')->default(3);
            $table->integer('weekday_wait_days')->default(7);
            $table->integer('weekend_wait_days')->default(14);

            // Ajouter les champs de plan d'abonnement
            $table->string('current_plan')->default('free');
            $table->timestamp('upgraded_to_pro_at')->nullable();

            // Ajouter les réseaux sociaux
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('website')->nullable();

            // Index pour les performances
            $table->index(['studio_id']);
            $table->index(['siret_verified']);
            $table->index(['stripe_connect_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('piercers', function (Blueprint $table) {
            $table->dropForeign(['studio_id']);
            $table->dropIndex(['studio_id']);
            $table->dropColumn([
                'studio_id',
                'siret_verified',
                'studio_name',
                'stripe_connect_account_id',
                'stripe_onboarding_complete',
                'stripe_connect_status',
                'stripe_connect_activated_at',
                'stripe_connect_last_transaction_at',
                'stripe_connect_deactivated_at',
                'has_accepted_payment_terms',
                'payment_terms_accepted_at',
                'minimum_deposit',
                'default_deposit_rate',
                'default_client_payment_deadline_days',
                'default_design_versions_included',
                'weekday_wait_days',
                'weekend_wait_days',
                'current_plan',
                'upgraded_to_pro_at',
                'instagram',
                'facebook',
                'tiktok',
                'website'
            ]);
        });
    }
};
