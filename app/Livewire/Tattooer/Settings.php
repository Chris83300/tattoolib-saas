<?php

namespace App\Livewire\Tattooer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Settings extends Component
{
    use WithFileUploads;

    #[Validate]
    public $name = '';

    #[Validate]
    public $email = '';

    public $phone = '';
    public $bio = '';
    public $experience_years = '';
    public $wait_time_days = '';
    public $price_from = '';
    public $avatar;
    public $testAvatar; // Pour l'upload séparé

    public $successMessage = '';
    public $errorMessage = '';

    public $pseudo = '';
    public $artistName = '';
    public $siret = '';
    public $address = '';
    public $postalCode = '';
    public $city = '';
    public $minPrice = '';

    public $emailNotifications = true;
    public $smsNotifications = false;
    public $marketingNotifications = false;

    public function mount()
    {
        Log::info('=== SETTINGS MOUNT ===');

        $user = Auth::user();
        $tattooer = $user->tattooer;

        // Charger les données existantes
        $this->pseudo = $user->pseudo ?? '';
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $tattooer->phone ?? '';
        $this->bio = $tattooer->bio ?? '';
        $this->experience_years = $tattooer->experience_years ?? '';
        $this->wait_time_days = $tattooer->wait_time_days ?? '';
        $this->price_from = $tattooer->price_from ?? '';

        $this->artistName = $tattooer->name ?? '';
        $this->siret = $tattooer->siret ?? '';
        $this->address = $tattooer->address ?? '';
        $this->postalCode = $tattooer->postal_code ?? '';
        $this->city = $tattooer->city ?? '';
        $this->minPrice = $tattooer->min_price ?? '';

        Log::info('Avatar existant: ' . ($tattooer->getFirstMediaUrl('avatar') ? 'OUI' : 'NON'));
        Log::info('=== FIN SETTINGS MOUNT ===');
    }

    public function updateProfile()
    {
        Log::info('=== DÉBUT UPDATE PROFILE ===');
        Log::info('Avatar présent: ' . ($this->avatar ? 'OUI' : 'NON'));

        // Vider les messages précédents
        $this->successMessage = '';
        $this->errorMessage = '';

        if ($this->avatar) {
            Log::info('Avatar nom: ' . $this->avatar->getClientOriginalName());
            Log::info('Avatar taille: ' . $this->avatar->getSize());

            // Vérification simple de la taille
            if ($this->avatar->getSize() > 2048 * 1024) {
                $this->errorMessage = 'Image trop volumineuse: ' . round($this->avatar->getSize() / 1024 / 1024, 2) . 'MB';
                Log::info('Erreur taille image');
                return;
            }
        }

        try {
            // Validation complète
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
                'phone' => 'nullable|string|max:20',
                'bio' => 'nullable|string|max:1000',
                'experience_years' => 'nullable|integer|min:0|max:50',
                'wait_time_days' => 'nullable|integer|min:0|max:365',
                'price_from' => 'nullable|numeric|min:0',
                'avatar' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
                'pseudo' => 'nullable|string|max:50|unique:users,pseudo,' . Auth::id(),
            ]);

            Log::info('Validation réussie');

            $user = Auth::user();
            $tattooer = $user->tattooer;

