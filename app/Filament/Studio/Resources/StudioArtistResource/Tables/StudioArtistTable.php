<?php

namespace App\Filament\Studio\Resources\StudioArtistResource\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StudioArtistTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('artisan_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'piercer' ? 'Pierceur' : 'Tatoueur')
                    ->color(fn (string $state) => $state === 'piercer' ? 'info' : 'primary'),
                IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
                TextColumn::make('commission_rate')
                    ->label('Commission')
                    ->numeric(decimalPlaces: 2)
                    ->suffix('%')
                    ->placeholder('Défaut'),
                TextColumn::make('joined_at')
                    ->label('Rejoint le')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('artisan_type')
                    ->label('Type')
                    ->options([
                        'tattooer' => 'Tatoueur',
                        'piercer'  => 'Pierceur',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Actif'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('joined_at', 'desc');
    }
}
