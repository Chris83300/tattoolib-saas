<?php

namespace App\Filament\Admin\Resources\Studios\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StudioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom du studio')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->helperText('URL unique pour le studio'),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull()
                    ->rows(4)
                    ->maxLength(1000)
                    ->helperText('Description du studio et de ses services'),
                TextInput::make('address')
                    ->label('Adresse')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Adresse complète du studio'),
                TextInput::make('city')
                    ->label('Ville')
                    ->required()
                    ->maxLength(100),
                TextInput::make('postal_code')
                    ->label('Code postal')
                    ->required()
                    ->length(5)
                    ->regex('/^[0-9]{5}$/')
                    ->helperText('Code postal français'),
                TextInput::make('country')
                    ->label('Pays')
                    ->required()
                    ->default('FR')
                    ->maxLength(2)
                    ->helperText('Code pays (FR, BE, CH...)'),
                TextInput::make('phone')
                    ->label('Téléphone')
                    ->tel()
                    ->maxLength(20)
                    ->helperText('Numéro de téléphone du studio'),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->helperText('Email de contact du studio'),
                TextInput::make('website')
                    ->label('Site web')
                    ->url()
                    ->prefix('https://')
                    ->helperText('Site web du studio'),
                TextInput::make('social_media_links')
                    ->label('Réseaux sociaux')
                    ->helperText('Liens vers les réseaux sociaux (Instagram, Facebook...)'),
                TextInput::make('logo_url')
                    ->label('URL du logo')
                    ->url()
                    ->helperText('URL de l\'image du logo'),
                TextInput::make('cover_images')
                    ->label('Images de couverture')
                    ->helperText('URLs des images de couverture (séparées par des virgules)'),
                TextInput::make('latitude')
                    ->label('Latitude')
                    ->numeric()
                    ->helperText('Coordonnées GPS'),
                TextInput::make('longitude')
                    ->label('Longitude')
                    ->numeric()
                    ->helperText('Coordonnées GPS'),
                TextInput::make('opening_hours')
                    ->label('Horaires d\'ouverture')
                    ->helperText('Horaires d\'ouverture du studio'),
                TextInput::make('facilities')
                    ->label('Équipements')
                    ->helperText('Équipements et installations disponibles'),
                TextInput::make('settings')
                    ->label('Paramètres')
                    ->helperText('Paramètres spécifiques du studio (JSON)'),
                TextInput::make('siret')
                    ->label('SIRET')
                    ->length(14)
                    ->regex('/^[0-9]{14}$/')
                    ->helperText('Numéro SIRET à 14 chiffres'),
                TextInput::make('vat_number')
                    ->label('Numéro TVA')
                    ->helperText('Numéro de TVA intracommunautaire'),
                TextInput::make('stripe_customer_id')
                    ->label('ID Client Stripe')
                    ->helperText('Identifiant client Stripe'),
                TextInput::make('total_artists')
                    ->label('Nombre d\'artistes')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->helperText('Nombre d\'artistes dans le studio'),
                Toggle::make('is_active')
                    ->label('Studio actif')
                    ->required()
                    ->helperText('Activer/désactiver le studio'),
                Toggle::make('is_verified')
                    ->label('Studio vérifié')
                    ->required()
                    ->helperText('Studio vérifié par l\'administration'),
                DateTimePicker::make('verified_at')
                    ->label('Date de vérification')
                    ->helperText('Date de vérification du studio'),
                Select::make('payment_mode')
                    ->label('Mode de paiement')
                    ->options([
                        'artist_direct' => '🎨 Artiste direct (chaque artiste encaisse)',
                        'studio_managed' => '🏢 Studio géré (studio encaisse)'
                    ])
                    ->default('artist_direct')
                    ->required()
                    ->helperText('Mode de gestion des paiements'),
                Toggle::make('uses_accounting_module')
                    ->label('Module comptabilité')
                    ->required()
                    ->helperText('Utiliser le module de comptabilité'),
                DateTimePicker::make('payment_mode_changed_at')
                    ->label('Changement mode paiement')
                    ->helperText('Date du dernier changement de mode de paiement'),
                TextInput::make('user_id')
                    ->label('ID Utilisateur propriétaire')
                    ->required()
                    ->numeric()
                    ->helperText('ID de l\'utilisateur propriétaire du studio'),
            ]);
    }
}