            // Mise à jour user
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'pseudo' => $this->pseudo,
            ]);

            // Mise à jour tattooer
            $tattooer->update([
                'phone' => $this->phone,
                'bio' => $this->bio,
                'experience_years' => $this->experience_years,
                'wait_time_days' => $this->wait_time_days,
                'price_from' => $this->price_from,
            ]);

            Log::info('Données utilisateur mises à jour');

            // Upload avatar si présent
            if ($this->avatar) {
                Log::info('Début upload avatar...');

                try {
                    // Supprimer ancien avatar
                    $tattooer->clearMediaCollection('avatar');
                    Log::info('Ancien avatar supprimé');

                    // Uploader nouveau
                    $tattooer->addMedia($this->avatar->getRealPath())
                             ->usingFileName($this->avatar->getClientOriginalName())
                             ->toMediaCollection('avatar');

                    Log::info('Nouvel avatar uploadé avec succès');
                    $this->successMessage = 'Profil et avatar mis à jour avec succès !';
                } catch (\Exception $e) {
                    Log::error('Erreur upload avatar: ' . $e->getMessage());
                    $this->errorMessage = 'Erreur upload avatar: ' . $e->getMessage();
                    return;
                }
            } else {
                $this->successMessage = 'Profil mis à jour avec succès !';
            }

            // Vider l'avatar après traitement
            $this->avatar = null;

            // Envoyer notification
            $this->dispatch('showNotification', $this->successMessage);

        } catch (\Exception $e) {
            Log::error('Erreur validation/update: ' . $e->getMessage());
            $this->errorMessage = 'Erreur: ' . $e->getMessage();
        }

        Log::info('=== FIN UPDATE PROFILE ===');
    }

    public function uploadAvatar()
    {
        Log::info('=== DÉBUT UPLOAD AVATAR SETTINGS ===');
        Log::info('Avatar présent: ' . ($this->testAvatar ? 'OUI' : 'NON'));
        Log::info('Avatar type: ' . gettype($this->testAvatar));

        if ($this->testAvatar) {
            Log::info('Avatar nom: ' . $this->testAvatar->getClientOriginalName());
            Log::info('Avatar taille: ' . $this->testAvatar->getSize());
            Log::info('Avatar erreur: ' . $this->testAvatar->getError());
            Log::info('Avatar chemin temporaire: ' . $this->testAvatar->getRealPath());
        }

        try {
            $this->validate([
                'testAvatar' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            ]);

            Log::info('Validation réussie');

            if (!$this->testAvatar) {
                $this->errorMessage = 'Veuillez sélectionner un fichier';
                return;
            }

            $tattooer = Auth::user()->tattooer;

            // Supprimer l'ancien avatar
            $tattooer->clearMediaCollection('avatar');

            // Uploader le nouvel avatar
            $tattooer->addMedia($this->testAvatar->getRealPath())
                     ->usingFileName($this->testAvatar->getClientOriginalName())
                     ->toMediaCollection('avatar');

            Log::info('Avatar uploadé avec succès');

            $this->successMessage = 'Avatar uploadé avec succès !';
            $this->errorMessage = null;
            $this->testAvatar = null;

        } catch (\Exception $e) {
            Log::error('Erreur upload avatar: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            $this->errorMessage = 'Erreur: ' . $e->getMessage();
            $this->successMessage = null;
        }

        Log::info('=== FIN UPLOAD AVATAR SETTINGS ===');
    }

    public function removeAvatar()
    {
        Log::info('=== DÉBUT REMOVE AVATAR ===');

        try {
            $tattooer = Auth::user()->tattooer;

            // Supprimer l'avatar existant
            $tattooer->clearMediaCollection('avatar');

            Log::info('Avatar supprimé avec succès');

            $this->successMessage = 'Avatar supprimé avec succès !';
            $this->errorMessage = '';

            // Envoyer la notification Livewire
            $this->dispatch('showNotification', $this->successMessage);

        } catch (\Exception $e) {
            Log::error('Erreur suppression avatar: ' . $e->getMessage());
            $this->errorMessage = 'Erreur lors de la suppression de l\'avatar: ' . $e->getMessage();
            $this->successMessage = '';
        }

        Log::info('=== FIN REMOVE AVATAR ===');
    }

    public function updateProfessional()
    {
        $artisan = Auth::user()->artisan();
        $studio = $artisan?->studio;
        $siretRequired = $studio && $studio->payment_mode === 'artist_direct';

        $this->validate([
            'artistName' => 'required|string|max:255',
            'siret' => $siretRequired ? 'required|string|size:14' : 'nullable|string|max:14',
            'address' => 'nullable|string|max:255',
            'postalCode' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:255',
            'minPrice' => 'nullable|numeric|min:0',
        ]);

        $tattooer = Auth::user()->tattooer;

        $tattooer->update([
            'name' => $this->artistName,
            'siret' => $this->siret,
            'address' => $this->address,
            'postal_code' => $this->postalCode,
            'city' => $this->city,
            'min_price' => $this->minPrice,
        ]);

        $this->dispatch('showNotification', 'Informations professionnelles mises à jour !');
    }

    public function updateNotifications()
    {
        // Logique pour sauvegarder les préférences de notification
        // Pour l'instant, on simule la sauvegarde

        $this->dispatch('showNotification', 'Préférences de notification mises à jour !');
    }

    #[Layout('components.layouts.livewire-site')]
    #[Title('Paramètres du profil')]
    public function render()
    {
        return view('livewire.tattooer.settings-final');
    }
}
