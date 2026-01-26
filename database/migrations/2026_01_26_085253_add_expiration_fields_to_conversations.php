<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            // ===========================================
            // TYPE D'EXPIRATION
            // ===========================================
            $table->enum('expiry_type', [
                'deposit_pending',    // Phase 1 : Avant acompte
                'permanent',          // Phase 2 : Après acompte
                'post_appointment',   // Phase 3 : Après RDV
                'archived'            // Archivé (plan PRO)
            ])->default('deposit_pending')
                ->after('booking_request_id')
                ->comment('Type expiration selon cycle booking');

            // ===========================================
            // DATES CRITIQUES
            // ===========================================
            $table->timestamp('deposit_deadline_at')->nullable()
                ->after('expiry_type')
                ->comment('Date limite paiement acompte (Phase 1)');

            $table->timestamp('appointment_completed_at')->nullable()
                ->after('deposit_deadline_at')
                ->comment('Date fin RDV (déclenche Phase 3)');

            $table->timestamp('expires_at')->nullable()
                ->after('appointment_completed_at')
                ->comment('Date expiration définitive');

            $table->timestamp('archived_at')->nullable()
                ->after('expires_at')
                ->comment('Date archivage (plan PRO uniquement)');

            // ===========================================
            // FLAGS
            // ===========================================
            $table->boolean('is_expired')->default(false)
                ->after('archived_at')
                ->comment('True si conversation expirée');

            $table->boolean('images_preserved')->default(false)
                ->after('is_expired')
                ->comment('True si images conservées (plan PRO)');

            // ===========================================
            // NOTIFICATIONS
            // ===========================================
            $table->timestamp('expiry_warning_sent_at')->nullable()
                ->after('images_preserved')
                ->comment('Alerte envoyée J-2 avant expiration');
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn([
                'expiry_type',
                'deposit_deadline_at',
                'appointment_completed_at',
                'expires_at',
                'archived_at',
                'is_expired',
                'images_preserved',
                'expiry_warning_sent_at',
            ]);
        });
    }
};
