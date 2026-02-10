<?php

namespace App\Http\Controllers\Tattooer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tattooer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    /**
     * Supprime définitivement le compte du tattooer
     */
    public function delete(Request $request)
    {
        $user = Auth::user();
        $tattooer = $user->tattooer;

        if (!$tattooer) {
            return response()->json(['error' => 'Profil tattooer non trouvé'], 404);
        }

        try {
            DB::beginTransaction();

            // Supprimer les médias (avatar, bannière, portfolio)
            $tattooer->clearMediaCollection('avatar');
            $tattooer->clearMediaCollection('banner');
            $tattooer->clearMediaCollection('portfolio');

            // Supprimer les données liées
            $tattooer->delete();

            // Supprimer l'utilisateur
            $user->delete();

            DB::commit();

            // Déconnexion
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Compte supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur suppression compte tattooer: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Erreur lors de la suppression du compte'
            ], 500);
        }
    }
}
