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
            // Rendre booking_request_id nullable pour permettre les messages de projet
            $table->dropForeign(['booking_request_id']);
            $table->foreignId('booking_request_id')->nullable()->change();
            $table->foreign('booking_request_id')->references('id')->on('booking_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['booking_request_id']);
            $table->foreignId('booking_request_id')->nullable(false)->change();
            $table->foreign('booking_request_id')->references('id')->on('booking_requests')->onDelete('cascade');
        });
    }
};
