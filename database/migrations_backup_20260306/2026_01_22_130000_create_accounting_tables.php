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
            $table->foreignId('tattooer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('studio_id')->nullable()->constrained()->onDelete('cascade');

            // Transaction
            $table->string('reference')->unique(); // Facture #, etc.
            $table->enum('type', ['income', 'expense', 'tax_payment', 'transfer']);
            $table->enum('category', ['appointment', 'product_sale', 'equipment', 'rent', 'utility', 'marketing', 'tax', 'other']);
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('EUR');

            // Description
            $table->string('description');
            $table->text('notes')->nullable();

            // Dates
            $table->date('transaction_date');
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();

            // Statut
            $table->enum('status', ['draft', 'pending', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->string('payment_method')->nullable(); // 'cash', 'card', 'bank_transfer', 'stripe'

            // Association
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('purchase_order_id')->nullable()->constrained()->onDelete('set null');

            // TVA et taxes
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 8, 2)->default(0);
            $table->decimal('amount_with_tax', 10, 2)->storedAs('amount + tax_amount');

            // Pièces jointes
            $table->json('attachments')->nullable(); // URLs des factures, reçus, etc.

            $table->timestamps();

            $table->index(['tattooer_id', 'transaction_date']);
            $table->index(['studio_id', 'transaction_date']);
            $table->index('type');
            $table->index('status');
            $table->index('reference');
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tattooer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('studio_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');

            // Facture
            $table->string('invoice_number')->unique();
            $table->enum('type', ['appointment', 'product', 'service', 'deposit']);
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft');

            // Montants
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_rate', 5, 2)->default(20);
            $table->decimal('tax_amount', 8, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining_amount', 10, 2)->storedAs('total_amount - paid_amount');

            // Dates
            $table->date('invoice_date');
            $table->date('due_date');
            $table->date('paid_date')->nullable();

            // Informations client
            $table->text('client_address')->nullable();
            $table->text('client_email')->nullable();
            $table->string('client_phone')->nullable();

            // Services/Produits
            $table->json('items'); // Détail des prestations

            // Paiement
            $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'stripe', 'other'])->nullable();
            $table->string('transaction_id')->nullable(); // Stripe transaction ID

            // Notes
            $table->text('notes')->nullable();
            $table->text('payment_terms')->nullable();

            $table->timestamps();

            $table->index(['tattooer_id', 'invoice_date']);
            $table->index(['client_id', 'status']);
            $table->index('invoice_number');
        });

        Schema::create('expense_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tattooer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('studio_id')->nullable()->constrained()->onDelete('cascade');

            // Rapport de dépenses
            $table->string('report_number')->unique();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->decimal('total_amount', 10, 2);

            // Période
            $table->date('start_date');
            $table->date('end_date');

            // Validation
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('approved_date')->nullable();
            $table->text('approval_notes')->nullable();

            $table->timestamps();

            $table->index(['tattooer_id', 'status']);
            $table->index('report_number');
        });

        Schema::create('expense_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_report_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventory_item_id')->nullable()->constrained()->onDelete('set null');

            // Dépense
            $table->string('description');
            $table->enum('category', ['equipment', 'supplies', 'marketing', 'travel', 'utilities', 'rent', 'other']);
            $table->decimal('amount', 8, 2);
            $table->date('expense_date');

            // Receipt
            $table->string('receipt_number')->nullable();
            $table->json('attachments')->nullable(); // URLs des reçus

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_items');
        Schema::dropIfExists('expense_reports');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('accounting_transactions');
    }
};
