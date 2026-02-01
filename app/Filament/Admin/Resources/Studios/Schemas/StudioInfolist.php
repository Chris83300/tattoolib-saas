<?php

namespace App\Filament\Admin\Resources\Studios\Schemas;

use App\Models\Studio;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StudioInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('address'),
                TextEntry::make('city'),
                TextEntry::make('postal_code'),
                TextEntry::make('country'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('website')
                    ->placeholder('-'),
                TextEntry::make('logo_url')
                    ->placeholder('-'),
                TextEntry::make('latitude')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('longitude')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('siret')
                    ->placeholder('-'),
                TextEntry::make('vat_number')
                    ->placeholder('-'),
                TextEntry::make('stripe_customer_id')
                    ->placeholder('-'),
                TextEntry::make('total_artists')
                    ->numeric(),
                IconEntry::make('is_active')
                    ->boolean(),
                IconEntry::make('is_verified')
                    ->boolean(),
                TextEntry::make('verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('payment_mode')
                    ->badge(),
                IconEntry::make('uses_accounting_module')
                    ->boolean(),
                TextEntry::make('payment_mode_changed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Studio $record): bool => $record->trashed()),
                TextEntry::make('user_id')
                    ->numeric(),
            ]);
    }
}
