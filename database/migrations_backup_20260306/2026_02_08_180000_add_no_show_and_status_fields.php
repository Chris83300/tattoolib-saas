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
        Schema::table('clients', function (Blueprint $table) {
            // Ajouter le compteur de no-show
            if (!Schema::hasColumn('clients', 'no_show_count')) {
                $table->unsignedTinyInteger('no_show_count')->default(0)->after('email');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            // Ajouter le statut de l'utilisateur
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('is_active');
            }
            
            // Ajouter les champs de bannissement
            if (!Schema::hasColumn('users', 'banned_at')) {
                $table->timestamp('banned_at')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('users', 'banned_reason')) {
                $table->string('banned_reason')->nullable()->after('banned_at');
            }
            
            if (!Schema::hasColumn('users', 'unbanned_at')) {
                $table->timestamp('unbanned_at')->nullable()->after('banned_reason');
            }
            
            if (!Schema::hasColumn('users', 'unbanned_reason')) {
                $table->string('unbanned_reason')->nullable()->after('unbanned_at');
            }
            
            if (!Schema::hasColumn('users', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('unbanned_reason');
            }
            
            if (!Schema::hasColumn('users', 'suspended_reason')) {
                $table->string('suspended_reason')->nullable()->after('suspended_at');
            }
        });

        Schema::table('appointments', function (Blueprint $table) {
            // Ajouter les champs de no-show
            if (!Schema::hasColumn('appointments', 'no_show_reported_at')) {
                $table->timestamp('no_show_reported_at')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('appointments', 'no_show_reason')) {
                $table->string('no_show_reason')->nullable()->after('no_show_reported_at');
            }
            
            // Ajouter les champs d'absence du tattooer
            if (!Schema::hasColumn('appointments', 'tattooer_absence_reported_at')) {
                $table->timestamp('tattooer_absence_reported_at')->nullable()->after('no_show_reason');
            }
            
            if (!Schema::hasColumn('appointments', 'tattooer_absence_reason')) {
                $table->string('tattooer_absence_reason')->nullable()->after('tattooer_absence_reported_at');
            }
            
            // Ajouter le champ de fin réelle
            if (!Schema::hasColumn('appointments', 'actual_end_time')) {
                $table->timestamp('actual_end_time')->nullable()->after('tattooer_absence_reason');
            }
        });

        Schema::table('booking_requests', function (Blueprint $table) {
            // Ajouter les champs de remboursement
            if (!Schema::hasColumn('booking_requests', 'refund_amount')) {
                $table->decimal('refund_amount', 10, 2)->nullable()->after('total_price');
            }
            
            if (!Schema::hasColumn('booking_requests', 'refund_processed_at')) {
                $table->timestamp('refund_processed_at')->nullable()->after('refund_amount');
            }
        });

        // Ajouter les index pour optimiser les requêtes
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasIndex('clients', ['no_show_count'])) {
                $table->index(['no_show_count']);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasIndex('users', ['status'])) {
                $table->index(['status']);
            }
            
            if (!Schema::hasIndex('users', ['banned_at'])) {
                $table->index(['banned_at']);
            }
            
            if (!Schema::hasIndex('users', ['suspended_at'])) {
                $table->index(['suspended_at']);
            }
        });

        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasIndex('appointments', ['no_show_reported_at'])) {
                $table->index(['no_show_reported_at']);
            }
            
            if (!Schema::hasIndex('appointments', ['tattooer_absence_reported_at'])) {
                $table->index(['tattooer_absence_reported_at']);
            }
        });

        Schema::table('booking_requests', function (Blueprint $table) {
            if (!Schema::hasIndex('booking_requests', ['refund_processed_at'])) {
                $table->index(['refund_processed_at']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'no_show_count')) {
                $table->dropColumn('no_show_count');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
            
            if (Schema::hasColumn('users', 'banned_at')) {
                $table->dropColumn('banned_at');
            }
            
            if (Schema::hasColumn('users', 'banned_reason')) {
                $table->dropColumn('banned_reason');
            }
            
            if (Schema::hasColumn('users', 'unbanned_at')) {
                $table->dropColumn('unbanned_at');
            }
            
            if (Schema::hasColumn('users', 'unbanned_reason')) {
                $table->dropColumn('unbanned_reason');
            }
            
            if (Schema::hasColumn('users', 'suspended_at')) {
                $table->dropColumn('suspended_at');
            }
            
            if (Schema::hasColumn('users', 'suspended_reason')) {
                $table->dropColumn('suspended_reason');
            }
        });

        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'no_show_reported_at')) {
                $table->dropColumn('no_show_reported_at');
            }
            
            if (Schema::hasColumn('appointments', 'no_show_reason')) {
                $table->dropColumn('no_show_reason');
            }
            
            if (Schema::hasColumn('appointments', 'tattooer_absence_reported_at')) {
                $table->dropColumn('tattooer_absence_reported_at');
            }
            
            if (Schema::hasColumn('appointments', 'tattooer_absence_reason')) {
                $table->dropColumn('tattooer_absence_reason');
            }
            
            if (Schema::hasColumn('appointments', 'actual_end_time')) {
                $table->dropColumn('actual_end_time');
            }
        });

        Schema::table('booking_requests', function (Blueprint $table) {
            if (Schema::hasColumn('booking_requests', 'refund_amount')) {
                $table->dropColumn('refund_amount');
            }
            
            if (Schema::hasColumn('booking_requests', 'refund_processed_at')) {
                $table->dropColumn('refund_processed_at');
            }
        });
    }
};
