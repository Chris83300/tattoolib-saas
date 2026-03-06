<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            // Ajouter la relation avec Studio
            $table->foreignId('studio_id')
                ->nullable()
                ->after('user_id')
                ->constrained('studios')
                ->nullOnDelete();

            // Index pour les recherches
            $table->index('studio_id');
        });
    }

    public function down(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            $table->dropForeign(['studio_id']);
            $table->dropColumn('studio_id');
        });
    }
};
