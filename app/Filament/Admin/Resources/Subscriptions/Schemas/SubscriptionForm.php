<?php

namespace App\Filament\Admin\Resources\Subscriptions\Schemas;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Support\Facades\DB;

class SubscriptionForm
{
    public static function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations générales')
                    ->description('Détails de l\'abonnement')
                    ->schema([
                        TextInput::make('user.name')
                            ->label('Utilisateur')
                            ->disabled()
                            ->formatStateUsing(function ($record) {
                                $user = $record->user;
                                if (!$user) return 'N/A';
                                return $user->name ?? $user->email ?? 'N/A';
                            }),
                        Select::make('plan')
                            ->label('Plan')
                            ->options([
                                'starter' => 'Starter (commission 7%)',
                                'pro' => 'Pro (commission 0%)',
                                'studio' => 'Studio',
                            ])
                            ->required(),
                        Select::make('stripe_status')
                            ->label('Statut')
                            ->options([
                                'active' => 'Actif',
                                'trialing' => 'Essai',
                                'canceled' => 'Annulé',
                                'incomplete' => 'Incomplet',
                                'past_due' => 'En retard',
                                'unpaid' => 'Impayé',
                            ])
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Informations Stripe')
                    ->description('Données de facturation Stripe')
                    ->schema([
                        TextInput::make('stripe_id')
                            ->label('ID Abonnement Stripe')
                            ->disabled(),
                        TextInput::make('stripe_price')
                            ->label('ID Prix Stripe')
                            ->disabled(),
                    ])
                    ->columns(2),

                Section::make('Période de facturation')
                    ->description('Dates de l\'abonnement')
                    ->schema([
                        DateTimePicker::make('created_at')
                            ->label('Début période')
                            ->disabled(),
                        DateTimePicker::make('updated_at')
                            ->label('Dernière mise à jour')
                            ->disabled(),
                        DateTimePicker::make('trial_ends_at')
                            ->label('Date de fin d\'essai')
                            ->disabled(),
                        DateTimePicker::make('ends_at')
                            ->label('Date de fin')
                            ->disabled(),
                    ])
                    ->columns(2),

                Section::make('Tarification')
                    ->description('Prix et commissions')
                    ->schema([
                        TextInput::make('stripe_price')
                            ->label('Prix mensuel (€)')
                            ->disabled()
                            ->formatStateUsing(function ($record) {
                                $price = $record->stripe_price ?? '';

                                // Prix dynamiques depuis .env
                                $priceStarter = env('STRIPE_PRICE_STARTER', 9.99);
                                $pricePro = env('STRIPE_PRICE_PRO', 29.99);
                                $priceStudio = env('STRIPE_PRICE_STUDIO', 59.99);
                                $priceStudioArtist = env('STRIPE_PRICE_STUDIO_EXTRA', 24.99);

                                // Mapping exact des IDs Stripe vers les prix dynamiques
                                if (str_contains($price, '1T7E4D')) {
                                    return $priceStarter . '€ (Starter)';
                                } elseif (str_contains($price, '1T8zRR')) {
                                    return $pricePro . '€ (Pro)';
                                } elseif (str_contains($price, '1T8zPp')) {
                                    // Pour le studio, calculer le prix total avec les artistes
                                    $user = $record->user;
                                    if ($user && $user->role === 'studio' && $user->studio_id) {
                                        $artistCount = DB::table('tattooers')
                                            ->where('studio_id', $user->studio_id)
                                            ->count();
                                        $totalPrice = $priceStudio + ($artistCount * $priceStudioArtist);
                                        return $totalPrice . '€ (Studio - ' . $artistCount . ' artistes)';
                                    }
                                    return $priceStudio . '€ (Studio)';
                                } elseif (str_contains($price, 'starter')) {
                                    return $priceStarter . '€ (Starter)';
                                } elseif (str_contains($price, 'pro')) {
                                    return $pricePro . '€ (Pro)';
                                } elseif (str_contains($price, 'studio')) {
                                    // Pour le studio, calculer le prix total avec les artistes
                                    $user = $record->user;
                                    if ($user && $user->role === 'studio' && $user->studio_id) {
                                        $artistCount = DB::table('tattooers')
                                            ->where('studio_id', $user->studio_id)
                                            ->count();
                                        $totalPrice = $priceStudio + ($artistCount * $priceStudioArtist);
                                        return $totalPrice . '€ (Studio - ' . $artistCount . ' artistes)';
                                    }
                                    return $priceStudio . '€ (Studio)';
                                }

                                return 'Prix non identifié';
                            }),
                        TextInput::make('quantity')
                            ->label('Quantité')
                            ->numeric()
                            ->default(1)
                            ->disabled(),
                    ])
                    ->columns(2),

                Section::make('Artistes du studio')
                    ->description('Artistes rattachés à ce studio')
                    ->schema([
                        TextInput::make('studio_artists')
                            ->label('Artistes liés')
                            ->disabled()
                            ->formatStateUsing(function ($record) {
                                $user = $record->user;
                                if (!$user || $user->role !== 'studio') {
                                    return 'N/A';
                                }

                                // Récupérer les artistes du studio en utilisant la table tattooers
                                $studioId = $user->studio_id; // L'utilisateur studio a studio_id = 3

                                if (!$studioId) {
                                    return 'Aucun studio_id trouvé';
                                }

                                $artists = DB::table('tattooers')
                                    ->where('studio_id', $studioId)
                                    ->get(['id', 'first_name', 'last_name', 'pseudo']);

                                if ($artists->isEmpty()) {
                                    return 'Aucun artiste rattaché';
                                }

                                $artistList = $artists->map(function ($artist) {
                                    $name = trim(($artist->first_name ?? '') . ' ' . ($artist->last_name ?? ''));
                                    if (empty($name)) {
                                        // Fallback: utiliser le pseudo ou l'ID
                                        return $artist->pseudo ?? 'Artiste #' . $artist->id;
                                    }
                                    return $name;
                                })->implode(', ');

                                $artistCount = $artists->count();

                                // Prix dynamiques depuis .env
                                $priceStudio = env('STRIPE_PRICE_STUDIO', 59.99);
                                $priceStudioExtra = env('STRIPE_PRICE_STUDIO_EXTRA', 24.99);

                                // Calculer le tarif total
                                $totalPrice = $priceStudio + ($artistCount * $priceStudioExtra);

                                return $artistList . ' (' . $artistCount . ' artistes)' . "\n" .
                                       'Tarif studio: ' . $priceStudio . '€' . "\n" .
                                       'Tarif artistes: ' . $artistCount . ' × ' . $priceStudioExtra . '€ = ' . ($artistCount * $priceStudioExtra) . '€' . "\n" .
                                       'TOTAL: ' . $totalPrice . '€';
                            }),
                    ])
                    ->columns(1),

                Section::make('Fonctionnalités')
                    ->description('Configuration des fonctionnalités')
                    ->schema([
                        TextInput::make('features')
                            ->label('Fonctionnalités (JSON)')
                            ->helperText('Format JSON avec la liste des fonctionnalités activées'),
                        TextInput::make('type')
                            ->label('Type d\'abonnement')
                            ->default('default')
                            ->disabled(),
                    ])
                    ->columns(1),
            ]);
    }
}
