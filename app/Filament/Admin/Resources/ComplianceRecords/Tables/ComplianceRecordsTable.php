<?php

namespace App\Filament\Admin\Resources\ComplianceRecords\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class ComplianceRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // Type de document (badge coloré)
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'hygiene',
                        'success' => 'ars',
                        'warning' => 'certibiocide',
                    ])
                    ->icons([
                        'heroicon-o-academic-cap' => 'hygiene',
                        'heroicon-o-building-office-2' => 'ars',
                        'heroicon-o-beaker' => 'certibiocide',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'hygiene' => 'Formation Hygiène',
                        'ars' => 'Déclaration ARS',
                        'certibiocide' => 'Certibiocide TP2',
                        default => $state,
                    }),

                // Artiste concerné
                Tables\Columns\TextColumn::make('compliant.name')
                    ->label('Artiste')
                    ->searchable()
                    ->url(fn ($record) => $record->compliant_type === 'App\Models\Tattooer'
                        ? route('filament.admin.resources.tattooers.edit', $record->compliant_id)
                        : null)
                    ->color('primary'),

                // Numéro document (si applicable)
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Numéro')
                    ->copyable()
                    ->placeholder('Non renseigné')
                    ->fontFamily('mono'),

                // Dates
                Tables\Columns\TextColumn::make('issued_date')
                    ->label('Émis le')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('Expire le')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($state) => $state && $state < now()->addDays(30) ? 'danger' : 'success')
                    ->icon(fn ($state) => $state && $state < now()->addDays(30) ? 'heroicon-o-exclamation-triangle' : null),

                // Statut validation
                Tables\Columns\IconColumn::make('verified_by_admin')
                    ->label('Validé')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('warning'),

                // Document uploadé
                Tables\Columns\TextColumn::make('document')
                    ->label('Document')
                    ->getStateUsing(fn ($record) => $record->certificate_file_path ? 'Voir document' : 'Aucun')
                    ->url(fn ($record) => $record->certificate_file_path ? Storage::url($record->certificate_file_path) : null)
                    ->openUrlInNewTab()
                    ->color('info')
                    ->icon('heroicon-o-document'),

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('type')
                    ->label('Type de document')
                    ->options([
                        'hygiene' => 'Formation Hygiène',
                        'ars' => 'Déclaration ARS',
                        'certibiocide' => 'Certibiocide TP2',
                    ]),

                Tables\Filters\SelectFilter::make('tattooer_id')
                    ->label('Artiste')
                    ->relationship('tattooer', 'name')
                    ->searchable(),

                Tables\Filters\Filter::make('verified')
                    ->label('Documents validés')
                    ->query(fn ($query) => $query->where('verified_by_admin', true))
                    ->toggle(),

                Tables\Filters\Filter::make('not_verified')
                    ->label('Documents non validés')
                    ->query(fn ($query) => $query->where('verified_by_admin', false))
                    ->toggle(),

                Tables\Filters\Filter::make('expired')
                    ->label('Documents expirés')
                    ->query(fn ($query) => $query->where('expiry_date', '<', now()))
                    ->toggle(),

                Tables\Filters\Filter::make('expiring_soon')
                    ->label('Expire dans 30 jours')
                    ->query(fn ($query) => $query->where('expiry_date', '>', now())
                        ->where('expiry_date', '<=', now()->addDays(30)))
                    ->toggle(),

            ])
            ->actions([
                // Actions à implémenter plus tard (problème Filament v4)
            ])
            ->bulkActions([
                // Bulk actions à implémenter plus tard (problème Filament v4)
            ])
            ->emptyStateActions([
                // Empty state actions à implémenter plus tard (problème Filament v4)
            ]);
    }
}
