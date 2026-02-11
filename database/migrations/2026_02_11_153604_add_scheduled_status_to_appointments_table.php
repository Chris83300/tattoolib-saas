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
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', [
                'scheduled',
                'confirmed',
                'completed',
                'cancelled',
                'client_no_show',
                'tattooer_no_show',
                'disputed'
            ])->default('confirmed')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', [
                'confirmed',
                'completed',
                'cancelled',
                'client_no_show',
                'tattooer_no_show',
                'disputed'
            ])->default('confirmed')->change();
        });
    }
};
