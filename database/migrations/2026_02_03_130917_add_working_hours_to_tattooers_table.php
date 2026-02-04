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
            if (!Schema::hasColumn('tattooers', 'working_hours')) {
                $table->json('working_hours')->nullable()->after('price_from')->comment('Horaires de travail avec créneaux multiples');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            if (Schema::hasColumn('tattooers', 'working_hours')) {
                $table->dropColumn('working_hours');
            }
        });
    }
};
