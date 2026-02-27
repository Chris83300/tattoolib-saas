<?php

namespace App\Filament\Studio\Resources\StudioArtistResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StudioArtistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('user.name')
                ->label('Nom')
                ->disabled(),
            TextInput::make('user.email')
                ->label('Email')
                ->disabled(),
            Select::make('artisan_type')
                ->label('Type')
                ->options([
                    'tattooer' => 'Tatoueur',
                    'piercer'  => 'Pierceur',
                ])
                ->disabled(),
            Toggle::make('is_active')
                ->label('Actif'),
            TextInput::make('commission_rate')
                ->label('Taux de commission (%)')
                ->numeric()
                ->step(0.01)
                ->placeholder('Défaut plateforme'),
        ]);
    }
}
