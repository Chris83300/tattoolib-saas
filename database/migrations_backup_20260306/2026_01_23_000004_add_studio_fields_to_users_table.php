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
        Schema::table('users', function (Blueprint $table) {
            // Optionnel : rattachement à un studio (pour propriétaire)
            $table->foreignId('studio_id')
                ->nullable()
                ->after('password')
                ->constrained('studios')
                ->onDelete('set null');

            // Flag pour différencier les types
            $table->boolean('is_studio_owner')->default(false)->after('studio_id');
            $table->boolean('is_studio_artist')->default(false)->after('is_studio_owner');

            // Index
            $table->index(['studio_id', 'is_studio_owner']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['studio_id', 'is_studio_owner']);
            $table->dropColumn(['studio_id', 'is_studio_owner', 'is_studio_artist']);
        });
    }
};
