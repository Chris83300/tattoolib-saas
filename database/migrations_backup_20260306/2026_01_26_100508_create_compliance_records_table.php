<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compliance_records', function (Blueprint $table) {
            $table->id();

            // =====================================
            // POLYMORPHIC RELATION
            // =====================================
            $table->string('compliant_type'); // App\Models\Tattooer ou App\Models\StudioArtist
            $table->unsignedBigInteger('compliant_id');
            $table->index(['compliant_type', 'compliant_id'], 'compliant_index');

            // =====================================
            // TYPE DE CERTIFICATION
            // =====================================
            $table->enum('certification_type', [
                'hygiene_salubrite',  // Hygiène & Salubrité (OBLIGATOIRE)
                'certibiocide',       // Certibiocide TP2 (BONUS)
                'declaration_ars'     // Déclaration ARS (OBLIGATOIRE)
            ]);

            // =====================================
            // INFORMATIONS CERTIFICAT
            // =====================================
            $table->string('certificate_number')->nullable()
                ->comment('Numéro du certificat');

            $table->string('training_organization')->nullable()
                ->comment('Organisme de formation agréé');

            $table->date('obtained_at')
                ->comment('Date d\'obtention');

            $table->date('expires_at')->nullable()
                ->comment('Date d\'expiration (null pour ARS)');

            // =====================================
            // DOCUMENTS (Upload)
            // =====================================
            $table->string('certificate_file_path', 500)->nullable()
                ->comment('Chemin vers le PDF du certificat');

            $table->string('ars_proof_file_path', 500)->nullable()
                ->comment('Chemin vers la preuve de déclaration ARS');

            // =====================================
            // STATUT AUTO-CALCULÉ
            // =====================================
            $table->enum('status', [
                'valid',           // Valide
                'expiring_soon',   // Expire dans 90j
                'expired',         // Expiré
                'missing',         // Manquant
                'pending'          // En attente validation admin
            ])->default('missing');

            // =====================================
            // CERTIBIOCIDE SPÉCIFIQUE
            // =====================================
            $table->string('biocide_type', 50)->nullable()
                ->comment('Type de biocide : TP2, TP4, etc.');

            $table->boolean('is_decision_maker')->default(false)
                ->comment('True si acheteur/décideur (Certibiocide requis)');

            // =====================================
            // ARS SPÉCIFIQUE
            // =====================================
            $table->string('ars_region', 100)->nullable()
                ->comment('Région ARS : Île-de-France, PACA, etc.');

            $table->string('ars_number', 100)->nullable()
                ->comment('Numéro de déclaration ARS');

            // =====================================
            // ALERTES ENVOYÉES
            // =====================================
            $table->timestamp('notification_90d_sent_at')->nullable()
                ->comment('Date envoi alerte J-90');

            $table->timestamp('notification_30d_sent_at')->nullable()
                ->comment('Date envoi alerte J-30');

            $table->timestamp('notification_expired_sent_at')->nullable()
                ->comment('Date envoi notification expiration');

            // =====================================
            // VÉRIFICATION ADMIN
            // =====================================
            $table->foreignId('verified_by')->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->comment('Admin qui a vérifié le document');

            $table->timestamp('verified_at')->nullable()
                ->comment('Date de vérification admin');

            $table->text('admin_notes')->nullable()
                ->comment('Notes internes admin');

            // =====================================
            // AUDIT
            // =====================================
            $table->timestamps();
            $table->softDeletes();

            // =====================================
            // INDEX PERFORMANCE
            // =====================================
            $table->index('status');
            $table->index('expires_at');
            $table->index('certification_type');

            // Contrainte unique : 1 seul certificat par type par artiste
            $table->unique([
                'compliant_type',
                'compliant_id',
                'certification_type'
            ], 'unique_certification_per_artist');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compliance_records');
    }
};
