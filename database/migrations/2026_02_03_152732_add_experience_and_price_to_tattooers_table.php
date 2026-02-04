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
            $table->integer('years_of_experience')->nullable()->after('styles');
            $table->decimal('minimum_price', 8, 2)->nullable()->after('years_of_experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            $table->dropColumn(['years_of_experience', 'minimum_price']);
        });
    }
};
