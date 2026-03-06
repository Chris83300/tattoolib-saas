<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tattoo_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            // Polymorphique — bookable_type/bookable_id
            $table->unsignedBigInteger('bookable_id');
            $table->string('bookable_type');
            $table->foreignId('booking_request_id')->nullable()->constrained()->cascadeOnDelete();
            $table->date('tattoo_date');
            $table->string('body_location');
            $table->text('description');
            $table->integer('duration');
            $table->decimal('total_paid', 10, 2);
            $table->enum('payment_method', ['stripe', 'cash', 'other']);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['client_id', 'bookable_type', 'bookable_id'], 'tattoo_histories_bookable_index');
            $table->index(['client_id', 'tattoo_date']);
            $table->index('booking_request_id');
        });

        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_request_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['no_show', 'quality', 'hygiene', 'payment', 'other'])->default('no_show');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'investigating', 'resolved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('tattoo_histories');
    }
};
