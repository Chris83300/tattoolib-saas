<?php
namespace App\Filament\Admin\Resources\CancellationResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use App\Filament\Admin\Resources\CancellationResource;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class ViewCancellation extends ViewRecord
{
    protected static string $resource = CancellationResource::class;

    protected function getHeaderActions(): array
    {
        $stripeBase = app()->isProduction()
            ? 'https://dashboard.stripe.com/payments/'
            : 'https://dashboard.stripe.com/test/payments/';

        return [
            Actions\Action::make('view_request')
                ->label('Voir la demande complète')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->url(fn () => route('filament.admin.resources.booking-requests.edit', $this->record))
                ->openUrlInNewTab(),

            Actions\Action::make('open_chat')
                ->label('Voir le chat')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('info')
                ->visible(fn () => (bool) $this->record->conversation)
                ->url(fn () => route('admin.conversation.show', $this->record->conversation))
                ->openUrlInNewTab(),

            Actions\Action::make('open_stripe')
                ->label('Voir dans Stripe')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->visible(fn () => (bool) $this->record->stripe_payment_intent_id)
                ->url(fn () => $stripeBase . $this->record->stripe_payment_intent_id)
                ->openUrlInNewTab(),

            Actions\Action::make('send_admin_message')
                ->label('Envoyer un message')
                ->icon('heroicon-o-paper-airplane')
                ->color('info')
                ->visible(fn () => (bool) $this->record->conversation)
                ->form([
                    Select::make('recipient')
                        ->label('Destinataire')
                        ->options([
                            'all'    => 'Tous (client + artiste)',
                            'client' => 'Client uniquement',
                            'artist' => 'Artiste uniquement',
                        ])
                        ->default('all')
                        ->required(),
                    Textarea::make('message')
                        ->label('Message')
                        ->placeholder("Écrivez votre message en tant qu'équipe Ink&Pik...")
                        ->rows(4)
                        ->required()
                        ->minLength(5),
                ])
                ->action(function (array $data) {
                    $booking      = $this->record;
                    $conversation = $booking->conversation;

                    if (!$conversation) {
                        \Filament\Notifications\Notification::make()
                            ->warning()->title('Pas de conversation liée')->send();
                        return;
                    }

                    \App\Models\Message::create([
                        'conversation_id' => $conversation->id,
                        'sender_id'       => auth()->id(),
                        'sender_type'     => 'admin',
                        'content'         => '🛡️ Équipe Ink&Pik : ' . $data['message'],
                    ]);

                    $artist = $booking->bookable;
                    $client = $booking->client;

                    if (in_array($data['recipient'], ['all', 'client']) && $client?->user) {
                        $client->user->notify(
                            new \App\Notifications\AdminMessageReceived($data['message'], $booking)
                        );
                    }

                    if (in_array($data['recipient'], ['all', 'artist']) && $artist?->user) {
                        $artist->user->notify(
                            new \App\Notifications\AdminMessageReceived($data['message'], $booking)
                        );
                    }

                    \Filament\Notifications\Notification::make()
                        ->success()->title('Message envoyé')->send();
                }),

            Actions\Action::make('manual_refund')
                ->label('Effectuer le remboursement')
                ->icon('heroicon-o-currency-euro')
                ->color('success')
                ->visible(fn () =>
                    ($this->record->refund_amount ?? 0) > 0
                    && !$this->record->refund_processed_at
                )
                ->form([
                    \Filament\Forms\Components\TextInput::make('refund_amount')
                        ->label('Montant à rembourser (€)')
                        ->numeric()
                        ->step(0.01)
                        ->default(fn () => $this->record->refund_amount)
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('admin_note')
                        ->label('Note admin')
                        ->placeholder('Raison du remboursement manuel...'),
                ])
                ->action(function (array $data) {
                    $amount = (float) $data['refund_amount'];
                    try {
                        app(\App\Services\BookingRequestService::class)
                            ->processStripeRefund($this->record, $amount);
                        $this->record->update([
                            'refund_processed_at' => now(),
                            'refund_amount'       => $amount,
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('✅ Remboursement de ' . number_format($amount, 2, ',', ' ') . '€ effectué')
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('❌ Erreur: ' . $e->getMessage())
                            ->persistent()
                            ->send();
                    }
                }),
        ];
    }
}
