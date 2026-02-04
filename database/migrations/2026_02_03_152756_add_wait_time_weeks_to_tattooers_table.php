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
        Schema::table('tattooers', function (Blueprint $table) {
            $table->integer('wait_time_weeks_min')->nullable()->after('minimum_price');
            $table->integer('wait_time_weeks_max')->nullable()->after('wait_time_weeks_min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            $table->dropColumn(['wait_time_weeks_min', 'wait_time_weeks_max']);
        });
    }
};
