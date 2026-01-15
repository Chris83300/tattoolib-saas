<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            // Index pour les requêtes fréquentes
            $table->index(['client_id', 'tattooer_id']);
            $table->index('client_payment_deadline');
            $table->index('tattooer_design_deadline');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropIndex(['client_id', 'tattooer_id']);
            $table->dropIndex(['client_payment_deadline']);
            $table->dropIndex(['tattooer_design_deadline']);
            $table->dropIndex(['status']);
        });
    }
};
