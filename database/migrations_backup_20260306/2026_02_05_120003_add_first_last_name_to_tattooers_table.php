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
            // Vérifier si les colonnes existent déjà
            if (!Schema::hasColumn('tattooers', 'first_name')) {
                $table->string('first_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('tattooers', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('tattooers', 'pseudo')) {
                $table->string('pseudo')->nullable()->after('last_name');
            }

            // Index pour optimisation
            if (!Schema::hasColumn('tattooers', 'first_name')) {
                $table->index(['first_name', 'last_name']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            $table->dropIndex(['first_name', 'last_name']);
            $table->dropColumn(['first_name', 'last_name', 'pseudo']);
        });
    }
};
