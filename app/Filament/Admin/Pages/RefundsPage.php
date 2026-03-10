<?php

namespace App\Filament\Admin\Pages;

use App\Models\Payment;
use App\Models\Refund;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;

class RefundsPage extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationLabel = 'Remboursements';
    protected static ?string $title = 'Gestion des Remboursements';
    protected string $view = 'filament.admin.pages.refunds-page';
    protected static ?int $navigationSort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->where('status', 'succeeded')
                    ->whereDoesntHave('refunds', function ($query) {
                        $query->where('status', 'succeeded');
                    })
                    ->with(['bookingRequest.user', 'bookingRequest.tattooer', 'bookingRequest.piercer'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bookingRequest.user.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->bookingRequest?->user?->name ?? 'N/A';
                    })
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('recipient_name')
                    ->label('Artiste')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->money('EUR')
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\BadgeColumn::make('payment_type')
                    ->label('Type')
                    ->colors([
                        'warning' => 'deposit',
                        'success' => 'full_payment',
                        'gray' => fn ($state) => !in_array($state, ['deposit', 'full_payment']),
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'deposit' => 'Acompte',
                        'full_payment' => 'Paiement complet',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Date paiement')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->description('Heure locale'),
                Tables\Columns\TextColumn::make('stripe_payment_intent_id')
                    ->label('Payment Intent')
                    ->copyable()
                    ->copyMessage('ID copié!')
                    ->copyMessageDuration(1500)
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('paid_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_type')
                    ->label('Type de paiement')
                    ->options([
                        'deposit' => 'Acompte',
                        'full_payment' => 'Paiement complet',
                    ])
                    ->default('all'),
                Tables\Filters\Filter::make('paid_at')
                    ->label('Période de paiement')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Date de début')
                            ->closeOnDateSelection()
                            ->native(false),
                        Forms\Components\DatePicker::make('until')
                            ->label('Date de fin')
                            ->closeOnDateSelection()
                            ->native(false),
                    ])
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators['from'] = 'Du: ' . $data['from'];
                        }
                        if ($data['until'] ?? null) {
                            $indicators['until'] = 'Au: ' . $data['until'];
                        }
                        return $indicators;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('paid_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('paid_at', '<=', $date),
                            );
                    }),
                Tables\Filters\SelectFilter::make('recipient_type')
                    ->label('Type d\'artiste')
                    ->options([
                        'tattooer' => 'Tatoueur',
                        'piercer' => 'Piercer',
                        'studio' => 'Studio',
                    ]),
            ])
            ->actions([
                Action::make('view_details')
                    ->label('Détails')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading('Détails du paiement')
                    ->modalContent(function (Payment $record) {
                        return view('filament.admin.pages.payment-details', ['payment' => $record]);
                    })
                    ->modalWidth('2xl'),
                Action::make('refund')
                    ->label('Rembourser')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Confirmer le remboursement')
                    ->modalDescription('Êtes-vous sûr de vouloir rembourser ce paiement ? Cette action est irréversible.')
                    ->modalSubmitActionLabel('Oui, rembourser')
                    ->modalCancelActionLabel('Annuler')
                    ->form([
                        Forms\Components\Placeholder::make('info')
                            ->label('Informations de remboursement')
                            ->content('Veuillez remplir les informations ci-dessous pour traiter le remboursement.'),
                        Forms\Components\TextInput::make('amount')
                            ->label('Montant du remboursement (€)')
                            ->numeric()
                            ->required()
                            ->rules(['min:1', 'max:999999'])
                            ->helperText('Montant maximum: 999999€')
                            ->prefix('€'),
                        Forms\Components\Textarea::make('reason')
                            ->label('Motif du remboursement')
                            ->required()
                            ->rows(3)
                            ->placeholder('Veuillez indiquer le motif du remboursement...')
                            ->maxLength(500),
                    ])
                    ->action(function (Payment $record, array $data) {
                        try {
                            // Validation personnalisée
                            if ($data['amount'] > $record->amount) {
                                Notification::make()
                                    ->title('Erreur de validation')
                                    ->body("Le montant du remboursement ne peut pas dépasser le montant du paiement ({$record->amount}€)")
                                    ->danger()
                                    ->send();
                                return;
                            }

                            // Créer le remboursement via Stripe
                            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

                            $refund = $stripe->refunds->create([
                                'payment_intent' => $record->stripe_payment_intent_id,
                                'amount' => (int) ($data['amount'] * 100), // Conversion en centimes
                                'reason' => 'requested_by_customer',
                                'metadata' => [
                                    'admin_reason' => $data['reason'],
                                    'payment_id' => $record->id,
                                ],
                            ]);

                            // Logger le remboursement en base
                            Refund::create([
                                'payment_id' => $record->id,
                                'stripe_refund_id' => $refund->id,
                                'amount' => $data['amount'],
                                'reason' => $data['reason'],
                                'status' => $refund->status,
                                'admin_id' => \Filament\Facades\Filament::auth()->id(),
                                'processed_at' => now(),
                            ]);

                            Notification::make()
                                ->title('Remboursement effectué')
                                ->body("Remboursement de {$data['amount']}€ traité avec succès (ID: {$refund->id})")
                                ->success()
                                ->send();

                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Erreur de remboursement')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->emptyStateHeading('Aucun paiement éligible au remboursement')
            ->emptyStateDescription('Tous les paiements avec des remboursements réussis sont filtrés.')
            ->emptyStateActions([
                Action::make('reset_filters')
                    ->label('Réinitialiser les filtres')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->action(function () {
                        // This will reset all filters
                    }),
            ])
            ->bulkActions([
                // Pas d'actions de masse pour les remboursements
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Exporter')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->action(function () {
                        // TODO: Implement export functionality
                        Notification::make()
                            ->title('Export à venir')
                            ->body('Cette fonctionnalité sera bientôt disponible.')
                            ->info()
                            ->send();
                    }),
            ]);
    }
}
