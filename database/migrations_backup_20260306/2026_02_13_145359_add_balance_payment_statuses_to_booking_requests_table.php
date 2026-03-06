<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'accepted',
                'deposit_requested',
                'deposit_paid',
                'date_confirmed',
                'completed',
                'balance_paid',
                'balance_paid_offline',
                'fully_completed',
                'rejected',
                'cancelled',
                'expired',
                'no_show'
            ])->change();
        });
    }

    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'accepted',
                'deposit_requested',
                'deposit_paid',
                'date_confirmed',
                'completed',
                'rejected',
                'cancelled',
                'expired',
                'no_show'
            ])->change();
        });
    }
};
