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
            $table->text('aftercare_sheet')->nullable();
            $table->boolean('aftercare_reminder_2h')->default(true);
            $table->boolean('aftercare_reminder_7d')->default(true);
            $table->boolean('aftercare_reminder_14d')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            $table->dropColumn([
                'aftercare_sheet',
                'aftercare_reminder_2h',
                'aftercare_reminder_7d',
                'aftercare_reminder_14d'
            ]);
        });
    }
};
