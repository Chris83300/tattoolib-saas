<?php

namespace App\Filament\Admin\Resources\StudioArtists\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StudioArtistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('studio_id')
                    ->required()
                    ->numeric(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('artist_name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('bio')
                    ->columnSpanFull(),
                TextInput::make('specialties'),
                TextInput::make('stripe_connect_account_id'),
                Select::make('stripe_connect_status')
                    ->options([
            'not_connected' => 'Not connected',
            'onboarding' => 'Onboarding',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'reactivating' => 'Reactivating',
        ])
                    ->default('not_connected')
                    ->required(),
                DateTimePicker::make('stripe_connect_activated_at'),
                DateTimePicker::make('stripe_connect_last_transaction_at'),
                DateTimePicker::make('stripe_connect_deactivated_at'),
                Toggle::make('has_accepted_payment_terms')
                    ->required(),
                DateTimePicker::make('payment_terms_accepted_at'),
                Toggle::make('is_decision_maker')
                    ->required(),
                Select::make('compliance_status')
                    ->options([
            'non_compliant' => 'Non compliant',
            'compliant' => 'Compliant',
            'expiring_soon' => 'Expiring soon',
        ])
                    ->default('non_compliant')
                    ->required(),
                DateTimePicker::make('last_compliance_check_at'),
                Select::make('status')
                    ->options([
            'active' => 'Active',
            'inactive' => 'Inactive',
            'on_leave' => 'On leave',
            'deleted' => 'Deleted',
        ])
                    ->default('active')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                DatePicker::make('joined_at')
                    ->required(),
                DatePicker::make('left_at'),
                TextInput::make('working_schedule'),
                TextInput::make('total_appointments')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_revenue')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Toggle::make('credentials_managed_by_studio')
                    ->required(),
                Toggle::make('siret_verified')
                    ->required(),
                Toggle::make('stripe_onboarding_complete')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
