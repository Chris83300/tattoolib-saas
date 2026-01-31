<?php

namespace App\Filament\Admin\Resources\Tattooers\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Actions\Action;


class TattooerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([

                // SECTION 1 : Informations Légales (OBLIGATOIRES)
                Section::make('Informations Légales')
                    ->description('Informations privées (visibles admin uniquement)')
                    ->schema([

                        TextInput::make('name')
                            ->label('Nom Réel')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Le nom réel du tatoueur'),

                        TextInput::make('siret')
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

                        Toggle::make('siret_verified')
                            ->label('SIRET vérifié')
                            ->helperText('Cocher si le SIRET a été vérifié'),

                        TextInput::make('city')
                            ->label('Ville')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Ville d\'exercice principal'),

                        TextInput::make('postal_code')
                            ->label('Code Postal')
                            ->required()
                            ->length(5)
                            ->numeric()
                            ->mask('99999')
                            ->helperText('Code postal de la ville'),

                        TextInput::make('phone')
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

                        Select::make('user.status')
                            ->label('Statut du compte')
                            ->required()
                            ->options([
                                'pending_verification' => 'En attente de validation',
                                'active' => 'Actif',
                                'suspended' => 'Suspendu',
                            ])
                            ->native(false)
                            ->helperText('Statut actuel du tatoueur')
                            ->reactive(),

                        Toggle::make('has_compliance_badge')
                            ->label('Badge de conformité')
                            ->helperText('Le tatoueur a obtenu son badge de conformité')
                            ->columnSpanFull(),

                        DateTimePicker::make('admin_verified_at')
                            ->label('Date de validation')
                            ->disabled()
                            ->helperText('Date à laquelle le compte a été validé par l\'admin'),

                        Textarea::make('admin_rejection_reason')
                            ->label('Raison du rejet/suspension')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Visible uniquement par l\'administration')
                            ->visible(fn ($get) => $get('user.status') === 'suspended')
                            ->columnSpanFull(),

                    ])
                    ->columns(2),

                // SECTION 3 : Portfolio (NON OBLIGATOIRE)
                Section::make('Portfolio')
                    ->description('Photos et images du tatoueur')
                    ->schema([

                        FileUpload::make('avatar')
                            ->label('Avatar')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1:1'])
                            ->maxSize(5120)
                            ->helperText('Avatar principal du tatoueur (format carré recommandé)')
                            ->required(false), // ← NON OBLIGATOIRE

                        FileUpload::make('portfolio')
                            ->label('Portfolio')
                            ->multiple()
                            ->image()
                            ->imageEditor()
                            ->maxSize(5120)
                            ->maxFiles(20)
                            ->reorderable()
                            ->helperText('Photos des réalisations (max 20 images)')
                            ->required(false) // ← NON OBLIGATOIRE
                            ->columnSpanFull(),

                    ]),

                // SECTION 4 : Configuration Paiement
                Section::make('Configuration Paiement')
                    ->description('Paramètres Stripe et paiements')
                    ->schema([

                        TextInput::make('stripe_connect_account_id')
                            ->label('Stripe Connect Account ID')
                            ->disabled()
                            ->helperText('ID du compte Stripe Connect du tatoueur'),

                        TextInput::make('minimum_deposit')
                            ->label('Acompte minimum (€)')
                            ->numeric()
                            ->suffix('€')
                            ->helperText('Montant minimum d\'acompte pour les réservations'),

                        TextInput::make('default_deposit_rate')
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

                        DateTimePicker::make('created_at')
                            ->label('Créé le')
                            ->disabled(),

                        DateTimePicker::make('updated_at')
                            ->label('Modifié le')
                            ->disabled(),

                        TextInput::make('user_id')
                            ->label('ID Utilisateur')
                            ->disabled()
                            ->helperText('ID de l\'utilisateur associé'),

                        TextInput::make('id')
                            ->label('ID Tattooer')
                            ->disabled()
                            ->helperText('ID du tatoueur'),

                    ])
                    ->columns(4)
                    ->collapsed(),

            ]);
    }
}
