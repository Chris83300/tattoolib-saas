<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureOwnership
{
    public function handle(Request $request, Closure $next, string $modelClass)
    {
        $model = $request->route()->parameter(class_basename($modelClass));
        
        if (!$model) {
            abort(404);
        }
        
        // Autoriser via policy
        if (!$request->user()->can('view', $model)) {
            abort(403, 'Vous n\'avez pas accès à cette ressource.');
        }
        
        return $next($request);
    }
}
