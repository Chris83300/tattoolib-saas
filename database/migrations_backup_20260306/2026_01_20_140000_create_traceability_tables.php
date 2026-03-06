<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_consent_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('tattooer_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');

            // Informations personnelles
            $table->string('full_name');
            $table->date('birth_date');
            $table->string('id_document_type'); // 'carte_id', 'passeport', 'permis'
            $table->string('id_document_number');
            $table->date('id_document_expiry');
            $table->string('phone');
            $table->string('email');
            $table->text('address');

            // Vérification d'âge
            $table->boolean('is_adult')->default(true);
            $table->date('consent_date');
            $table->time('consent_time');

            // Déclaration de santé
            $table->boolean('has_allergies')->default(false);
            $table->text('allergies_details')->nullable();
            $table->boolean('has_skin_conditions')->default(false);
            $table->text('skin_conditions_details')->nullable();
            $table->boolean('has_blood_disorders')->default(false);
            $table->text('blood_disorders_details')->nullable();
            $table->boolean('has_diabetes')->default(false);
            $table->boolean('has_heart_conditions')->default(false);
            $table->boolean('is_pregnant')->default(false);
            $table->boolean('is_breastfeeding')->default(false);
            $table->boolean('taking_medications')->default(false);
            $table->text('medications_details')->nullable();
            $table->boolean('has_recent_surgery')->default(false);
            $table->text('recent_surgery_details')->nullable();

            // Déclaration de tatouages existants
            $table->boolean('has_existing_tattoos')->default(false);
            $table->text('existing_tattoos_location')->nullable();

            // Consentement spécifique
            $table->boolean('consents_to_tattoo')->default(false);
            $table->boolean('understands_risks')->default(false);
            $table->boolean('understands_aftercare')->default(false);
            $table->boolean('consents_to_photos')->default(false); // Pour portfolio
            $table->boolean('consents_to_data_processing')->default(false);

            // Documents joints
            $table->json('id_document_photos')->nullable(); // Photos pièce d'identité
            $table->json('consent_signature')->nullable(); // Signature numérique
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            // Statut
            $table->enum('status', ['draft', 'signed', 'verified', 'expired'])->default('draft');
            $table->timestamp('signed_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            $table->index(['client_id', 'appointment_id']);
            $table->index(['tattooer_id', 'status']);
            $table->unique(['appointment_id']); // Un consentement par RDV
        });

        Schema::create('parental_consent_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_consent_form_id')->constrained()->onDelete('cascade');
            $table->foreignId('tattooer_id')->constrained()->onDelete('cascade');

            // Informations du parent/tuteur
            $table->string('parent_full_name');
            $table->string('parent_relationship'); // 'mother', 'father', 'guardian'
            $table->string('parent_id_document_type');
            $table->string('parent_id_document_number');
            $table->date('parent_id_document_expiry');
            $table->string('parent_phone');
            $table->string('parent_email');
            $table->text('parent_address');

            // Consentement parental
            $table->boolean('parent_consents_to_tattoo')->default(false);
            $table->boolean('parent_understands_risks')->default(false);
            $table->boolean('parent_will_supervise_aftercare')->default(false);
            $table->boolean('parent_consents_to_emergency_treatment')->default(false);

            // Documents
            $table->json('parent_id_document_photos')->nullable();
            $table->json('parent_signature')->nullable();
            $table->string('parent_ip_address')->nullable();
            $table->string('parent_user_agent')->nullable();

            // Statut
            $table->enum('status', ['draft', 'signed', 'verified', 'expired'])->default('draft');
            $table->timestamp('signed_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            $table->index(['client_consent_form_id', 'status']);
        });

        Schema::create('traceability_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tattooer_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_consent_form_id')->constrained()->onDelete('cascade');

            // Informations de procédure
            $table->date('procedure_date');
            $table->time('procedure_start_time');
            $table->time('procedure_end_time');

            // Équipement stérile
            $table->json('sterile_equipment'); // [{type, brand, lot_number, expiration_date, photo_url}]

            // Produits de soin
            $table->json('aftercare_products'); // [{brand, product_name, lot_number, expiration_date, photo_url}]

            // Environnement
            $table->string('room_number')->nullable();
            $table->string('autoclave_batch_number')->nullable();
            $table->date('autoclave_test_date')->nullable();

            // Photos de procédure
            $table->json('procedure_photos')->nullable(); // Photos avant/après, zone traitée
            $table->json('workstation_photos')->nullable(); // Photos de l'espace de travail

            // Notes et observations
            $table->text('procedure_notes')->nullable();
            $table->text('client_condition_notes')->nullable();
            $table->text('equipment_notes')->nullable();

            // Validation
            $table->boolean('client_verified_photos')->default(false);
            $table->boolean('tattooer_verified_traceability')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();

            $table->timestamps();

            $table->index(['tattooer_id', 'procedure_date']);
            $table->index('appointment_id');
            $table->unique('appointment_id'); // Un enregistrement de tracabilité par RDV
        });

        Schema::create('traceability_needles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('traceability_record_id')->constrained()->onDelete('cascade');
            $table->string('type'); // round_liner, magnum
            $table->string('size'); // 3RL, 5M1
            $table->integer('quantity')->default(1);
            $table->string('lot_number');
            $table->date('expiration_date');
            $table->string('photo_url')->nullable();
            $table->timestamps();

            $table->index('lot_number'); // ⭐ Permet recherche par lot en cas de rappel
        });

        Schema::create('traceability_inks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('traceability_record_id')->constrained()->onDelete('cascade');
            $table->string('brand');
            $table->string('color');
            $table->string('lot_number');
            $table->date('expiration_date');
            $table->integer('quantity_ml')->default(0);
            $table->string('photo_url')->nullable();
            $table->timestamps();

            $table->index('lot_number'); // ⭐ Permet recherche par lot en cas de rappel
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traceability_records');
        Schema::dropIfExists('traceability_inks');
        Schema::dropIfExists('traceability_needles');
        Schema::dropIfExists('parental_consent_forms');
        Schema::dropIfExists('client_consent_forms');
    }
};
