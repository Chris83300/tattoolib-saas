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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('booking_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->enum('sender_type', ['tattooer', 'client']);

            // Contenu
            $table->text('content');
            $table->enum('attachment_type', ['image', 'document'])->nullable();

            // Métadonnées
            $table->boolean('is_design_version')->default(false);
            $table->integer('design_version_number')->nullable();

            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->timestamp('read_by_client_at')->nullable();
            $table->timestamp('read_by_tattooer_at')->nullable();

            $table->foreignId('conversation_id')->nullable()->constrained()->onDelete('cascade');

            $table->index(['booking_request_id', 'sender_id']);
            

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
