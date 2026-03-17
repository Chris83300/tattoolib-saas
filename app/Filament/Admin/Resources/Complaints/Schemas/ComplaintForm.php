<?php

namespace App\Filament\Admin\Resources\Complaints\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ComplaintForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_request_id')
                    ->numeric(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'no_show' => 'Absence client',
                        'quality' => 'Qualité',
                        'hygiene' => 'Hygiène',
                        'payment' => 'Paiement',
                        'other' => 'Autre',
                    ])
                    ->default('no_show')
                    ->required(),
                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Statut')
                    ->options([
                        'pending' => 'En attente',
                        'investigating' => 'En cours d\'enquête',
                        'resolved' => 'Résolu',
                        'rejected' => 'Rejeté',
                    ])
                    ->default('pending')
                    ->required(),
                Textarea::make('admin_notes')
                    ->label('Notes administrateur')
                    ->columnSpanFull(),
                DateTimePicker::make('resolved_at')
                    ->label('Résolu le'),
            ]);
    }
}
