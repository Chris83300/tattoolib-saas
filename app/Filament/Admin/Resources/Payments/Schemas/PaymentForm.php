<?php

namespace App\Filament\Admin\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_request_id')
                    ->label('Réservation (ID)')
                    ->required()
                    ->numeric(),
                TextInput::make('stripe_payment_intent_id')
                    ->label('Intent Stripe')
                    ->required(),
                TextInput::make('stripe_charge_id')
                    ->label('Charge Stripe'),
                TextInput::make('amount')
                    ->label('Montant')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->label('Devise')
                    ->required()
                    ->default('EUR'),
                Select::make('status')
                    ->label('Statut')
                    ->options([
                        'pending' => 'En attente',
                        'succeeded' => 'Réussi',
                        'failed' => 'Échoué',
                        'canceled' => 'Annulé',
                    ])
                    ->default('pending')
                    ->required(),
                Select::make('payment_type')
                    ->label('Type de paiement')
                    ->options([
                        'deposit' => 'Acompte',
                        'remaining' => 'Solde',
                        'full' => 'Paiement complet',
                    ])
                    ->default('deposit')
                    ->required(),
                Select::make('recipient_type')
                    ->label('Destinataire')
                    ->options(['artist' => 'Artiste', 'studio' => 'Studio']),
                TextInput::make('recipient_name')
                    ->label('Nom destinataire'),
                DateTimePicker::make('paid_at')
                    ->label('Payé le'),
                Textarea::make('failure_reason')
                    ->label('Raison de l\'échec')
                    ->columnSpanFull(),
            ]);
    }
}
