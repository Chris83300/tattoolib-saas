<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $pseudo = '';
    public string $email = '';
    public $avatar;
    public $currentAvatar;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->pseudo = $user->pseudo ?? '';
        $this->email = $user->email;
        $this->currentAvatar = $user->avatar_url;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'pseudo' => ['nullable', 'string', 'max:50', Rule::unique('users', 'pseudo')->ignore($user->id)],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Upload avatar via Spatie si fourni
        if ($this->avatar) {
            $profile = $user->profile;

            if ($profile && method_exists($profile, 'clearMediaCollection')) {
                $profile->clearMediaCollection('avatar');
                $profile->addMedia($this->avatar->getRealPath())
                    ->usingFileName($this->avatar->getClientOriginalName())
                    ->toMediaCollection('avatar');
            }
        }

        $this->dispatch('profile-updated', name: $user->name);

        session()->flash('success', 'Profil mis à jour avec succès !');

        $this->currentAvatar = $user->fresh()->avatar_url;
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Layout('components.layouts.livewire-site')]
    #[Title('Paramètres - Ink&Pik')]
    public function render()
    {
        return view('livewire.settings.profile');
    }
}
