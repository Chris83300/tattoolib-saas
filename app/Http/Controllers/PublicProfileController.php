<?php

namespace App\Http\Controllers;

use App\Models\Tattooer;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    /**
     * Afficher le profil public d'un tattooer
     */
    public function tattooerProfile($slug)
    {
        $tattooer = Tattooer::where('slug', $slug)
            ->with(['user.media', 'studio'])
            ->firstOrFail();

        return view('public.tattooer-profile', compact('tattooer'));
    }
}
