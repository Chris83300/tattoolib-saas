<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // TATTOOERS - Ajouter seulement les champs manquants
        Schema::table('tattooers', function (Blueprint $table) {
            // Vérifier si le champ n'existe pas déjà
            if (!Schema::hasColumn('tattooers', 'siret')) {
                $table->string('siret', 14)->nullable()
                    ->after('stripe_connect_account_id')
                    ->comment('SIRET obligatoire pour inscription');
            }

            // Vérifier si le champ n'existe pas déjà
            if (!Schema::hasColumn('tattooers', 'siret_verified')) {
                $table->boolean('siret_verified')->default(false)
                    ->after('siret')
                    ->comment('SIRET vérifié par admin');
            }

            // Vérifier si le champ n'existe pas déjà
            if (!Schema::hasColumn('tattooers', 'is_decision_maker')) {
                $table->boolean('is_decision_maker')->default(true)
                    ->after('siret_verified')
                    ->comment('True si acheteur/décideur (Certibiocide requis)');
            }

            // Vérifier si le champ n'existe pas déjà
            if (!Schema::hasColumn('tattooers', 'compliance_status')) {
                $table->enum('compliance_status', [
                    'non_compliant',      // Niveau 0 : Non conforme
                    'compliant',          // Niveau 1 : Conforme
                    'expiring_soon',      // Expire bientôt
                ])->default('non_compliant')
                    ->after('is_decision_maker')
                    ->comment('Statut global de conformité (auto-calculé)');

                $table->timestamp('last_compliance_check_at')->nullable()
                    ->after('compliance_status')
                    ->comment('Dernière vérification auto du statut');

                // Index
                $table->index('siret');
                $table->index('compliance_status');
            }
        });

        // STUDIO ARTISTS
        Schema::table('studio_artists', function (Blueprint $table) {
            // Vérifier si le champ n'existe pas déjà
            if (!Schema::hasColumn('studio_artists', 'is_decision_maker')) {
                $table->boolean('is_decision_maker')->default(false)
                    ->after('stripe_connect_account_id')
                    ->comment('True si acheteur/décideur (Certibiocide requis)');
            }

            // Vérifier si le champ n'existe pas déjà
            if (!Schema::hasColumn('studio_artists', 'compliance_status')) {
                $table->enum('compliance_status', [
                    'non_compliant',
                    'compliant',
                    'expiring_soon',
                ])->default('non_compliant')
                    ->after('is_decision_maker')
                    ->comment('Statut global de conformité (auto-calculé)');

                $table->timestamp('last_compliance_check_at')->nullable()
                    ->after('compliance_status')
                    ->comment('Dernière vérification auto du statut');

                // Index
                $table->index('compliance_status');
            }
        });

        // STUDIOS - Ajouter SIRET si manquant
        Schema::table('studios', function (Blueprint $table) {
            if (!Schema::hasColumn('studios', 'siret')) {
                $table->string('siret', 14)->nullable()
                    ->after('name')
                    ->comment('SIRET du studio (obligatoire)');

                $table->boolean('siret_verified')->default(false)
                    ->after('siret')
                    ->comment('SIRET vérifié par admin');

                $table->index('siret');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tattooers', function (Blueprint $table) {
            $table->dropColumn([
                'siret',
                'siret_verified',
                'is_decision_maker',
                'compliance_status',
                'last_compliance_check_at'
            ]);
        });

        Schema::table('studio_artists', function (Blueprint $table) {
            $table->dropColumn([
                'is_decision_maker',
                'compliance_status',
                'last_compliance_check_at'
            ]);
        });

        Schema::table('studios', function (Blueprint $table) {
            $table->dropColumn(['siret', 'siret_verified']);
        });
    }
};
