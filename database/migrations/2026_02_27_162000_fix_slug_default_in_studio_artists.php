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
        Schema::table('studio_artists', function (Blueprint $table) {
            // Rendre la colonne slug nullable pour permettre la création sans slug
            $table->string('slug')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('studio_artists', function (Blueprint $table) {
            // Remettre la colonne slug comme non nullable
            $table->string('slug')->change();
        });
    }
};
