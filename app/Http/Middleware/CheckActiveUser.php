<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckActiveUser
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->is_active) {
            Auth::logout();
            
            // Redirect to a specific error page for inactive users
            return redirect('/inactive-account')
                ->with('error', 'Votre compte a été désactivé. Veuillez contacter l\'administrateur.');
        }

        return $next($request);
    }
}
