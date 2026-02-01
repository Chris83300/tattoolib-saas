<?php

namespace App\Filament\Admin\Resources\StudioArtists\Schemas;

use App\Models\StudioArtist;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StudioArtistInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('studio_id')
                    ->numeric(),
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('artist_name'),
                TextEntry::make('slug'),
                TextEntry::make('bio')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('stripe_connect_account_id')
                    ->placeholder('-'),
                TextEntry::make('stripe_connect_status')
                    ->badge(),
                TextEntry::make('stripe_connect_activated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('stripe_connect_last_transaction_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('stripe_connect_deactivated_at')
                    ->dateTime()
                    ->placeholder('-'),
                IconEntry::make('has_accepted_payment_terms')
                    ->boolean(),
                TextEntry::make('payment_terms_accepted_at')
                    ->dateTime()
                    ->placeholder('-'),
                IconEntry::make('is_decision_maker')
                    ->boolean(),
                TextEntry::make('compliance_status')
                    ->badge(),
                TextEntry::make('last_compliance_check_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('joined_at')
                    ->date(),
                TextEntry::make('left_at')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('total_appointments')
                    ->numeric(),
                TextEntry::make('total_revenue')
                    ->numeric(),
                IconEntry::make('credentials_managed_by_studio')
                    ->boolean(),
                IconEntry::make('siret_verified')
                    ->boolean(),
                IconEntry::make('stripe_onboarding_complete')
                    ->boolean(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (StudioArtist $record): bool => $record->trashed()),
            ]);
    }
}
