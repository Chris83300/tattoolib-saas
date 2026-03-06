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
        Schema::create('tattoo_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('bookable_id'); // Polymorphic
            $table->string('bookable_type'); // 'App\Models\Tattooer', 'App\Models\StudioArtist', 'App\Models\Pierceur'
            $table->foreignId('booking_request_id')->nullable()->constrained()->cascadeOnDelete();

            $table->date('tattoo_date');
            $table->string('body_location');
            $table->text('description');
            $table->integer('duration'); // Minutes
            $table->decimal('total_paid', 10, 2);
            $table->enum('payment_method', ['stripe', 'cash', 'other']);

            // Spatie Media : photos (collection)

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['client_id', 'bookable_type', 'bookable_id'], 'tattoo_histories_bookable_index');
            $table->index(['client_id', 'tattoo_date'], 'tattoo_histories_client_date_index');
            $table->index(['booking_request_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tattoo_histories');
    }
};
