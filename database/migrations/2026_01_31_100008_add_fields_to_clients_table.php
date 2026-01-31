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
            // Séparer nom et prénom
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
            
            // Adresse complète
            $table->text('address')->nullable()->after('email');
            
            // Notes privées tatoueur
            $table->text('notes')->nullable()->after('blacklist_reason');
            
            // Index pour performances
            $table->index(['first_name', 'last_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex(['first_name', 'last_name']);
            $table->dropColumn(['first_name', 'last_name', 'address', 'notes']);
        });
    }
};
