<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Afficher le profil du client
     */
    public function index(Request $request)
    {
        // La vue Blade contient le composant Livewire
        return view('client.profile');
    }
}
