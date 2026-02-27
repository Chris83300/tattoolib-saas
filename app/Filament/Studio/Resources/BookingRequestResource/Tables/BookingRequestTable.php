<?php

namespace App\Filament\Studio\Resources\BookingRequestResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingRequestTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.user.name')
                    ->label('Client')
                    ->searchable(),
                TextColumn::make('bookable.user.name')
                    ->label('Artiste')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn ($state) => match ($state?->value ?? $state) {
                        'pending'                       => 'warning',
                        'accepted', 'deposit_paid'      => 'success',
                        'completed'                     => 'info',
                        'cancelled', 'refused'          => 'danger',
                        default                         => 'gray',
                    }),
                TextColumn::make('deposit_amount')
                    ->label('Acompte')
                    ->money('EUR', divideBy: 100),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options(
                        collect(['pending', 'accepted', 'deposit_requested', 'deposit_paid', 'completed', 'cancelled', 'refused'])
                            ->mapWithKeys(fn ($s) => [$s => ucfirst($s)])
                            ->toArray()
                    ),
            ]);
    }
}
