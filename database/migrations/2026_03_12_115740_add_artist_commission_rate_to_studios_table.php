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
        Schema::table('studios', function (Blueprint $table) {
            $table->decimal('artist_commission_rate', 5, 2)
                  ->nullable()
                  ->default(null)
                  ->after('payment_mode')
                  ->comment('% prélevé par le studio sur les transactions de ses artistes. null = aucune commission');
        });
    }

    public function down(): void
    {
        Schema::table('studios', function (Blueprint $table) {
            $table->dropColumn('artist_commission_rate');
        });
    }
};
