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

                // Redirection selon le rôle
                if ($user->isAdmin()) {
                    return redirect('/admin');
                }

                return match($user->role) {
                    'client'        => redirect()->route('client.profile'),
                    'tattooer'      => redirect()->route('tattooer.dashboard'),
                    'pierceur'      => redirect()->route('pierceur.dashboard'),
                    'studio'        => redirect()->route('studio.dashboard'),
                    'studio_artist' => redirect()->route('tattooer.dashboard'),
                    default         => redirect()->route('home'),
                };
            }
        }

        return $next($request);
    }
}
