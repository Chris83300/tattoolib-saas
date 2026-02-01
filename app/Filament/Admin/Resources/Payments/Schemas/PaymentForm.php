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
                    ->required()
                    ->numeric(),
                TextInput::make('stripe_payment_intent_id')
                    ->required(),
                TextInput::make('stripe_charge_id'),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->required()
                    ->default('EUR'),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'succeeded' => 'Succeeded',
            'failed' => 'Failed',
            'canceled' => 'Canceled',
        ])
                    ->default('pending')
                    ->required(),
                Select::make('payment_type')
                    ->options(['deposit' => 'Deposit', 'remaining' => 'Remaining', 'full' => 'Full'])
                    ->default('deposit')
                    ->required(),
                Select::make('recipient_type')
                    ->options(['artist' => 'Artist', 'studio' => 'Studio']),
                TextInput::make('recipient_name'),
                DateTimePicker::make('paid_at'),
                Textarea::make('failure_reason')
                    ->columnSpanFull(),
            ]);
    }
}
