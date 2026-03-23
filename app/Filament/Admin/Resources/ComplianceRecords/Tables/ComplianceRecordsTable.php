<?php

namespace App\Filament\Admin\Resources\ComplianceRecords\Tables;

use Filament\Actions;
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
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hygiene' => 'primary',
                        'ars' => 'success',
                        'certibiocide' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'hygiene' => 'heroicon-o-academic-cap',
                        'ars' => 'heroicon-o-building-office-2',
                        'certibiocide' => 'heroicon-o-beaker',
                        default => '',
                    })
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
                    ->url(fn ($record) => $record->certificate_file_path ? route('admin.compliance.documents.serve', [$record, 'certificate_file_path']) : null)
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
                Actions\Action::make('validate')
                    ->label('Valider ✓')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($record): void {
                        $record->verified_by_admin = true;
                        $record->save();
                        $record->syncComplianceBadge();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Valider ce document ?')
                    ->modalDescription('Le badge "Conforme" sera attribué à l\'artiste si hygiène ET ARS sont tous deux validés.')
                    ->visible(fn ($record) => is_null($record->verified_at)),

                Actions\Action::make('invalidate')
                    ->label('Invalider ✗')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function ($record): void {
                        $record->verified_by_admin = false;
                        $record->save();
                        $record->syncComplianceBadge();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Invalider ce document ?')
                    ->visible(fn ($record) => !is_null($record->verified_at)),

                Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Bulk actions à implémenter plus tard (problème Filament v4)
            ])
            ->emptyStateActions([
                // Empty state actions à implémenter plus tard (problème Filament v4)
            ]);
    }
}
