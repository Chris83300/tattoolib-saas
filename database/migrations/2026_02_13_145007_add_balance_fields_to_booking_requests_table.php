<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            // Ajouter les colonnes de paiement du solde si elles n'existent pas
            if (!Schema::hasColumn('booking_requests', 'balance_amount')) {
                $table->decimal('balance_amount', 10, 2)->nullable()->after('total_price');
            }
            if (!Schema::hasColumn('booking_requests', 'balance_paid_at')) {
                $table->timestamp('balance_paid_at')->nullable()->after('balance_amount');
            }
            if (!Schema::hasColumn('booking_requests', 'balance_payment_method')) {
                $table->string('balance_payment_method')->nullable()->after('balance_paid_at'); // 'stripe', 'cash', 'card_direct', 'transfer'
            }
            if (!Schema::hasColumn('booking_requests', 'balance_stripe_session_id')) {
                $table->string('balance_stripe_session_id')->nullable()->after('balance_payment_method');
            }
        });
    }

    public function down(): void
    {
        Schema::table('booking_requests', function (Blueprint $table) {
            $table->dropColumn([
                'balance_amount',
                'balance_paid_at',
                'balance_payment_method',
                'balance_stripe_session_id'
            ]);
        });
    }
};
