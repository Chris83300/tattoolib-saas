<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasStatus
{
    public function handle(Request $request, Closure $next, string ...$statuses)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        if (!in_array(auth()->user()->status, $statuses)) {
            // Redirection si pas le bon statut
            if (auth()->user()->status === 'pending_verification') {
                return redirect()->route('tattooer.pending-verification');
            }
            
            abort(403, 'Votre compte n\'est pas actif');
        }
        
        return $next($request);
    }
}
