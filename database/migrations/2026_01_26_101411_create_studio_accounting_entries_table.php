<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studio_accounting_entries', function (Blueprint $table) {
            $table->id();

            // ===========================================
            // STUDIO PROPRIÉTAIRE
            // ===========================================
            $table->foreignId('studio_id')
                ->constrained()
                ->onDelete('cascade');

            // ===========================================
            // TYPE D'ÉCRITURE
            // ===========================================
            $table->enum('entry_type', [
                'income',           // Revenus (paiement client)
                'expense',          // Dépenses (matériel, loyer, etc.)
                'artist_payout',    // Versement artiste
                'other'             // Autre
            ]);

            // ===========================================
            // MONTANT
            // ===========================================
            $table->decimal('amount', 10, 2)
                ->comment('Montant de l\'opération (en euros)');

            // ===========================================
            // DESCRIPTION
            // ===========================================
            $table->string('description')
                ->comment('Description libre de l\'opération');

            // ===========================================
            // CATÉGORIE (LIBRE)
            // ===========================================
            $table->string('category')->nullable()
                ->comment('Catégorie définie par le studio');

            // ===========================================
            // LIENS OPTIONNELS
            // ===========================================
            $table->foreignId('payment_id')->nullable()
                ->constrained()
                ->onDelete('set null')
                ->comment('Lien vers paiement client (si applicable)');

            $table->foreignId('studio_artist_id')->nullable()
                ->constrained()
                ->onDelete('set null')
                ->comment('Lien vers artiste (si applicable)');

            // ===========================================
            // DATE OPÉRATION
            // ===========================================
            $table->date('transaction_date')
                ->comment('Date de l\'opération');

            // ===========================================
            // NOTES
            // ===========================================
            $table->text('notes')->nullable()
                ->comment('Notes libres du studio');

            // ===========================================
            // PIÈCES JOINTES
            // ===========================================
            $table->json('attachments')->nullable()
                ->comment('Chemins vers factures/reçus (JSON)');

            // ===========================================
            // AUDIT
            // ===========================================
            $table->timestamps();
            $table->softDeletes();

            // ===========================================
            // INDEX
            // ===========================================
            $table->index(['studio_id', 'entry_type']);
            $table->index('transaction_date');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('studio_accounting_entries');
    }
};
