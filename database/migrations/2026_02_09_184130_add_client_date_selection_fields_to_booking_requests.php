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
            if (!Schema::hasColumn('booking_requests', 'client_selected_dates')) {
                $table->json('client_selected_dates')->nullable()->after('proposed_dates');
            }
            if (!Schema::hasColumn('booking_requests', 'date_selection_deadline')) {
                $table->timestamp('date_selection_deadline')->nullable()->after('client_selected_dates');
            }
            if (!Schema::hasColumn('booking_requests', 'client_dates_selected_at')) {
                $table->timestamp('client_dates_selected_at')->nullable()->after('date_selection_deadline');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            if (Schema::hasColumn('booking_requests', 'client_selected_dates')) {
                $table->dropColumn('client_selected_dates');
            }
            if (Schema::hasColumn('booking_requests', 'date_selection_deadline')) {
                $table->dropColumn('date_selection_deadline');
            }
            if (Schema::hasColumn('booking_requests', 'client_dates_selected_at')) {
                $table->dropColumn('client_dates_selected_at');
            }
        });
    }
};
