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
            if (Schema::hasColumn('tattooers', 'wait_time_days')) {
                $table->dropColumn('wait_time_days');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            $table->integer('wait_time_days')->nullable()->default(7)->after('bio')->comment('Délai d\'attente en jours');
        });
    }
};
