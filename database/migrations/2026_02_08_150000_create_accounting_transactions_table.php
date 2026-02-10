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
        if (!Schema::hasTable('accounting_transactions')) {
            Schema::create('accounting_transactions', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('booking_request_id')->constrained('booking_requests')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // le payeur (client)

            // Transaction details
            $table->string('type'); // 'deposit', 'final_payment', 'refund', 'commission', 'surcharge'
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('eur');
            $table->string('status'); // 'pending', 'completed', 'failed', 'refunded'
            $table->string('payment_method')->nullable(); // 'stripe', 'cash', 'bank_transfer', etc.

            // Stripe integration
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_session_id')->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->string('receipt_url')->nullable();

            // Additional data
            $table->json('metadata')->nullable(); // receipts, refunds details, etc.
            $table->text('description')->nullable();
            $table->timestamp('processed_at')->nullable(); // quand la transaction a été traitée

            $table->timestamps();

            // Indexes
            $table->index(['booking_request_id', 'type']);
            $table->index('stripe_payment_intent_id');
            $table->index('stripe_session_id');
            $table->index(['user_id', 'type']);
            $table->index('status');
            $table->index('processed_at');
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_transactions');
    }
};
