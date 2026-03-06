<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            // Plan actuel (dénormalisé pour perfs)
            $table->enum('current_plan', ['free', 'pro'])
                ->default('free')
                ->after('stripe_connect_account_id')
                ->comment('Plan actuel (dénormalisé pour requêtes rapides)');

            // Cache statut abonnement
            $table->boolean('is_subscribed')
                ->default(false)
                ->after('current_plan')
                ->comment('True si abonnement PRO actif');

            // Date upgrade PRO (analytics)
            $table->timestamp('upgraded_to_pro_at')->nullable()
                ->after('is_subscribed')
                ->comment('Date premier upgrade PRO');
        });
    }

    public function down(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            $table->dropColumn([
                'current_plan',
                'is_subscribed',
                'upgraded_to_pro_at',
            ]);
        });
    }
};
