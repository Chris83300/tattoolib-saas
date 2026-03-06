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
        Schema::table('messages', function (Blueprint $table) {
            // Ajouter les colonnes de lecture pour le système de messagerie
            $table->timestamp('read_by_tattooer_at')->nullable()->after('content');
            $table->timestamp('read_by_client_at')->nullable()->after('read_by_tattooer_at');
            
            // Index pour optimiser les requêtes de lecture
            $table->index('read_by_tattooer_at');
            $table->index('read_by_client_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['read_by_tattooer_at']);
            $table->dropIndex(['read_by_client_at']);
            $table->dropColumn('read_by_client_at');
            $table->dropColumn('read_by_tattooer_at');
        });
    }
};
