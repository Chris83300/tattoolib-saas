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
            $table->string('custom_pricing_note')->nullable()->after('pricing_grid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('piercers', function (Blueprint $table) {
            $table->dropColumn('custom_pricing_note');
        });
    }
};
