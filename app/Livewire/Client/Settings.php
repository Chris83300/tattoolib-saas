<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Settings extends Component
{
    use WithFileUploads;

    public $avatar;
    public $testAvatar; // Pour l'upload séparé
    public $pseudo;
    public $birth_date;

    #[Layout('components.layouts.livewire-site')]
    #[Title('Paramètres - Ink&Pik')]

    public function mount()
    {
        $user = Auth::user();
        $client = $user->client;

        $this->pseudo = $client->pseudo ?? $user->name;
        $this->birth_date = $client->birth_date?->format('Y-m-d') ?? '';
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

        // Mettre à jour pseudo sur User
        $user->update(['name' => $this->pseudo]);

        // Mettre à jour date de naissance sur Client
        if ($client) {
            $client->update([
                'pseudo' => $this->pseudo,
                'birth_date' => $this->birth_date ? $this->birth_date : null,
            ]);
        }

        $this->dispatch('profile-updated', 'Profil mis à jour avec succès !');
    }

    public function uploadAvatar()
    {
        $this->validate([
            'testAvatar' => 'required|image|mimes:jpeg,png,gif,webp|max:2048', // 2MB max
        ]);

        $user = Auth::user();

        // Supprimer l'ancien avatar s'il existe
        $user->clearMediaCollection('avatar');

        // Ajouter le nouvel avatar avec le bon chemin et nom
        $media = $user->addMedia($this->testAvatar->getRealPath())
             ->usingFileName($this->testAvatar->getClientOriginalName())
             ->toMediaCollection('avatar');

        $this->testAvatar = null;
        $this->dispatch('avatar-uploaded', 'Avatar mis à jour avec succès !');
    }

    public function removeAvatar()
    {
        $user = Auth::user();

        $user->clearMediaCollection('avatar');

        $this->dispatch('avatar-removed', 'Avatar supprimé avec succès !');
    }

    public function getAvatarUrlProperty()
    {
        $user = Auth::user();

        if ($user && $user->getFirstMediaUrl('avatar')) {
            return $user->getFirstMediaUrl('avatar');
        }

        return null;
    }
}
