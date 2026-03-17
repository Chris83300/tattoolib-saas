<?php
namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CancellationResource\Pages;
use App\Models\BookingRequest;
use BackedEnum;
use UnitEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;

class CancellationResource extends Resource
{
    protected static ?string $model = BookingRequest::class;
    protected static ?string $navigationLabel = 'Annulations & Remboursements';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-x-circle';
    protected static UnitEnum|string|null $navigationGroup = 'Finances';
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'cancellations';

    public static function getNavigationBadge(): ?string
    {
        $count = BookingRequest::where('status', 'cancelled')
            ->whereNull('refund_processed_at')
            ->whereNotNull('deposit_paid_at')
            ->where('refund_amount', '>', 0)
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function infolist(Schema $schema): Schema
    {
        $stripeBase = app()->isProduction()
            ? 'https://dashboard.stripe.com/payments/'
            : 'https://dashboard.stripe.com/test/payments/';

        return $schema->components([
            TextEntry::make('client_name')
                ->label('Client')
                ->getStateUsing(fn ($record) => $record->client?->user?->name ?? $record->client?->user?->pseudo ?? '—'),
            TextEntry::make('client.user.email')
                ->label('Email client')
                ->copyable()
                ->placeholder('—'),
            TextEntry::make('artist_name')
                ->label('Artiste')
                ->getStateUsing(fn ($record) =>
                    $record->bookable?->pseudo
                    ?? $record->bookable?->name
                    ?? ($record->bookable_id ? 'ID ' . $record->bookable_id : '—')
                ),
            TextEntry::make('artist_email')
                ->label('Email artiste')
                ->getStateUsing(fn ($record) => $record->bookable?->user?->email ?? '—')
                ->copyable(),

            TextEntry::make('id')->label('Référence')->prefix('#'),
            TextEntry::make('description')->label('Description')->placeholder('—'),
            TextEntry::make('body_zone')->label('Zone')->placeholder('—'),
            TextEntry::make('cancelled_by')
                ->label('Annulé par')
                ->formatStateUsing(fn ($s) => match($s) {
                    'client' => 'Client',
                    'artist' => 'Artiste',
                    'admin'  => 'Admin',
                    default  => $s ?? '—',
                })
                ->badge()
                ->color(fn ($state) => match($state) {
                    'Client'  => 'warning',
                    'Artiste' => 'danger',
                    'Admin'   => 'info',
                    default   => 'gray',
                }),
            TextEntry::make('cancelled_at')
                ->label('Date d\'annulation')
                ->dateTime('d/m/Y H:i')
                ->placeholder('—'),
            TextEntry::make('cancellation_reason')
                ->label('Message d\'annulation')
                ->placeholder('Aucun message')
                ->columnSpanFull(),

            TextEntry::make('estimated_total_price')
                ->label('Prix total estimé')
                ->money('EUR')
                ->placeholder('—'),
            TextEntry::make('total_deposit_amount')
                ->label('Acompte versé')
                ->money('EUR')
                ->placeholder('—'),
            TextEntry::make('refund_amount')
                ->label('Montant à rembourser')
                ->money('EUR')
                ->placeholder('Non défini')
                ->color(fn ($record) => $record->refund_amount > 0 ? 'success' : 'gray'),
            TextEntry::make('refund_percent')
                ->label('Taux')
                ->suffix('%')
                ->placeholder('—'),
            TextEntry::make('stripe_payment_intent_id')
                ->label('Payment Intent Stripe')
                ->placeholder('—')
                ->copyable()
                ->url(fn ($record) => $record->stripe_payment_intent_id
                    ? $stripeBase . $record->stripe_payment_intent_id
                    : null
                )
                ->openUrlInNewTab(),
            TextEntry::make('refund_processed_at')
                ->label('Remboursé le')
                ->dateTime('d/m/Y H:i')
                ->placeholder('⏳ Non traité')
                ->color(fn ($record) => $record->refund_processed_at ? 'success' : 'warning'),
        ]);
    }

    public static function table(Table $table): Table
    {
        $stripeBase = app()->isProduction()
            ? 'https://dashboard.stripe.com/payments/'
            : 'https://dashboard.stripe.com/test/payments/';

        return $table
            ->query(
                BookingRequest::query()
                    ->where('status', 'cancelled')
                    ->with(['client.user', 'bookable.user', 'conversation'])
                    ->latest('cancelled_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->prefix('#'),

                Tables\Columns\TextColumn::make('cancelled_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('cancelled_by')
                    ->label('Annulé par')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'client' => 'warning',
                        'artist' => 'danger',
                        'admin'  => 'info',
                        default  => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match($state) {
                        'client' => '👤 Client',
                        'artist' => '🎨 Artiste',
                        'admin'  => '⚙️ Admin',
                        default  => $state ?? '—',
                    }),

                Tables\Columns\TextColumn::make('client.user.pseudo')
                    ->label('Client')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('artist_display')
                    ->label('Artiste')
                    ->searchable(query: fn ($query, $search) =>
                        $query->whereHasMorph('bookable', [\App\Models\Tattooer::class, \App\Models\Piercer::class],
                            fn ($q) => $q->where('pseudo', 'like', "%{$search}%")->orWhere('name', 'like', "%{$search}%")
                        )
                    )
                    ->getStateUsing(fn ($record) =>
                        $record->bookable?->pseudo
                        ?? $record->bookable?->name
                        ?? ($record->bookable_type ? class_basename($record->bookable_type) . ' #' . $record->bookable_id : '—')
                    ),

                Tables\Columns\TextColumn::make('total_deposit_amount')
                    ->label('Acompte')
                    ->money('EUR')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('refund_amount')
                    ->label('Remboursement')
                    ->money('EUR')
                    ->sortable()
                    ->placeholder('—')
                    ->color(fn ($record) => match(true) {
                        $record->refund_amount > 0          => 'success',
                        ($record->total_deposit_amount ?? 0) > 0 => 'danger',
                        default                             => 'gray',
                    })
                    ->description(fn ($record) =>
                        $record->refund_percent !== null ? $record->refund_percent . '%' : null
                    ),

                Tables\Columns\TextColumn::make('refund_status_display')
                    ->label('Statut remboursement')
                    ->badge()
                    ->getStateUsing(fn ($record) => match(true) {
                        $record->refund_processed_at !== null          => 'Traité',
                        ($record->refund_amount ?? 0) > 0              => 'En attente',
                        ($record->total_deposit_amount ?? 0) > 0       => 'Non remboursable',
                        default                                        => 'Aucun acompte',
                    })
                    ->color(fn ($state) => match($state) {
                        'Traité'          => 'success',
                        'En attente'      => 'warning',
                        'Non remboursable' => 'danger',
                        default           => 'gray',
                    }),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('cancelled_by')
                    ->label('Annulé par')
                    ->options([
                        'client' => '👤 Client',
                        'artist' => '🎨 Artiste',
                        'admin'  => '⚙️ Admin',
                    ]),

                Tables\Filters\Filter::make('refund_pending')
                    ->label('Remboursements en attente')
                    ->query(fn ($query) => $query
                        ->where('refund_amount', '>', 0)
                        ->whereNull('refund_processed_at')
                    )
                    ->toggle(),

                Tables\Filters\Filter::make('refund_done')
                    ->label('Remboursements traités')
                    ->query(fn ($query) => $query->whereNotNull('refund_processed_at'))
                    ->toggle(),
            ])

            ->actions([
                Action::make('view')
                    ->label('Détail')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => static::getUrl('view', ['record' => $record])),

                Action::make('chat')
                    ->label('Chat')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('info')
                    ->visible(fn ($record) => (bool) $record->conversation)
                    ->url(fn ($record) => $record->conversation
                        ? route('admin.conversation.show', $record->conversation)
                        : '#'
                    )
                    ->openUrlInNewTab(),

                Action::make('open_stripe')
                    ->label('Stripe')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->visible(fn ($record) => (bool) $record->stripe_payment_intent_id)
                    ->url(fn ($record) => $stripeBase . $record->stripe_payment_intent_id)
                    ->openUrlInNewTab(),

                Action::make('refund')
                    ->label('Rembourser')
                    ->icon('heroicon-o-currency-euro')
                    ->color('success')
                    ->visible(fn ($record) =>
                        ($record->refund_amount ?? 0) > 0
                        && !$record->refund_processed_at
                        && $record->stripe_payment_intent_id
                    )
                    ->form([
                        \Filament\Forms\Components\TextInput::make('refund_amount')
                            ->label('Montant à rembourser (€)')
                            ->numeric()
                            ->step(0.01)
                            ->default(fn ($record) => $record->refund_amount)
                            ->required()
                            ->helperText(fn ($record) =>
                                'Acompte versé : ' . number_format($record->total_deposit_amount ?? 0, 2, ',', ' ') . ' €'
                                . ' — Recommandé : ' . number_format($record->refund_amount ?? 0, 2, ',', ' ') . ' €'
                            ),
                        \Filament\Forms\Components\Textarea::make('note')
                            ->label('Note interne')
                            ->placeholder('Raison du remboursement...'),
                    ])
                    ->action(function ($record, array $data) {
                        try {
                            app(\App\Services\BookingRequestService::class)
                                ->processStripeRefund($record, (float) $data['refund_amount']);
                            $record->update([
                                'refund_processed_at' => now(),
                                'refund_amount'       => $data['refund_amount'],
                            ]);
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('✅ Remboursement effectué')
                                ->body(number_format($data['refund_amount'], 2, ',', ' ') . '€ remboursé au client')
                                ->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('❌ Erreur Stripe')
                                ->body($e->getMessage())
                                ->persistent()
                                ->send();
                        }
                    }),

                Action::make('mark_refunded')
                    ->label('Marquer remboursé')
                    ->icon('heroicon-o-check')
                    ->color('gray')
                    ->visible(fn ($record) =>
                        ($record->refund_amount ?? 0) > 0 && !$record->refund_processed_at
                    )
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['refund_processed_at' => now()])),
            ])

            ->bulkActions([
                BulkActionGroup::make([
                    Action::make('mark_all_refunded')
                        ->label('Marquer comme remboursés')
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each(
                            fn ($r) => $r->update(['refund_processed_at' => now()])
                        ))
                        ->requiresConfirmation(),
                ]),
            ])

            ->defaultSort('cancelled_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCancellations::route('/'),
            'view'  => Pages\ViewCancellation::route('/{record}'),
        ];
    }
}
