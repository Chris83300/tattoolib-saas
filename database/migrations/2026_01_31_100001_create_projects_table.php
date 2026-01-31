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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('bookable_id'); // Polymorphic
            $table->string('bookable_type'); // 'App\Models\Tattooer', 'App\Models\StudioArtist', 'App\Models\Pierceur'

            // Statuts : pending, accepted, in_progress, completed, cancelled, no_show
            $table->enum('status', ['pending', 'accepted', 'in_progress', 'completed', 'cancelled', 'no_show'])
                ->default('pending');

            // Détails projet
            $table->text('tattoo_description');
            $table->string('tattoo_location'); // Partie du corps
            $table->string('tattoo_style')->nullable(); // Style (réalisme, tribal, etc.)
            $table->integer('estimated_duration')->nullable(); // Minutes
            $table->decimal('estimated_price', 10, 2)->nullable();

            // Acompte
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->timestamp('deposit_paid_at')->nullable();
            $table->string('deposit_stripe_payment_id')->nullable();

            // Solde
            $table->decimal('final_price', 10, 2)->nullable();
            $table->timestamp('final_paid_at')->nullable();
            $table->string('final_stripe_payment_id')->nullable();
            $table->enum('payment_method', ['stripe', 'cash', 'other'])->nullable();

            // RDV
            $table->dateTime('proposed_date')->nullable(); // Date proposée par client
            $table->dateTime('appointment_date')->nullable(); // Date confirmée
            $table->dateTime('appointment_end')->nullable(); // Fin RDV (calculé auto)

            // Workflow
            $table->timestamp('accepted_at')->nullable(); // Tatoueur accepte projet
            $table->timestamp('deposit_requested_at')->nullable();
            $table->timestamp('appointment_confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->boolean('refund_issued')->default(false);

            // Auto-archivage
            $table->timestamp('archived_at')->nullable(); // J+1 après RDV

            $table->timestamps();
            $table->softDeletes();

            $table->index(['bookable_type', 'bookable_id', 'status', 'appointment_date']);
            $table->index(['client_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
