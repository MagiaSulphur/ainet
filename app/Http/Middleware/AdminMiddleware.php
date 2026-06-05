<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (Auth::user()->user_type !== 'A') {
            abort(403, 'Access denied');
        }

        if (Auth::user()->blocked) {
            Auth::logout();
            return redirect('/login')
                ->with('error', 'Blocked user');
        }

        return $next($request);
    }
}