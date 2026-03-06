<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounting_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tattooer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('studio_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->enum('type', ['income', 'expense', 'tax_payment', 'transfer']);
            $table->enum('category', ['appointment', 'product_sale', 'equipment', 'rent', 'utility', 'marketing', 'tax', 'other']);
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('EUR');
            $table->string('description');
            $table->text('notes')->nullable();
            $table->date('transaction_date');
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->enum('status', ['draft', 'pending', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->string('payment_method')->nullable();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('purchase_order_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('tax_rate', 5, 2)->default(0.00);
            $table->decimal('tax_amount', 8, 2)->default(0.00);
            $table->decimal('amount_with_tax', 10, 2)->storedAs('(`amount` + `tax_amount`)');
            $table->json('attachments')->nullable();
            $table->foreignId('booking_request_id')->constrained()->cascadeOnDelete();
            $table->string('stripe_charge_id')->nullable();
            $table->string('receipt_url')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['tattooer_id', 'transaction_date']);
            $table->index(['studio_id', 'transaction_date']);
            $table->index('type');
            $table->index('status');
            $table->index('reference');
        });

        Schema::create('studio_accounting_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
            $table->enum('entry_type', ['income', 'expense', 'artist_payout', 'other']);
            $table->decimal('amount', 10, 2);
            $table->string('description');
            $table->string('category')->nullable();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('studio_artist_id')->nullable()->constrained()->nullOnDelete();
            $table->date('transaction_date');
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['studio_id', 'entry_type']);
            $table->index('transaction_date');
            $table->index('category');
        });

        Schema::create('expense_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tattooer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('studio_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('report_number')->unique();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->decimal('total_amount', 10, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('approved_date')->nullable();
            $table->text('approval_notes')->nullable();
            $table->timestamps();

            $table->index(['tattooer_id', 'status']);
            $table->index('report_number');
        });

        Schema::create('expense_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_report_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_item_id')->nullable()->constrained()->nullOnDelete();
            $table->string('description');
            $table->enum('category', ['equipment', 'supplies', 'marketing', 'travel', 'utilities', 'rent', 'other']);
            $table->decimal('amount', 8, 2);
            $table->date('expense_date');
            $table->string('receipt_number')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tattooer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('studio_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('invoice_number')->unique();
            $table->enum('type', ['appointment', 'product', 'service', 'deposit']);
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_rate', 5, 2)->default(20.00);
            $table->decimal('tax_amount', 8, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->decimal('remaining_amount', 10, 2)->storedAs('(`total_amount` - `paid_amount`)');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->text('client_address')->nullable();
            $table->text('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->json('items');
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'stripe', 'other'])->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->text('payment_terms')->nullable();
            $table->timestamps();

            $table->index(['tattooer_id', 'invoice_date']);
            $table->index(['client_id', 'status']);
            $table->index('invoice_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('expense_items');
        Schema::dropIfExists('expense_reports');
        Schema::dropIfExists('studio_accounting_entries');
        Schema::dropIfExists('accounting_transactions');
    }
};
