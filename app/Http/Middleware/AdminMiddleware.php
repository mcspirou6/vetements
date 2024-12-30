<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Redirect to a specific error page for unauthorized access
        return redirect('/unauthorized-admin')
            ->with('error', 'Accès refusé. Cette section est réservée aux administrateurs.');
    }
}