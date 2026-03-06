<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Relation booking
            $table->foreignId('booking_request_id')
                ->constrained()
                ->onDelete('cascade');

            // Stripe
            $table->string('stripe_payment_intent_id')->unique();
            $table->string('stripe_charge_id')->nullable();

            // Montant
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR');

            // Statut
            $table->enum('status', [
                'pending',
                'succeeded',
                'failed',
                'canceled'
            ])->default('pending');

            // Type
            $table->enum('payment_type', [
                'deposit',      // Acompte
                'remaining',    // Solde
                'full'          // Paiement complet
            ])->default('deposit');

            // Dates
            $table->timestamp('paid_at')->nullable();
            $table->text('failure_reason')->nullable();

            $table->timestamps();

            // Index
            $table->index(['booking_request_id', 'payment_type']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
