<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ComplianceRecord;
use App\Models\Tattooer;
use App\Models\Piercer;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Actions\Action;

class QualityAlerts extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ComplianceRecord::query()
                    ->where('verified_by_admin', false)
                    ->where('expires_at', '>=', now())
                    ->with(['complianceable'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('complianceable.name')
                    ->label('Artiste')
                    ->getStateUsing(function ($record) {
                        if ($record->complianceable instanceof Tattooer) {
                            return $record->complianceable->name ?? 'Tatoueur #' . $record->complianceable_id;
                        }
                        if ($record->complianceable instanceof Piercer) {
                            return $record->complianceable->name ?? 'Pierceur #' . $record->complianceable_id;
                        }
                        return 'Artiste #' . $record->complianceable_id;
                    }),

                Tables\Columns\TextColumn::make('certification_type')
                    ->label('Type de document')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hygiene_certificate' => 'success',
                        'insurance' => 'info',
                        'professional_license' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expiration')
                    ->dateTime('d/m/Y')
                    ->badge()
                    ->color(fn ($record) => $record->expires_at->diffInDays(now()) <= 30 ? 'danger' : 'warning'),
            ])
            ->actions([
                Action::make('review')
                    ->label('Examiner')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.resources.compliance-records.edit', $record)),
            ])
            ->paginated(false);
    }
}
