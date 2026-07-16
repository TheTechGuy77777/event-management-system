<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

abstract class BasePortalMiddleware
{
    abstract protected function getRoleCheck(User $user): bool;

    abstract protected function getSuspendedMessage(): string;

    abstract protected function getBannedMessage(): string;

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user || ! $this->getRoleCheck($user)) {
            abort(403, 'Unauthorized');
        }

        if (! $user->is_active) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => $this->getSuspendedMessage(),
            ]);
        }

        if ($user->is_banned) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => $this->getBannedMessage(),
            ]);
        }

        return $next($request);
    }
}
