<?php

namespace App\Filament\Admin\Resources\Tattooers\Pages;

use App\Filament\Admin\Resources\Tattooers\TattooerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditTattooer extends EditRecord
{
    protected static string $resource = TattooerResource::class;

    protected function getHeaderActions(): array
    {
        return [

            // ACTION 1 : Valider le profil (passe en actif)
            Actions\Action::make('validate_profile')
                ->label('✅ Valider Profil')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () {
                    // Passer le user en actif
                    $this->record->user()->update([
                        'status' => 'active',
                    ]);

                    // Enregistrer la date de validation
                    $this->record->update([
                        'admin_verified_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Profil validé !')
                        ->body('Le tatoueur est maintenant actif et visible sur la plateforme.')
                        ->success()
                        ->send();

                    // Recharger la page pour voir les changements
                    redirect()->to(static::getUrl(['record' => $this->record]));
                })
                ->requiresConfirmation()
                ->modalHeading('Valider ce profil ?')
                ->modalDescription('Le tatoueur deviendra actif et visible sur la plateforme.')
                ->modalSubmitActionLabel('Valider')
                ->visible(fn () => $this->record->user->status === 'pending_verification'),

            // ACTION 2 : Suspendre le profil
            Actions\Action::make('suspend_profile')
                ->label('🚫 Suspendre')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    \Filament\Forms\Components\Textarea::make('reason')
                        ->label('Raison de la suspension')
                        ->required()
                        ->maxLength(500)
                        ->helperText('Cette raison sera visible par le tatoueur'),
                ])
                ->action(function (array $data) {
                    $this->record->user()->update([
                        'status' => 'suspended',
                    ]);

                    $this->record->update([
                        'admin_rejection_reason' => $data['reason'],
                    ]);

                    Notification::make()
                        ->title('Profil suspendu')
                        ->body('Le tatoueur a été suspendu.')
                        ->warning()
                        ->send();

                    redirect()->to(static::getUrl(['record' => $this->record]));
                })
                ->requiresConfirmation()
                ->modalHeading('Suspendre ce profil ?')
                ->modalSubmitActionLabel('Suspendre')
                ->visible(fn () => $this->record->user->status === 'active'),

            // ACTION 3 : Activer/Désactiver badge conformité
            Actions\Action::make('toggle_badge')
                ->label(fn () => $this->record->has_compliance_badge ? '🏅 Retirer Badge' : '🏅 Attribuer Badge')
                ->icon('heroicon-o-check-badge')
                ->color(fn () => $this->record->has_compliance_badge ? 'warning' : 'success')
                ->action(function () {
                    $newStatus = !$this->record->has_compliance_badge;

                    $this->record->update([
                        'has_compliance_badge' => $newStatus,
                    ]);

                    Notification::make()
                        ->title($newStatus ? 'Badge attribué' : 'Badge retiré')
                        ->success()
                        ->send();

                    redirect()->to(static::getUrl(['record' => $this->record]));
                })
                ->requiresConfirmation()
                ->modalHeading(fn () => $this->record->has_compliance_badge ? 'Retirer le badge ?' : 'Attribuer le badge ?'),

            // ACTION 4 : Voir les documents conformité
            Actions\Action::make('view_compliance')
                ->label('📄 Documents Conformité')
                ->icon('heroicon-o-document-check')
                ->color('info')
                ->url(fn () => route('filament.admin.resources.compliance-records.index', [
                    'tableFilters' => [
                        'tattooer_id' => ['value' => $this->record->id],
                    ],
                ]))
                ->openUrlInNewTab(),

            // ACTION 5 : Supprimer
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Charger le statut depuis la relation User
        $data['user_status'] = $this->record->user?->status ?? 'pending_verification';

        // Charger les URLs des médias existants pour les afficher
        $data['avatar'] = $this->record->getFirstMediaUrl('avatar') ?: null;
        $data['portfolio'] = $this->record->getMedia('portfolio')->map(fn ($media) => $media->getUrl())->toArray();

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        $data = $this->form->getState();

        // Sauvegarder le statut dans la relation User
        if (isset($data['user_status']) && $record->user) {
            $oldStatus = $record->user->status;
            $newStatus = $data['user_status'];

            if ($oldStatus !== $newStatus) {
                $record->user->update(['status' => $newStatus]);

                // Si passage à "active", mettre à jour admin_verified_at
                if ($newStatus === 'active' && !$record->admin_verified_at) {
                    $record->update(['admin_verified_at' => now()]);
                }
            }
        }

        // Gérer avatar upload → Spatie
        if (isset($data['avatar']) && $data['avatar']) {
            $avatarPath = $data['avatar'];
            $record->clearMediaCollection('avatar');

            // Vérifier si c'est un chemin ou une URL
            if (str_starts_with($avatarPath, 'http')) {
                // C'est une URL, télécharger et ajouter
                $tempPath = tempnam(sys_get_temp_dir(), 'avatar_');
                file_put_contents($tempPath, file_get_contents($avatarPath));
                $record->addMedia($tempPath)->toMediaCollection('avatar');
                unlink($tempPath);
            } else {
                // C'est un chemin relatif depuis storage
                $fullPath = storage_path('app/public/' . $avatarPath);
                if (file_exists($fullPath)) {
                    $record->addMedia($fullPath)->toMediaCollection('avatar');
                }
            }
        }

        // Gérer portfolio upload → Spatie
        if (isset($data['portfolio']) && is_array($data['portfolio'])) {
            $record->clearMediaCollection('portfolio');
            foreach ($data['portfolio'] as $path) {
                if (str_starts_with($path, 'http')) {
                    // C'est une URL, télécharger et ajouter
                    $tempPath = tempnam(sys_get_temp_dir(), 'portfolio_');
                    file_put_contents($tempPath, file_get_contents($path));
                    $record->addMedia($tempPath)->toMediaCollection('portfolio');
                    unlink($tempPath);
                } else {
                    // C'est un chemin relatif depuis storage
                    $fullPath = storage_path('app/public/' . $path);
                    if (file_exists($fullPath)) {
                        $record->addMedia($fullPath)->toMediaCollection('portfolio');
                    }
                }
            }
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Si le statut passe à "active" et que admin_verified_at est vide
        if (isset($data['user_status']) && $data['user_status'] === 'active' && !$this->record->admin_verified_at) {
            $data['admin_verified_at'] = now();
        }

        return $data;
    }
}
