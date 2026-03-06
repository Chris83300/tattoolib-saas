<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Note: table 'transactions' (0 rows) supprimée — couverte par booking_transactions + payments
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_request_id')->constrained()->cascadeOnDelete();
            $table->string('stripe_payment_intent_id')->unique();
            $table->string('stripe_charge_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->enum('status', ['pending', 'succeeded', 'failed', 'canceled'])->default('pending');
            $table->enum('payment_type', ['deposit', 'remaining', 'full'])->default('deposit');
            $table->enum('recipient_type', ['artist', 'studio'])->nullable();
            $table->string('recipient_name')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            $table->index(['booking_request_id', 'payment_type']);
            $table->index('status');
            $table->index('recipient_type');
        });

        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->string('stripe_refund_id')->unique();
            $table->decimal('amount', 10, 2);
            $table->text('reason');
            $table->string('status');
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['payment_id', 'status']);
            $table->index('stripe_refund_id');
        });

        Schema::create('booking_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('eur');
            $table->string('status');
            $table->string('payment_method')->nullable();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_session_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['booking_request_id', 'type']);
            $table->index('stripe_payment_intent_id');
            $table->index('stripe_session_id');
            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_transactions');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('payments');
    }
};
