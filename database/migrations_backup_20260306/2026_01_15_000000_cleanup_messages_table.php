<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Suppression des colonnes redondantes
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'is_read')) {
                $table->dropColumn('is_read');
            }
            if (Schema::hasColumn('messages', 'read_at')) {
                $table->dropColumn('read_at');
            }
            if (Schema::hasColumn('messages', 'read_by_client_at')) {
                $table->dropColumn('read_by_client_at');
            }
            if (Schema::hasColumn('messages', 'read_by_tattooer_at')) {
                $table->dropColumn('read_by_tattooer_at');
            }

            // Rendre conversation_id obligatoire
            $table->foreignId('conversation_id')
                ->nullable(false)
                ->change();

            // Ajout d'index pour les requêtes fréquentes
            $table->index('conversation_id');
            $table->index(['conversation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // Recréer les colonnes supprimées
            $table->boolean('is_read')->default(false)->after('design_version_number');
            $table->timestamp('read_at')->nullable()->after('is_read');
            $table->timestamp('read_by_client_at')->nullable()->after('read_at');
            $table->timestamp('read_by_tattooer_at')->nullable()->after('read_by_client_at');

            // Rendre conversation_id nullable à nouveau
            $table->foreignId('conversation_id')
                ->nullable()
                ->change();

            // Supprimer les index ajoutés
            $table->dropIndex(['conversation_id']);
            $table->dropIndex(['conversation_id', 'created_at']);
        });
    }
};
