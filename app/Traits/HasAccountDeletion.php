<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait HasAccountDeletion
{
    public function destroyAccount(Request $request)
    {
        $request->validate([
            'confirmation' => ['required', 'in:SUPPRIMER'],
            'password'     => ['required', 'current_password'],
        ], [
            'confirmation.in'              => 'Veuillez taper exactement "SUPPRIMER".',
            'password.current_password'    => 'Mot de passe incorrect.',
        ]);

        $user = $request->user();

        Log::info('Suppression compte initiée', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'role'    => $user->getRoleNames()->first() ?? 'unknown',
            'ip'      => $request->ip(),
        ]);

        try {
            $this->performDeletion($user);
        } catch (\Exception $e) {
            Log::error('Erreur suppression compte: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace'   => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error',
                'Une erreur est survenue. Vos données n\'ont pas été supprimées. '
                . 'Contactez le support si le problème persiste.'
            );
        }

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')
            ->with('info', 'Votre compte a été supprimé. À bientôt.');
    }

    abstract protected function performDeletion(\App\Models\User $user): void;
}
