<?php

namespace App\Filament\Admin\Resources\DataProcessingRecords\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DataProcessingRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('purpose')
                    ->label('Finalité')
                    ->limit(50),

                TextColumn::make('legal_basis')
                    ->label('Base légale')
                    ->badge()
                    ->limit(30),

                TextColumn::make('retention_period')
                    ->label('Conservation'),

                IconColumn::make('transfers_outside_eu')
                    ->label('Hors UE')
                    ->boolean(),

                IconColumn::make('requires_dpia')
                    ->label('AIPD')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('requires_dpia')->label('AIPD requise'),
                TernaryFilter::make('transfers_outside_eu')->label('Transferts hors UE'),
                TernaryFilter::make('is_active')->label('Actif'),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }
}
