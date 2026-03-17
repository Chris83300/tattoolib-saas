<?php

namespace App\Filament\Admin\Resources\BookingRequests\Tables;

use App\Models\Tattooer;
use App\Models\Piercer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookingRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Réf.')
                    ->prefix('#')
                    ->sortable()
                    ->width('80px'),

                TextColumn::make('client_name')
                    ->label('Client')
                    ->getStateUsing(fn ($record) =>
                        $record->client?->user?->name
                        ?? $record->client?->user?->email
                        ?? 'Client #' . $record->client_id
                    )
                    ->searchable(query: fn (Builder $q, string $search) =>
                        $q->whereHas('client.user', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                    )
                    ->icon('heroicon-o-user'),

                TextColumn::make('artist')
                    ->label('Artiste')
                    ->getStateUsing(fn ($record) =>
                        $record->bookable?->pseudo
                        ?? $record->bookable?->name
                        ?? (class_basename($record->bookable_type ?? '') . ' #' . $record->bookable_id)
                    )
                    ->searchable(query: fn (Builder $q, string $search) =>
                        $q->whereHasMorph('bookable', [Tattooer::class, Piercer::class],
                            fn ($q) => $q->where('pseudo', 'like', "%{$search}%")
                                        ->orWhere('name', 'like', "%{$search}%")
                        )
                    )
                    ->icon('heroicon-o-paint-brush'),

                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending'      => 'gray',
                        'accepted'     => 'warning',
                        'deposit_paid' => 'info',
                        'in_progress'  => 'primary',
                        'completed'    => 'success',
                        'cancelled'    => 'danger',
                        'expired'      => 'danger',
                        default        => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending'      => 'En attente',
                        'accepted'     => 'Acceptée',
                        'deposit_paid' => 'Acompte payé',
                        'in_progress'  => 'En cours',
                        'completed'    => 'Terminée',
                        'cancelled'    => 'Annulée',
                        'expired'      => 'Expirée',
                        default        => $state,
                    })
                    ->sortable(),

                TextColumn::make('total_price')
                    ->label('Prix total')
                    ->money('EUR')
                    ->sortable()
                    ->placeholder('Non défini'),

                TextColumn::make('deposit_amount')
                    ->label('Acompte')
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('artist_type')
                    ->label('Type')
                    ->getStateUsing(fn ($record) => match (class_basename($record->bookable_type ?? '')) {
                        'Tattooer' => 'Tatoueur',
                        'Piercer'  => 'Pierceur',
                        default    => '—',
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Tatoueur' => 'primary',
                        'Pierceur' => 'warning',
                        default    => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'pending'      => 'En attente',
                        'accepted'     => 'Acceptée',
                        'deposit_paid' => 'Acompte payé',
                        'in_progress'  => 'En cours',
                        'completed'    => 'Terminée',
                        'cancelled'    => 'Annulée',
                        'expired'      => 'Expirée',
                    ]),

                SelectFilter::make('artist_type')
                    ->label("Type d'artiste")
                    ->options([
                        'App\\Models\\Tattooer' => 'Tatoueur',
                        'App\\Models\\Piercer'  => 'Pierceur',
                    ])
                    ->attribute('bookable_type'),

                Filter::make('this_month')
                    ->label('Ce mois')
                    ->query(fn ($query) => $query->whereMonth('created_at', now()->month))
                    ->toggle(),
            ])

            ->recordActions([
                EditAction::make()->label('Modifier'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Supprimer'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->emptyStateHeading('Aucune demande')
            ->emptyStateDescription('Aucune demande de réservation pour le moment.');
    }
}
