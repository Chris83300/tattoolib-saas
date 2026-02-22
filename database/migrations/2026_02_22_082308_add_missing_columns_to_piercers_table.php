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
        Schema::table('piercers', function (Blueprint $table) {
            $table->json('styles')->nullable()->after('bio');
            $table->json('custom_styles')->nullable()->after('styles');
            $table->integer('years_of_experience')->nullable()->after('custom_styles');
            $table->decimal('minimum_price', 8, 2)->nullable()->after('years_of_experience');
            $table->integer('wait_time_weeks_min')->nullable()->after('minimum_price');
            $table->integer('wait_time_weeks_max')->nullable()->after('wait_time_weeks_min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('piercers', function (Blueprint $table) {
            $table->dropColumn([
                'styles',
                'custom_styles',
                'years_of_experience',
                'minimum_price',
                'wait_time_weeks_min',
                'wait_time_weeks_max',
            ]);
        });
    }
};
