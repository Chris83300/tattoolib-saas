<?php

namespace App\Filament\Admin\Resources\Complaints\Tables;

use App\Enums\ComplaintStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ComplaintsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('bookingRequest.id')
                    ->label('Demande #')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (ComplaintStatus $status): string => $status->getColor())
                    ->formatStateUsing(fn (ComplaintStatus $status): string => $status->getLabel()),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        ComplaintStatus::PENDING->value => ComplaintStatus::PENDING->getLabel(),
                        ComplaintStatus::INVESTIGATING->value => ComplaintStatus::INVESTIGATING->getLabel(),
                        ComplaintStatus::RESOLVED->value => ComplaintStatus::RESOLVED->getLabel(),
                        ComplaintStatus::REJECTED->value => ComplaintStatus::REJECTED->getLabel(),
                    ]),
            ])
            ->recordActions([
                Action::make('take_charge')
                    ->label('Prendre en charge')
                    ->icon('heroicon-o-hand-raised')
                    ->color('info')
                    ->visible(fn (Model $record) => $record->status === ComplaintStatus::PENDING)
                    ->action(function (Model $record) {
                        $record->update(['status' => ComplaintStatus::INVESTIGATING]);
                        \Filament\Notifications\Notification::make()
                            ->title('Réclamation prise en charge')
                            ->success()
                            ->send();
                    }),
                Action::make('resolve')
                    ->label('Résoudre')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Model $record) => $record->status === ComplaintStatus::INVESTIGATING)
                    ->form([
                        Textarea::make('admin_notes')
                            ->label('Notes de résolution')
                            ->required(),
                    ])
                    ->action(function (Model $record, array $data) {
                        $record->update([
                            'status' => ComplaintStatus::RESOLVED,
                            'admin_notes' => $data['admin_notes'],
                            'resolved_at' => now(),
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->title('Réclamation résolue')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Rejeter')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Model $record) => $record->status === ComplaintStatus::INVESTIGATING)
                    ->form([
                        Textarea::make('admin_notes')
                            ->label('Motif du rejet')
                            ->required(),
                    ])
                    ->action(function (Model $record, array $data) {
                        $record->update([
                            'status' => ComplaintStatus::REJECTED,
                            'admin_notes' => $data['admin_notes'],
                            'resolved_at' => now(),
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->title('Réclamation rejetée')
                            ->danger()
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
