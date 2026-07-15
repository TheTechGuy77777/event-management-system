<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EventManagerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || ! $user->isEventManager()) {
            abort(403, 'Unauthorized');
        }

        if ($user->is_banned) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been permanently disabled.',
            ]);
        }

        if (! $user->is_active) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been suspended. Please contact support.',
            ]);
        }

        return $next($request);
    }
}
