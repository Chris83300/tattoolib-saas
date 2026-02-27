<?php

namespace App\Filament\Studio\Resources\BookingRequestResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookingRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('status')
                ->label('Statut')
                ->disabled(),
        ]);
    }
}
