<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        if (in_array($user->user_type, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}