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
        Schema::table('clients', function (Blueprint $table) {
            // Vérifier si les colonnes existent déjà
            if (!Schema::hasColumn('clients', 'first_name')) {
                $table->string('first_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('clients', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('clients', 'pseudo')) {
                // Renommer 'name' en 'pseudo' pour clarté
                if (Schema::hasColumn('clients', 'name')) {
                    $table->renameColumn('name', 'pseudo');
                }
            }

            // Index pour optimisation
            if (!Schema::hasColumn('clients', 'first_name')) {
                $table->index(['first_name', 'last_name']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex(['first_name', 'last_name']);
            $table->dropColumn(['first_name', 'last_name']);
            $table->renameColumn('pseudo', 'name');
        });
    }
};
