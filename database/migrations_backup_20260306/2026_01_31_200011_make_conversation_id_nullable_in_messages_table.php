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
            // Rendre conversation_id nullable pour permettre les messages de projet
            $table->dropForeign(['conversation_id']);
            $table->foreignId('conversation_id')->nullable()->change();
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['conversation_id']);
            $table->foreignId('conversation_id')->nullable(false)->change();
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
        });
    }
};
