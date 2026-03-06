<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('studios', function (Blueprint $table) {
            // ===========================================
            // MODE PAIEMENT
            // ===========================================
            $table->enum('payment_mode', [
                'artist_direct',    // Chaque artiste encaisse (défaut)
                'studio_managed'    // Studio encaisse tout
            ])->default('artist_direct')
                ->after('verified_at')
                ->comment('Mode paiement : direct artiste ou centralisé studio');

            // ===========================================
            // MODULE COMPTABILITÉ INTERNE (OPTIONNEL)
            // ===========================================
            $table->boolean('uses_accounting_module')->default(false)
                ->after('payment_mode')
                ->comment('True si studio utilise module compta interne');

            // ===========================================
            // HISTORIQUE
            // ===========================================
            $table->timestamp('payment_mode_changed_at')->nullable()
                ->after('uses_accounting_module')
                ->comment('Date dernier changement mode paiement');

            // Index
            $table->index('payment_mode');
        });
    }

    public function down(): void
    {
        Schema::table('studios', function (Blueprint $table) {
            $table->dropColumn([
                'payment_mode',
                'uses_accounting_module',
                'payment_mode_changed_at',
            ]);
        });
    }
};
