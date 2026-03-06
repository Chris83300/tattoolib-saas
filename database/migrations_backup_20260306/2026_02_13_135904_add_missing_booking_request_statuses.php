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
            $table->enum('status', [
                'pending',
                'accepted',
                'awaiting_deposit',
                'deposit_paid',
                'design_sent',
                'date_confirmed',
                'confirmed',
                'completed',
                'rejected',
                'expired',
                'cancelled',
                'no_show'
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'accepted',
                'awaiting_deposit',
                'deposit_paid',
                'design_sent',
                'date_confirmed',
                'confirmed',
                'rejected',
                'expired',
                'cancelled'
            ])->default('pending')->change();
        });
    }
};
