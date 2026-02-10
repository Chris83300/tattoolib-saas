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
            // Prix et acompte
            if (!Schema::hasColumn('booking_requests', 'price_estimate_min')) {
                $table->decimal('price_estimate_min', 10, 2)->nullable()->after('estimated_total_price');
            }
            if (!Schema::hasColumn('booking_requests', 'price_estimate_max')) {
                $table->decimal('price_estimate_max', 10, 2)->nullable()->after('price_estimate_min');
            }
            if (!Schema::hasColumn('booking_requests', 'deposit_amount')) {
                $table->decimal('deposit_amount', 10, 2)->nullable()->after('total_deposit_amount');
            }
            if (!Schema::hasColumn('booking_requests', 'deposit_deadline_hours')) {
                $table->unsignedInteger('deposit_deadline_hours')->default(72)->after('client_payment_deadline_days');
            }

            // Design et modifications
            if (!Schema::hasColumn('booking_requests', 'included_designs')) {
                $table->unsignedTinyInteger('included_designs')->default(1)->after('included_design_versions');
            }
            if (!Schema::hasColumn('booking_requests', 'modifications_per_design')) {
                $table->unsignedTinyInteger('modifications_per_design')->default(2)->after('included_designs');
            }

            // Dates proposées et confirmation
            if (!Schema::hasColumn('booking_requests', 'proposed_dates')) {
                $table->json('proposed_dates')->nullable()->after('preferred_time_notes');
            }
            if (!Schema::hasColumn('booking_requests', 'confirmed_date')) {
                $table->date('confirmed_date')->nullable()->after('proposed_dates');
            }
            if (!Schema::hasColumn('booking_requests', 'confirmed_period')) {
                $table->string('confirmed_period')->nullable()->after('confirmed_date'); // morning | afternoon | evening | anytime
            }

            // Message d'acceptation
            $table->text('tattooer_acceptance_message')->nullable()->after('confirmed_period');

            // Compteurs de suivi
            $table->unsignedTinyInteger('designs_sent_count')->default(0)->after('design_versions_used');
            $table->unsignedTinyInteger('current_design_modifications_count')->default(0)->after('designs_sent_count');

            // Index pour la recherche
            if (!Schema::hasIndex('booking_requests', ['confirmed_date', 'confirmed_period'])) {
                $table->index(['confirmed_date', 'confirmed_period']);
            }
            if (!Schema::hasIndex('booking_requests', 'deposit_deadline_hours')) {
                $table->index('deposit_deadline_hours');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropColumn('price_estimate_min');
            $table->dropColumn('price_estimate_max');
            $table->dropColumn('deposit_amount');
            $table->dropColumn('deposit_deadline_hours');
            $table->dropColumn('included_designs');
            $table->dropColumn('modifications_per_design');
            $table->dropColumn('proposed_dates');
            $table->dropColumn('confirmed_date');
            $table->dropColumn('confirmed_period');
            $table->dropColumn('tattooer_acceptance_message');
            $table->dropColumn('designs_sent_count');
            $table->dropColumn('current_design_modifications_count');

            $table->dropIndex(['confirmed_date', 'confirmed_period']);
            if (Schema::hasIndex('booking_requests', 'deposit_deadline_hours')) {
                $table->dropIndex('deposit_deadline_hours');
            }
        });
    }
};
