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
        Schema::table('booking_requests', function (Blueprint $table) {
            // Ajouter les colonnes manquantes
            if (!Schema::hasColumn('booking_requests', 'deposit_deadline_hours')) {
                $table->unsignedInteger('deposit_deadline_hours')->default(72)->after('client_payment_deadline_days');
            }

            if (!Schema::hasColumn('booking_requests', 'included_designs')) {
                $table->unsignedTinyInteger('included_designs')->default(1)->after('included_design_versions');
            }

            if (!Schema::hasColumn('booking_requests', 'confirmed_date')) {
                $table->date('confirmed_date')->nullable()->after('proposed_dates');
            }

            if (!Schema::hasColumn('booking_requests', 'confirmed_period')) {
                $table->string('confirmed_period')->nullable()->after('confirmed_date'); // morning | afternoon | evening | anytime
            }

            if (!Schema::hasColumn('booking_requests', 'tattooer_acceptance_message')) {
                $table->text('tattooer_acceptance_message')->nullable()->after('confirmed_period');
            }

            if (!Schema::hasColumn('booking_requests', 'designs_sent_count')) {
                $table->unsignedTinyInteger('designs_sent_count')->default(0)->after('design_versions_used');
            }

            if (!Schema::hasColumn('booking_requests', 'current_design_modifications_count')) {
                $table->unsignedTinyInteger('current_design_modifications_count')->default(0)->after('designs_sent_count');
            }

            // Index pour la recherche
            if (!Schema::hasIndex('booking_requests', ['confirmed_date', 'confirmed_period'])) {
                $table->index(['confirmed_date', 'confirmed_period']);
            }
            // L'index deposit_deadline_hours est déjà créé par la migration 110000
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            if (Schema::hasColumn('booking_requests', 'deposit_deadline_hours')) {
                $table->dropColumn('deposit_deadline_hours');
            }

            if (Schema::hasColumn('booking_requests', 'included_designs')) {
                $table->dropColumn('included_designs');
            }

            if (Schema::hasColumn('booking_requests', 'confirmed_date')) {
                $table->dropColumn('confirmed_date');
            }

            if (Schema::hasColumn('booking_requests', 'confirmed_period')) {
                $table->dropColumn('confirmed_period');
            }

            if (Schema::hasColumn('booking_requests', 'tattooer_acceptance_message')) {
                $table->dropColumn('tattooer_acceptance_message');
            }

            if (Schema::hasColumn('booking_requests', 'designs_sent_count')) {
                $table->dropColumn('designs_sent_count');
            }

            if (Schema::hasColumn('booking_requests', 'current_design_modifications_count')) {
                $table->dropColumn('current_design_modifications_count');
            }

            if (Schema::hasIndex('booking_requests', ['confirmed_date', 'confirmed_period'])) {
                $table->dropIndex(['confirmed_date', 'confirmed_period']);
            }
            // L'index deposit_deadline_hours est géré par la migration 110000
        });
    }
};
