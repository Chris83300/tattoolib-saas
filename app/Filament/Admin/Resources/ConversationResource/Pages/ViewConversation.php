<?php
namespace App\Filament\Admin\Resources\ConversationResource\Pages;

use App\Filament\Admin\Resources\ConversationResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;

class ViewConversation extends ViewRecord
{
    protected static string $resource = ConversationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Répondre dans la conversation support (visible client + artiste)
            Actions\Action::make('send_support_message')
                ->label('Répondre dans cette conversation')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->form([
                    Textarea::make('message')
                        ->label("Message (visible par le client et l'artiste)")
                        ->placeholder("Répondez en tant qu'équipe Ink&Pik...")
                        ->rows(4)
                        ->required(),
                ])
                ->action(function (array $data) {
                    \App\Models\Message::create([
                        'conversation_id' => $this->record->id,
                        'sender_id'       => null,
                        'sender_type'     => 'admin',
                        'content'         => '🛡️ Équipe Ink&Pik : ' . $data['message'],
                    ]);

                    // Notifier les participants (hors admin)
                    $adminId = auth()->id();
                    $this->record->participants
                        ->reject(fn ($u) => $u->id === $adminId)
                        ->each(fn ($u) => $u->notify(
                            new \App\Notifications\AdminMessageReceived(
                                $data['message'],
                                $this->record->bookingRequest
                            )
                        ));

                    \Filament\Notifications\Notification::make()
                        ->success()->title('Message envoyé')->send();

                    $this->redirect(request()->url());
                }),

            // Contacter un utilisateur en privé (canal séparé)
            Actions\Action::make('contact_private')
                ->label('Contacter en privé')
                ->icon('heroicon-o-lock-closed')
                ->color('gray')
                ->form([
                    Select::make('user_id')
                        ->label('Utilisateur')
                        ->options(function () {
                            $options = [];
                            $br = $this->record->bookingRequest;
                            if ($br?->client?->user) {
                                $u = $br->client->user;
                                $options[$u->id] = '👤 Client — ' . ($u->name ?? $u->email);
                            }
                            if ($br?->bookable?->user) {
                                $u = $br->bookable->user;
                                $options[$u->id] = '🎨 Artiste — ' . ($u->pseudo ?? $u->name ?? $u->email);
                            }
                            // Fallback : participants du pivot si disponibles
                            if (empty($options)) {
                                foreach ($this->record->participants as $u) {
                                    $options[$u->id] = $u->name ?? $u->pseudo ?? $u->email;
                                }
                            }
                            return $options;
                        })
                        ->required(),
                    Textarea::make('message')
                        ->label('Message privé (canal sécurisé admin↔utilisateur)')
                        ->placeholder('Ce message sera dans un canal privé séparé...')
                        ->rows(4)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $adminId = auth()->id();
                    $userId  = (int) $data['user_id'];

                    $privateConv = \App\Models\Conversation::getOrCreateAdminChannel($adminId, $userId);

                    \App\Models\Message::create([
                        'conversation_id' => $privateConv->id,
                        'sender_id'       => null,
                        'sender_type'     => 'admin',
                        'content'         => '🛡️ Équipe Ink&Pik : ' . $data['message'],
                    ]);

                    $user = \App\Models\User::find($userId);
                    $user?->notify(
                        new \App\Notifications\AdminMessageReceived(
                            $data['message'],
                            $this->record->bookingRequest
                        )
                    );

                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Message privé envoyé')
                        ->body('Un canal privé a été créé avec cet utilisateur.')
                        ->send();
                }),

            // Voir dans Stripe si remboursement
            Actions\Action::make('stripe_link')
                ->label('Voir dans Stripe')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('success')
                ->visible(fn () => (bool) $this->record->bookingRequest?->stripe_payment_intent_id)
                ->url(fn () =>
                    'https://dashboard.stripe.com/'
                    . (app()->environment('production') ? '' : 'test/')
                    . 'payments/'
                    . $this->record->bookingRequest?->stripe_payment_intent_id
                )
                ->openUrlInNewTab(),

            // Lien vers la demande complète
            Actions\Action::make('open_booking')
                ->label('Voir la demande')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->visible(fn () => (bool) $this->record->bookingRequest)
                ->url(fn () => route(
                    'filament.admin.resources.booking-requests.edit',
                    $this->record->bookingRequest
                ))
                ->openUrlInNewTab(),
        ];
    }
}
