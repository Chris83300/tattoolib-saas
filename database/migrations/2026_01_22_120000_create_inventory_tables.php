<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tattooer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('studio_id')->nullable()->constrained()->onDelete('cascade');

            // Informations produit
            $table->string('name');
            $table->string('sku')->unique(); // Stock Keeping Unit
            $table->text('description')->nullable();
            $table->string('category'); // 'ink', 'needle', 'equipment', 'aftercare', 'disposable'
            $table->string('brand')->nullable();
            $table->string('supplier')->nullable();

            // Stock
            $table->integer('current_stock')->default(0);
            $table->integer('min_stock_level')->default(0); // Alert si en dessous
            $table->integer('max_stock_level')->default(0);
            $table->decimal('unit_price', 8, 2)->nullable();
            $table->string('unit_type')->default('unit'); // 'unit', 'ml', 'grams', 'box'

            // Spécifique aux encres
            $table->string('color')->nullable();
            $table->string('size')->nullable(); // '15ml', '30ml', etc.
            $table->boolean('is_vegan')->default(false);
            $table->date('expiration_date')->nullable();

            // Spécifique aux aiguilles
            $table->string('needle_type')->nullable(); // 'round_liner', 'magnum', etc.
            $table->string('needle_size')->nullable(); // '3RL', '5M1', etc.

            // Gestion
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['tattooer_id', 'category']);
            $table->index(['studio_id', 'category']);
            $table->index('sku');
        });

        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('tattooer_id')->nullable()->constrained()->onDelete('cascade');

            // Mouvement
            $table->enum('movement_type', ['in', 'out', 'adjustment', 'transfer']);
            $table->integer('quantity');
            $table->integer('stock_before');
            $table->integer('stock_after');

            // Raison du mouvement
            $table->string('reason'); // 'purchase', 'sale', 'usage', 'damage', 'return', 'adjustment'
            $table->text('notes')->nullable();

            // Association si usage
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');

            $table->timestamps();

            $table->index(['inventory_item_id', 'created_at']);
            $table->index('movement_type');
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tattooer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('studio_id')->nullable()->constrained()->onDelete('cascade');

            // Commande
            $table->string('order_number')->unique();
            $table->string('supplier');
            $table->enum('status', ['draft', 'sent', 'received', 'cancelled'])->default('draft');

            // Dates
            $table->date('order_date');
            $table->date('expected_delivery_date')->nullable();
            $table->date('received_date')->nullable();

            // Montants
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('shipping_cost', 8, 2)->default(0);

            // Documents
            $table->string('invoice_number')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['tattooer_id', 'status']);
            $table->index('order_number');
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventory_item_id')->nullable()->constrained()->onDelete('set null');

            // Détails article
            $table->string('item_name');
            $table->string('sku')->nullable();
            $table->integer('quantity_ordered');
            $table->integer('quantity_received')->default(0);
            $table->decimal('unit_price', 8, 2);
            $table->decimal('total_price', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('inventory_items');
    }
};
