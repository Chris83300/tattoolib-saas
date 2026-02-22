<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Settings extends Component
{
    use WithFileUploads;

    public $pseudo;
    public $birth_date;
    public $newAvatar;

    protected $rules = [
        'newAvatar' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120', // 5MB max
    ];

    #[Layout('components.layouts.livewire-site')]
    #[Title('Paramètres - Ink&Pik')]

    public function mount()
    {
        $user = Auth::user();
        $client = $user->client;

        // Priorité: client.pseudo > user.pseudo > user.first_name > vide
        $this->pseudo = $client->pseudo ?? $user->pseudo ?? $user->first_name ?? '';
        $this->birth_date = $client->birth_date?->format('Y-m-d') ?? '';
        $this->newAvatar = null; // Initialiser la propriété

        // Debug pour voir les propriétés
        logger()->info('Client Settings mount - newAvatar: ' . ($this->newAvatar ?? 'NULL'));
        logger()->info('Client Settings mount - properties: ' . json_encode(get_object_vars($this)));
    }

    public function render()
    {
        return view('livewire.client.settings');
    }

    public function updateProfile()
    {
        $this->validate([
            'pseudo' => 'required|string|min:2|max:30|unique:users,name,' . auth()->id(),
            'birth_date' => 'nullable|date|before:' . now()->subYears(16)->format('Y-m-d'), // min 16 ans
        ]);

        $user = Auth::user();
        $client = $user->client;

        try {
            // Mettre à jour pseudo sur User (dans pseudo et name)
            if (empty($user->pseudo) || $user->pseudo !== $this->pseudo) {
                $result = $user->update([
                    'pseudo' => $this->pseudo,
                ]);
            }

            // Mettre à jour pseudo sur Client (dans pseudo)
            if ($client) {
                $result = $client->update([
                    'pseudo' => $this->pseudo,  // Utiliser la colonne pseudo
                    'birth_date' => $this->birth_date ? $this->birth_date : null,
                ]);
            }

            // Vérifier réellement
            $client->refresh();

            // Rafraîchir les données pour l'affichage
            $this->pseudo = $this->pseudo;

            $this->dispatch('profile-updated', 'Profil mis à jour avec succès !');

        } catch (\Exception $e) {

            $this->dispatch('profile-update-error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function uploadAvatar()
    {
        // Validation avec debug
        try {
            $this->validate([
                'newAvatar' => 'required|image|mimes:jpeg,png,gif,webp|max:5120', // 5MB max
            ]);
        } catch (\Exception $e) {
            $this->dispatch('avatar-upload-error', 'Erreur validation: ' . $e->getMessage());
            return;
        }

        try {
            $user = Auth::user();
            $client = $user->client;

            // Upload sur le modèle Client
            $client->clearMediaCollection('avatar');
            $media = $client->addMedia($this->newAvatar)
                ->usingFileName($this->newAvatar->getClientOriginalName())
                ->toMediaCollection('avatar');

            $this->newAvatar = null;
            $this->dispatch('avatar-uploaded', 'Avatar mis à jour avec succès !');

        } catch (\Exception $e) {
            $this->dispatch('avatar-upload-error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function removeAvatar()
    {
        $user = Auth::user();
        $client = $user->client;

        // Supprimer l'avatar du modèle Client
        $client->clearMediaCollection('avatar');

        $this->dispatch('avatar-removed', 'Avatar supprimé avec succès !');
    }

    protected $listeners = ['test-debug' => 'testDebug', 'confirm-delete' => 'deleteAccount', 'cancel-delete' => 'cancelDelete'];

    public function cancelDelete()
    {
        $this->dispatch('hide-confirm-dialog');
    }



    public function confirmDeleteAccount()
    {
        // Afficher une alerte de confirmation
        $this->dispatch('show-confirm-dialog', [
            'title' => '⚠️ Suppression du compte',
            'message' => 'Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est IRREVERSIBLE et supprimera définitivement toutes vos données.',
            'confirmText' => 'Oui, supprimer mon compte',
            'cancelText' => 'Annuler'
        ]);
    }

    public function deleteAccount()
    {
        try {
            $user = Auth::user();
            $client = $user->client;


            // Supprimer l'avatar
            $user->clearMediaCollection('avatar');

            // Supprimer les données du client
            if ($client) {
                // Conserver les données importantes pour le tattooer
                $tattooerData = [
                    'first_name' => $client->first_name,
                    'last_name' => $client->last_name,
                    'email' => $client->email,
                    'phone' => $client->phone,
                    'birth_date' => $client->birth_date,
                    'notes' => $client->notes,
                    'no_show_count' => $client->no_show_count,
                    'is_blacklisted' => $client->is_blacklisted,
                    'blacklist_reason' => $client->blacklist_reason,
                ];


                $client->delete();
            }

            // Supprimer l'utilisateur
            $user->delete();

            // Déconnexion
            Auth::logout();

            $this->dispatch('account-deleted', 'Compte supprimé avec succès');

            // Redirection vers page d'accueil
            return redirect()->route('home');

        } catch (\Exception $e) {

            $this->dispatch('account-delete-error', 'Erreur lors de la suppression du compte');
        }
    }
}
