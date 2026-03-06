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
        Schema::create('booking_transactions', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('booking_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // le payeur (client)

            // Transaction
            $table->string('type'); // 'deposit', 'final_payment', 'refund', 'commission'
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('eur');
            $table->string('status'); // 'pending', 'completed', 'failed', 'refunded'
            $table->string('payment_method')->nullable(); // 'stripe', 'cash', etc.

            // Stripe
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_session_id')->nullable();

            // Métadonnées
            $table->json('metadata')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['booking_request_id', 'type']);
            $table->index('stripe_payment_intent_id');
            $table->index('stripe_session_id');
            $table->index(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_transactions');
    }
};
