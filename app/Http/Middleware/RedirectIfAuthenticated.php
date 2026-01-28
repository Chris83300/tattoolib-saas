<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                
                // Redirection selon le rôle au lieu de /dashboard
                switch ($user->role) {
                    case 'client':
                        return redirect()->route('client.profile');
                    case 'tattooer':
                        return redirect()->route('tattooer.dashboard');
                    case 'pierceur':
                        return redirect()->route('tattooer.dashboard'); // Temporairement
                    case 'studio':
                        return redirect()->route('tattooer.dashboard'); // Temporairement
                    default:
                        return redirect()->route('home');
                }
            }
        }

        return $next($request);
    }
}
