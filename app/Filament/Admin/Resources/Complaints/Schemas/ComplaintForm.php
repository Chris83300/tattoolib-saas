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
                    ->options([
            'no_show' => 'No show',
            'quality' => 'Quality',
            'hygiene' => 'Hygiene',
            'payment' => 'Payment',
            'other' => 'Other',
        ])
                    ->default('no_show')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'investigating' => 'Investigating',
            'resolved' => 'Resolved',
            'rejected' => 'Rejected',
        ])
                    ->default('pending')
                    ->required(),
                Textarea::make('admin_notes')
                    ->columnSpanFull(),
                DateTimePicker::make('resolved_at'),
            ]);
    }
}
