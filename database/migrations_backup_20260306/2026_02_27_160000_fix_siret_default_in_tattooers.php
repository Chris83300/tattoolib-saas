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
            // Rendre la colonne siret nullable pour permettre la création sans siret
            $table->string('siret', 14)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            // Remettre la colonne siret comme unique et non nullable
            $table->string('siret', 14)->unique()->change();
        });
    }
};
