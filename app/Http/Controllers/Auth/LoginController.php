<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirection selon le rôle
            switch ($user->role) {
                case 'client':
                    return redirect()->route('client.profile');
                case 'tattooer':
                    return redirect()->route('tattooer.profile');
                case 'pierceur':
                    return redirect()->route('tattooer.dashboard'); // Temporairement
                case 'studio':
                    return redirect()->route('tattooer.dashboard'); // Temporairement
                default:
                    return redirect()->route('home');
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }
}
