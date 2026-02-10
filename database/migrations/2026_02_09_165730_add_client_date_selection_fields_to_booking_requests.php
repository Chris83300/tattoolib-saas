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
            $table->json('client_selected_dates')->nullable()->after('proposed_dates');
            // Format: [{"date": "2026-03-15", "period": "morning"}, {"date": "2026-03-20", "period": "afternoon"}]

            $table->timestamp('date_selection_deadline')->nullable()->after('client_selected_dates');
            // Délai pour le client pour choisir ses dates

            $table->timestamp('client_dates_selected_at')->nullable()->after('date_selection_deadline');
            // Quand le client a validé sa sélection
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropColumn([
                'client_selected_dates',
                'date_selection_deadline',
                'client_dates_selected_at'
            ]);
        });
    }
};
