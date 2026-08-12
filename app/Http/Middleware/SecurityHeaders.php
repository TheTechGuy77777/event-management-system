<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        $appUrl = rtrim((string) config('app.url'), '/');
        $host = preg_replace('#^https?://#', '', $appUrl);

        $viteOrigin = 'https://' . $host . ':5173';
        $viteWsOrigin = 'wss://' . $host . ':5173';

        if (app()->environment('local', 'testing')) {
            $csp = "default-src 'self'; "
                . "script-src 'self' 'unsafe-inline' 'unsafe-eval' {$viteOrigin} https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; "
                . "style-src 'self' 'unsafe-inline' {$viteOrigin} https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://fonts.bunny.net; "
                . "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net https://cdnjs.cloudflare.com; "
                . "connect-src 'self' {$viteOrigin} {$viteWsOrigin}; "
                . "img-src 'self' data: https:; "
                . "frame-src 'self' https://www.loom.com; "
                . "frame-ancestors 'self';";
        } else {
            $csp = "default-src 'self'; "
                . "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; "
                . "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://fonts.bunny.net; "
                . "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net https://cdnjs.cloudflare.com; "
                . "connect-src 'self'; "
                . "img-src 'self' data: https:; "
                . "frame-src 'self' https://www.loom.com; "
                . "frame-ancestors 'self';";
        }

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
