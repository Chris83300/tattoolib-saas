<?php

namespace App\Filament\Admin\Resources\Pierceurs\Schemas;

use Filament\Forms;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Piercer;

class PierceurForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // SECTION 1 : Informations Légales (OBLIGATOIRES)
                Section::make('Informations Légales')
                    ->description('Informations privées (visibles admin uniquement)')
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->label('Nom Réel')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Le nom réel du pierceur'),

                        Forms\Components\TextInput::make('siret')
                            ->label('Numéro SIRET')
                            ->required()
                            ->length(14)
                            ->numeric()
                            ->mask('99999999999999')
                            ->helperText('14 chiffres obligatoires')
                            ->suffixAction(
                                Action::make('verify_siret')
                                    ->label('Vérifier')
                                    ->icon('heroicon-o-magnifying-glass')
                                    ->action(function ($state) {
                                        // TODO: Call API entreprise.data.gouv.fr
                                        // Pour l'instant, juste une notification
                                        \Filament\Notifications\Notification::make()
                                            ->title('SIRET à vérifier manuellement')
                                            ->info()
                                            ->send();
                                    })
                            ),

                        Forms\Components\Toggle::make('siret_verified')
                            ->label('SIRET vérifié')
                            ->helperText('Cocher si le SIRET a été vérifié'),

                        Forms\Components\TextInput::make('city')
                            ->label('Ville')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Ville d\'exercice principal'),

                        Forms\Components\TextInput::make('postal_code')
                            ->label('Code Postal')
                            ->required()
                            ->length(5)
                            ->numeric()
                            ->mask('99999')
                            ->helperText('Code postal de la ville'),

                        Forms\Components\TextInput::make('phone')
                            ->label('Téléphone')
                            ->tel()
                            ->maxLength(20)
                            ->helperText('Téléphone professionnel'),

                    ])
                    ->columns(2),

                // SECTION 2 : Validation Admin (CRITIQUE)
                Section::make('Validation Admin')
                    ->description('Gestion du statut et validation du compte')
                    ->schema([

                        Forms\Components\Select::make('user.status')
                            ->label('Statut du compte')
                            ->required()
                            ->options([
                                'pending_verification' => 'En attente de validation',
                                'active' => 'Actif',
                                'suspended' => 'Suspendu',
                            ])
                            ->native(false)
                            ->helperText('Statut actuel du pierceur')
                            ->reactive(),

                        Forms\Components\Toggle::make('has_compliance_badge')
                            ->label('Badge de conformité')
                            ->helperText('Le pierceur a obtenu son badge de conformité')
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('admin_verified_at')
                            ->label('Date de validation')
                            ->disabled()
                            ->helperText('Date à laquelle le compte a été validé par l\'admin'),

                        Forms\Components\Textarea::make('admin_rejection_reason')
                            ->label('Raison du rejet/suspension')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Visible uniquement par l\'administration')
                            ->visible(fn ($get) => $get('user.status') === 'suspended')
                            ->columnSpanFull(),

                        ]),

                // SECTION 4 : Configuration Paiement
                Section::make('Configuration Paiement')
                    ->description('Paramètres Stripe et paiements')
                    ->schema([

                        Forms\Components\TextInput::make('stripe_connect_account_id')
                            ->label('Stripe Connect Account ID')
                            ->disabled()
                            ->helperText('ID du compte Stripe Connect du pierceur'),

                        Forms\Components\TextInput::make('minimum_deposit')
                            ->label('Acompte minimum (€)')
                            ->numeric()
                            ->suffix('€')
                            ->helperText('Montant minimum d\'acompte pour les réservations'),

                        Forms\Components\TextInput::make('default_deposit_rate')
                            ->label('Taux acompte par défaut (%)')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100)
                            ->helperText('Pourcentage d\'acompte par défaut'),

                    ])
                    ->columns(3)
                    ->collapsed(),

                // SECTION 5 : Métadonnées (READ ONLY)
                Section::make('Métadonnées')
                    ->description('Informations système')
                    ->schema([

                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Créé le')
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('updated_at')
                            ->label('Modifié le')
                            ->disabled(),

                        Forms\Components\TextInput::make('user_id')
                            ->label('ID Utilisateur')
                            ->disabled()
                            ->helperText('ID de l\'utilisateur associé'),

                        Forms\Components\TextInput::make('id')
                            ->label('ID Pierceur')
                            ->disabled()
                            ->helperText('ID du pierceur'),

                    ])
                    ->columns(4)
                    ->collapsed(),

            ]);
    }
}
