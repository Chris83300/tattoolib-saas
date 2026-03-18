<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataProcessingRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $records = [
            [
                'name'             => 'Gestion des comptes utilisateurs',
                'purpose'          => 'Création et gestion des comptes tatoueurs, pierceurs, clients et studios',
                'legal_basis'      => 'Exécution du contrat (CGU)',
                'data_categories'  => ['identité', 'contact', 'authentification'],
                'data_subjects'    => ['tatoueurs', 'pierceurs', 'clients', 'studios'],
                'retention_period' => '3 ans après la dernière activité',
                'requires_dpia'    => false,
            ],
            [
                'name'                 => 'Traitement des réservations et paiements',
                'purpose'              => 'Gestion du flux de réservation, paiements via Stripe',
                'legal_basis'          => 'Exécution du contrat',
                'data_categories'      => ['identité', 'contact', 'données financières', 'historique achats'],
                'data_subjects'        => ['clients', 'tatoueurs', 'pierceurs'],
                'recipients'           => ['Stripe Inc. (sous-traitant paiement, US - Privacy Shield)'],
                'transfers_outside_eu' => true,
                'retention_period'     => '10 ans (obligation comptable)',
                'requires_dpia'        => false,
            ],
            [
                'name'             => 'Données de santé - Fiches clients et consentements',
                'purpose'          => 'Traçabilité médicale obligatoire pour tatouage/piercing (arrêté du 11 mars 2009)',
                'legal_basis'      => 'Obligation légale + Consentement explicite (Art. 9 RGPD)',
                'data_categories'  => ['données de santé', 'groupe sanguin', 'allergies', 'traitements médicaux', 'historique tatouage'],
                'data_subjects'    => ['clients'],
                'retention_period' => '10 ans (traçabilité médicale réglementaire)',
                'requires_dpia'    => true,
                'dpia_notes'       => 'AIPD requise - données de santé catégorie spéciale Art.9 RGPD. Mesures : chiffrement AES-256, accès restreint artiste concerné, logs d\'accès.',
            ],
            [
                'name'                 => 'Notifications push et emails',
                'purpose'              => 'Envoi de notifications relatives aux réservations et à la plateforme',
                'legal_basis'          => 'Intérêt légitime (notifications contractuelles) + Consentement (marketing)',
                'data_categories'      => ['contact', 'tokens push (FCM)'],
                'data_subjects'        => ['tous utilisateurs'],
                'recipients'           => ['Google Firebase (sous-traitant push, US)'],
                'transfers_outside_eu' => true,
                'retention_period'     => 'Durée du compte + 30 jours',
                'requires_dpia'        => false,
            ],
            [
                'name'                 => 'Stripe Connect - Comptes artistes',
                'purpose'              => 'Onboarding KYC et gestion des paiements vers les artistes',
                'legal_basis'          => 'Exécution du contrat + Obligation légale (LCB-FT)',
                'data_categories'      => ['identité', 'documents officiels', 'coordonnées bancaires', 'SIRET'],
                'data_subjects'        => ['tatoueurs', 'pierceurs', 'studios'],
                'recipients'           => ['Stripe Inc. (sous-traitant, US - Privacy Shield)'],
                'transfers_outside_eu' => true,
                'retention_period'     => '5 ans après fin de relation commerciale (LCB-FT)',
                'requires_dpia'        => true,
                'dpia_notes'           => 'Données bancaires et documents d\'identité. KYC délégué à Stripe.',
            ],
        ];

        foreach ($records as $record) {
            \App\Models\DataProcessingRecord::create($record);
        }
    }
}
