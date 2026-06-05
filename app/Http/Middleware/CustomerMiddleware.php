<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (Auth::user()->user_type !== 'C') {
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