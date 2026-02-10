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
            // Champs pour la gestion des surplus
            if (!Schema::hasColumn('booking_requests', 'overage_decision')) {
                $table->string('overage_decision')->nullable(); // 'free' | 'surcharge' | 'pending'
            }

            if (!Schema::hasColumn('booking_requests', 'surcharge_amount')) {
                $table->decimal('surcharge_amount', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('booking_requests', 'surcharge_paid_at')) {
                $table->timestamp('surcharge_paid_at')->nullable();
            }

            if (!Schema::hasColumn('booking_requests', 'overage_reason')) {
                $table->text('overage_reason')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            if (Schema::hasColumn('booking_requests', 'overage_decision')) {
                $table->dropColumn('overage_decision');
            }

            if (Schema::hasColumn('booking_requests', 'surcharge_amount')) {
                $table->dropColumn('surcharge_amount');
            }

            if (Schema::hasColumn('booking_requests', 'surcharge_paid_at')) {
                $table->dropColumn('surcharge_paid_at');
            }

            if (Schema::hasColumn('booking_requests', 'overage_reason')) {
                $table->dropColumn('overage_reason');
            }
        });
    }
};
