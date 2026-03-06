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
        Schema::table('consents', function (Blueprint $table) {
            // Ajouter booking_request_id pour lier directement à une demande de réservation
            $table->foreignId('booking_request_id')->nullable()->after('client_id')->constrained()->cascadeOnDelete();

            // Index pour optimiser les recherches
            $table->index(['booking_request_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consents', function (Blueprint $table) {
            $table->dropForeign(['booking_request_id']);
            $table->dropIndex(['booking_request_id']);
            $table->dropColumn('booking_request_id');
        });
    }
};
