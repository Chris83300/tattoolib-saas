<?php

namespace App\Filament\Admin\Pages;

use App\Models\Payment;
use App\Models\Refund;
use Filament\Forms;
use Filament\Forms\Form;
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('bookingRequest.user.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('recipient_name')
                    ->label('Artiste')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'deposit' => 'warning',
                        'full_payment' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Date paiement')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stripe_payment_intent_id')
                    ->label('Payment Intent')
                    ->copyable()
                    ->limit(20),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_type')
                    ->label('Type de paiement')
                    ->options([
                        'deposit' => 'Acompte',
                        'full_payment' => 'Paiement complet',
                    ]),
                Tables\Filters\Filter::make('paid_at')
                    ->label('Date de paiement')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Du'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Au'),
                    ])
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
            ])
            ->actions([
                Action::make('refund')
                    ->label('Rembourser')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('danger')
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->label('Montant du remboursement (€)')
                            ->numeric()
                            ->required()
                            ->rules(['min:1', 'max:' . fn ($record) => $record->amount])
                            ->helperText('Montant maximum: ' . fn ($record) => $record->amount . '€'),
                        Forms\Components\Textarea::make('reason')
                            ->label('Motif du remboursement')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Payment $record, array $data) {
                        try {
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
            ->bulkActions([
                //
            ]);
    }
}
