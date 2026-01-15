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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_request_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('subject')->nullable();
            $table->enum('status', ['active', 'archived', 'blocked'])
                ->default('active')
                ->index();

            $table->timestamp('last_message_at')
                ->nullable()
                ->index();

            $table->unsignedBigInteger('last_message_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('conversations');
    }
};
