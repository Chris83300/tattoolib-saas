<?php

namespace App\Filament\Admin\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('booking_request_id')
                    ->label('Réservation')
                    ->numeric()
                    ->sortable()
                    ->url(fn ($record) => '/admin/booking-requests/'.$record->booking_request_id)
                    ->openUrlInNewTab(false),
                TextColumn::make('user.name')
                    ->label('Client')
                    ->searchable()
                    ->getStateUsing(fn ($record) => $record->user->name ?? 'N/A'),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->colors([
                        'primary' => 'deposit',
                        'success' => 'final_payment',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'deposit' => 'Acompte',
                        'final_payment' => 'Paiement final',
                        default => $state,
                    }),
                TextColumn::make('amount')
                    ->label('Montant')
                    ->numeric()
                    ->sortable()
                    ->prefix('€ ')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', ' ')),
                TextColumn::make('currency')
                    ->label('Devise')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->colors([
                        'success' => 'completed',
                        'warning' => 'pending',
                        'danger' => 'failed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'completed' => 'Complété',
                        'pending' => 'En attente',
                        'failed' => 'Échoué',
                        default => $state,
                    }),
                TextColumn::make('payment_method')
                    ->label('Méthode')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'stripe' => 'Stripe',
                        'cash' => 'Espèces',
                        'other' => 'Autre',
                        default => $state,
                    }),
                TextColumn::make('paid_at')
                    ->label('Payé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Non payé'),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
