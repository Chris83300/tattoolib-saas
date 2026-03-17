<?php
namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ConversationResource\Pages;
use App\Models\Conversation;
use BackedEnum;
use UnitEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;

class ConversationResource extends Resource
{
    protected static ?string $model = Conversation::class;
    protected static ?string $navigationLabel = 'Support & Réclamations';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-lifebuoy';
    protected static UnitEnum|string|null $navigationGroup = 'Communication';
    protected static ?int $navigationSort = 2;

    // Badge : conversations support avec remboursement en attente
    public static function getNavigationBadge(): ?string
    {
        $count = Conversation::where('type', Conversation::TYPE_SUPPORT)
            ->whereHas('bookingRequest', fn ($q) =>
                $q->where('status', 'cancelled')
                  ->whereNull('refund_processed_at')
                  ->whereNotNull('deposit_paid_at')
            )
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                // ✅ UNIQUEMENT les conversations support (annulations/réclamations)
                Conversation::query()
                    ->where('type', Conversation::TYPE_SUPPORT)
                    ->with([
                        'bookingRequest',
                        'participants',
                        'messages' => fn ($q) => $q->latest()->limit(1),
                    ])
                    ->latest('updated_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('booking_ref')
                    ->label('Demande')
                    ->getStateUsing(fn ($record) =>
                        $record->bookingRequest
                            ? '#' . $record->bookingRequest->id
                            : '—'
                    ),

                Tables\Columns\TextColumn::make('participants_list')
                    ->label('Participants')
                    ->getStateUsing(fn ($record) =>
                        $record->participants->map(fn ($u) => $u->name ?? $u->pseudo ?? '?')->implode(', ') ?: '—'
                    ),

                Tables\Columns\TextColumn::make('refund_status')
                    ->label('Remboursement')
                    ->badge()
                    ->getStateUsing(fn ($record) => match (true) {
                        !$record->bookingRequest                                    => '—',
                        (bool) $record->bookingRequest->refund_processed_at        => 'Traité',
                        ($record->bookingRequest->refund_amount ?? 0) > 0          => 'En attente',
                        default                                                    => 'Aucun',
                    })
                    ->color(fn (string $state) => match ($state) {
                        'Traité'     => 'success',
                        'En attente' => 'warning',
                        default      => 'gray',
                    }),

                Tables\Columns\TextColumn::make('last_activity')
                    ->label('Dernière activité')
                    ->getStateUsing(fn ($record) =>
                        $record->messages->first()?->created_at?->diffForHumans() ?? '—'
                    ),
            ])
            ->filters([
                Tables\Filters\Filter::make('refund_pending')
                    ->label('Remboursements en attente')
                    ->query(fn ($query) => $query->whereHas('bookingRequest', fn ($sq) =>
                        $sq->where('refund_amount', '>', 0)->whereNull('refund_processed_at')
                    ))
                    ->toggle(),
            ])
            ->actions([
                Action::make('open')
                    ->label('Ouvrir')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record) => static::getUrl('view', ['record' => $record])),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('📋 Contexte de la demande')
                ->schema([
                    TextEntry::make('booking_ref')
                        ->label('Demande')
                        ->getStateUsing(fn ($record) =>
                            $record->bookingRequest
                                ? '#' . $record->bookingRequest->id . ' — ' . ($record->bookingRequest->description ?? '')
                                : '—'
                        ),

                    TextEntry::make('booking_status')
                        ->label('Statut demande')
                        ->getStateUsing(fn ($record) => $record->bookingRequest?->status ?? '—'),

                    TextEntry::make('cancelled_by')
                        ->label('Annulé par')
                        ->getStateUsing(fn ($record) => match ($record->bookingRequest?->cancelled_by) {
                            'client' => '👤 Client',
                            'artist' => '🎨 Artiste',
                            'admin'  => '⚙️ Admin',
                            default  => '—',
                        }),

                    TextEntry::make('refund_summary')
                        ->label('Remboursement')
                        ->getStateUsing(fn ($record) =>
                            $record->bookingRequest
                                ? number_format((float) ($record->bookingRequest->refund_amount ?? 0), 2, ',', ' ') . '€'
                                  . ' (' . ($record->bookingRequest->refund_percent ?? 0) . '%)'
                                  . ($record->bookingRequest->refund_processed_at ? ' — ✅ Traité' : ' — ⏳ En attente')
                                : '—'
                        ),
                ])
                ->columns(2),

            Section::make('💬 Messages')
                ->schema([
                    RepeatableEntry::make('messages')
                        ->schema([
                            TextEntry::make('sender_label')
                                ->label('')
                                ->getStateUsing(fn ($record) => match ($record->sender_type ?? null) {
                                    'admin' => '🛡️ Équipe Ink&Pik',
                                    default => $record->sender?->name ?? $record->sender?->pseudo ?? 'Utilisateur',
                                })
                                ->weight(\Filament\Support\Enums\FontWeight::Bold)
                                ->color(fn ($record) =>
                                    ($record->sender_type ?? '') === 'admin' ? 'primary' : 'gray'
                                ),

                            TextEntry::make('created_at')
                                ->label('')
                                ->since()
                                ->color('gray'),

                            TextEntry::make('content')
                                ->label('')
                                ->markdown()
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->contained(false),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConversations::route('/'),
            'view'  => Pages\ViewConversation::route('/{record}'),
        ];
    }
}
