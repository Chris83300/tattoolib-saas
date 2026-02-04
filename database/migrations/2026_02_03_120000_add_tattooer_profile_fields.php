<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tattooers', function (Blueprint $table) {
            if (!Schema::hasColumn('tattooers', 'experience_years')) {
                $table->integer('experience_years')->nullable()->after('bio')->comment('Années d\'expérience');
            }
            
            if (!Schema::hasColumn('tattooers', 'wait_time_days')) {
                $table->integer('wait_time_days')->nullable()->default(7)->after('experience_years')->comment('Délai d\'attente en jours');
            }
            
            if (!Schema::hasColumn('tattooers', 'price_from')) {
                $table->decimal('price_from', 10, 2)->nullable()->after('wait_time_days')->comment('Prix à partir de');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tattooers', function (Blueprint $table) {
            if (Schema::hasColumn('tattooers', 'experience_years')) {
                $table->dropColumn('experience_years');
            }
            
            if (Schema::hasColumn('tattooers', 'wait_time_days')) {
                $table->dropColumn('wait_time_days');
            }
            
            if (Schema::hasColumn('tattooers', 'price_from')) {
                $table->dropColumn('price_from');
            }
        });
    }
};
