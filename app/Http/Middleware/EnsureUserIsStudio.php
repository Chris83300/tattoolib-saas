<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsStudio
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'studio') {
            abort(403, 'Accès réservé aux studios');
        }
        
        return $next($request);
    }
}
