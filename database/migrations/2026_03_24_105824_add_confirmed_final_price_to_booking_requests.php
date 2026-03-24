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
            $table->decimal('confirmed_final_price', 10, 2)->nullable()->after('balance_requested_at');
            $table->boolean('final_price_confirmed')->default(false)->after('confirmed_final_price');
            $table->timestamp('final_price_confirmed_at')->nullable()->after('final_price_confirmed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropColumn(['confirmed_final_price', 'final_price_confirmed', 'final_price_confirmed_at']);
        });
    }
};
