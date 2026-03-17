<?php

namespace App\Filament\Admin\Resources\Appointments;

use App\Filament\Admin\Resources\Appointments\Pages\ManageAppointments;
use App\Models\Appointment;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|UnitEnum|null $navigationGroup = 'Réservations';

    protected static ?string $modelLabel = 'Rendez-vous';

    protected static ?string $pluralModelLabel = 'Rendez-vous';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // Formulaire readonly - les RDV sont créés via le système de booking
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('artist_name')
                    ->label('Artiste')
                    ->getStateUsing(function ($record) {
                        if ($record->bookable_type === 'App\Models\Tattooer') {
                            return $record->bookable->name ?? 'Tattooer #' . $record->bookable_id;
                        } elseif ($record->bookable_type === 'App\Models\Pierceur') {
                            return $record->bookable->name ?? 'Pierceur #' . $record->bookable_id;
                        }
                        return 'Artiste #' . $record->bookable_id;
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'completed' => 'primary',
                        'cancelled' => 'danger',
                        'client_no_show' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending' => 'En attente',
                        'confirmed' => 'Confirmé',
                        'completed' => 'Terminé',
                        'cancelled' => 'Annulé',
                        'client_no_show' => 'Absent',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Montant')
                    ->money('EUR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration')
                    ->label('Durée')
                    ->getStateUsing(fn ($record) => $record->start_time && $record->end_time
                        ? $record->start_time->diffInMinutes($record->end_time) . ' min'
                        : 'N/A'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'pending' => 'En attente',
                        'confirmed' => 'Confirmé',
                        'completed' => 'Terminé',
                        'cancelled' => 'Annulé',
                        'client_no_show' => 'Absent',
                    ]),

                Tables\Filters\Filter::make('today')
                    ->label('Aujourd\'hui')
                    ->query(fn ($query) => $query->whereDate('start_datetime', now()))
                    ->toggle(),

                Tables\Filters\Filter::make('this_week')
                    ->label('Cette semaine')
                    ->query(fn ($query) => $query->whereBetween('start_datetime', [now()->startOfWeek(), now()->endOfWeek()]))
                    ->toggle(),

                Tables\Filters\Filter::make('this_month')
                    ->label('Ce mois')
                    ->query(fn ($query) => $query->whereMonth('start_datetime', now()->month))
                    ->toggle(),
            ])
            ->actions([
                // Actions à implémenter plus tard
            ])
            ->bulkActions([
                //
            ])
            ->emptyStateActions([
                //
            ])
            ->striped()
            ->poll('60s');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAppointments::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery();
    }
}
