<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // conversations.last_message_id FK ajoutée APRÈS messages (dépendance circulaire)
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_request_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('expiry_type', ['deposit_pending', 'permanent', 'post_appointment', 'archived'])->default('deposit_pending');
            $table->timestamp('deposit_deadline_at')->nullable();
            $table->timestamp('appointment_completed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->boolean('is_expired')->default(false);
            $table->boolean('images_preserved')->default(false);
            $table->timestamp('expiry_warning_sent_at')->nullable();
            $table->string('subject')->nullable();
            $table->enum('status', ['active', 'archived', 'blocked'])->default('active');
            $table->timestamp('last_message_at')->nullable();
            $table->unsignedBigInteger('last_message_id')->nullable(); // FK ajoutée après messages
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('last_message_at');
            $table->index('expiry_type');
            $table->index('expires_at');
            $table->index('is_expired');
            $table->index(['expiry_type', 'expires_at'], 'expiry_lookup');
        });

        Schema::create('conversation_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['client', 'tattooer', 'admin', 'support'])->nullable();
            $table->timestamp('last_read_at')->nullable();
            $table->boolean('is_muted')->default(false);
            $table->timestamps();

            $table->unique(['conversation_id', 'user_id']);
            $table->index(['user_id', 'last_read_at']);
            $table->index(['conversation_id', 'last_read_at']);
            $table->index('role');
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_request_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->enum('sender_type', ['tattooer', 'client', 'system']);
            $table->text('content');
            $table->timestamp('read_by_tattooer_at')->nullable();
            $table->timestamp('read_by_client_at')->nullable();
            $table->enum('attachment_type', ['image', 'document'])->nullable();
            $table->boolean('is_design_version')->default(false);
            $table->integer('design_version_number')->nullable();
            $table->foreignId('conversation_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['booking_request_id', 'sender_id']);
            $table->index('conversation_id');
            $table->index(['conversation_id', 'created_at']);
            $table->index('read_by_tattooer_at');
            $table->index('read_by_client_at');
        });

        // Ajouter la FK conversations.last_message_id maintenant que messages existe
        Schema::table('conversations', function (Blueprint $table) {
            $table->foreign('last_message_id')->references('id')->on('messages')->nullOnDelete();
            $table->index('last_message_id');
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['last_message_id']);
        });
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversation_user');
        Schema::dropIfExists('conversations');
    }
};
