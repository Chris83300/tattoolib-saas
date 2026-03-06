<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            // Vérifier si la colonne n'existe pas déjà
            if (!Schema::hasColumn('booking_requests', 'estimated_price')) {
                $table->decimal('estimated_price', 10, 2)->nullable()->after('description');
            }

            if (!Schema::hasColumn('booking_requests', 'deposit_paid_at')) {
                $table->timestamp('deposit_paid_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropColumn(['estimated_price', 'deposit_paid_at']);
        });
    }
};
