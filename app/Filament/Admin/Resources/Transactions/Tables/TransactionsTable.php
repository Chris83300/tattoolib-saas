<?php

namespace App\Filament\Admin\Resources\Transactions\Tables;

use App\Models\Transaction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\Exports\ExportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('processed_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('artist.name')
                    ->label('Artiste')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        return $record->artist ? $record->artist->name : 'N/A';
                    }),
                TextColumn::make('artist_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tattooer' => 'blue',
                        'piercer' => 'green',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'tattooer' => 'Tatoueur',
                        'piercer' => 'Pierceur',
                        default => $state,
                    }),
                TextColumn::make('payment_type')
                    ->label('Type paiement')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'deposit' => 'warning',
                        'full_payment' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'deposit' => 'Acompte',
                        'full_payment' => 'Paiement complet',
                        default => $state,
                    }),
                TextColumn::make('amount')
                    ->label('Montant total')
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('commission_amount')
                    ->label('Commission (7%)')
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('net_amount')
                    ->label('Net artiste')
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'succeeded' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('refund_status')
                    ->label('Remboursement')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'none' => 'success',
                        'partial' => 'warning',
                        'full' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'none' => 'Aucun',
                        'partial' => 'Partiel',
                        'full' => 'Complet',
                        default => $state,
                    }),
                TextColumn::make('stripe_payment_intent_id')
                    ->label('Payment Intent')
                    ->copyable()
                    ->limit(15)
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('artist_type')
                    ->label('Type d\'artiste')
                    ->options([
                        'tattooer' => 'Tatoueur',
                        'piercer' => 'Pierceur',
                    ]),
                SelectFilter::make('payment_type')
                    ->label('Type de paiement')
                    ->options([
                        'deposit' => 'Acompte',
                        'full_payment' => 'Paiement complet',
                    ]),
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'succeeded' => 'Succès',
                        'pending' => 'En attente',
                        'failed' => 'Échec',
                    ]),
                SelectFilter::make('refund_status')
                    ->label('Statut remboursement')
                    ->options([
                        'none' => 'Aucun',
                        'partial' => 'Partiel',
                        'full' => 'Complet',
                    ]),
                \Filament\Tables\Filters\Filter::make('period')
                    ->label('Période')
                    ->form([
                        DatePicker::make('from')
                            ->label('Du'),
                        DatePicker::make('until')
                            ->label('Au'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('processed_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('processed_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators['from'] = 'Du ' . $data['from'];
                        }
                        if ($data['until'] ?? null) {
                            $indicators['until'] = 'Au ' . $data['until'];
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->toolbarActions([
                ExportAction::make('export')
                    ->label('Export CSV')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->exporter(\App\Filament\Exports\TransactionExporter::class)
                    ->fileName(fn () => 'transactions_' . now()->format('Y-m-d_His') . '.csv')
                    ->columnMapping(false),
            ])
            ->bulkActions([
                //
            ]);
    }
}
