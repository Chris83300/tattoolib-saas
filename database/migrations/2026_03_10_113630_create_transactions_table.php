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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('artist_id')->nullable();
            $table->string('artist_type'); // 'tattooer', 'piercer', 'studio'
            $table->decimal('amount', 10, 2);
            $table->decimal('commission_amount', 10, 2); // Commission plateforme 7%
            $table->decimal('net_amount', 10, 2); // Montant pour l'artiste
            $table->string('currency', 3)->default('EUR');
            $table->string('status'); // 'pending', 'completed', 'failed', 'refunded'
            $table->string('payment_type'); // 'deposit', 'full_payment'
            $table->string('refund_status')->default('none'); // 'none', 'partial', 'full'
            $table->decimal('refund_amount', 10, 2)->default(0);
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['artist_id', 'artist_type']);
            $table->index('status');
            $table->index('processed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
