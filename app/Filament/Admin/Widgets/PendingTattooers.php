<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Tattooer;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Actions\Action;

class PendingTattooers extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Tattooer::query()
                    ->whereHas('user', function($q) {
                        $q->where('status', 'pending_verification');
                    })
                    ->with('user')
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('artist_name')
                    ->label('Tattooer')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->first_name . ' ' . $record->last_name;
                    }),

                Tables\Columns\TextColumn::make('user.status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending_verification' => 'warning',
                        'active' => 'success',
                        'suspended' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('siret')
                    ->label('SIRET')
                    ->copyable(),

                Tables\Columns\TextColumn::make('city')
                    ->label('Ville'),
            ])
            ->actions([
                Action::make('view')
                    ->label('Voir')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.resources.tattooers.edit', $record))
                    ->openUrlInNewTab(),
            ])
            ->paginated(false);
    }
}
