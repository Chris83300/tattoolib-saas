<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            // ⭐ NOUVEAU : Préférences horaires du client
            $table->date('preferred_date')->nullable()->after('date_notes');
            $table->enum('preferred_time_slot', ['morning', 'afternoon', 'evening', 'anytime'])
                ->default('anytime')->after('preferred_date');
            $table->text('preferred_time_notes')->nullable()->after('preferred_time_slot');

            // ⭐ NOUVEAU : Heure exacte fixée par le tatoueur (après acceptation)
            $table->time('scheduled_start_time')->nullable()->after('preferred_time_notes');
            $table->time('scheduled_end_time')->nullable()->after('scheduled_start_time');
            $table->integer('scheduled_duration_minutes')->nullable()->after('scheduled_end_time');

            // ⭐ NOUVEAU : Prix total (remplace estimated_total_price)
            $table->decimal('total_price', 8, 2)->nullable()->after('scheduled_duration_minutes');

            // ⭐ NOUVEAU : Délai de paiement
            $table->timestamp('deposit_deadline')->nullable()->after('total_deposit_amount');

            // ⭐ NOUVEAU : Workflow timestamps
            $table->timestamp('accepted_at')->nullable()->after('deposit_deadline');
            $table->timestamp('deposit_requested_at')->nullable()->after('accepted_at');
            $table->timestamp('deposit_paid_at')->nullable()->after('deposit_requested_at');
            $table->timestamp('expired_at')->nullable()->after('deposit_paid_at');

            // Mettre à jour les statuts existants
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
            ])->default('pending')->change();

            // Index pour optimisation
            $table->index(['tattooer_id', 'preferred_date']);
            $table->index(['status', 'deposit_deadline']);
        });
    }

    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropColumn([
                'preferred_date',
                'preferred_time_slot',
                'preferred_time_notes',
                'scheduled_start_time',
                'scheduled_end_time',
                'scheduled_duration_minutes',
                'total_price',
                'deposit_deadline',
                'accepted_at',
                'deposit_requested_at',
                'deposit_paid_at',
                'expired_at'
            ]);

            // Revenir à l'ancien enum
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
            ])->default('pending')->change();

            $table->dropIndex(['tattooer_id', 'preferred_date']);
            $table->dropIndex(['status', 'deposit_deadline']);
        });
    }
};
