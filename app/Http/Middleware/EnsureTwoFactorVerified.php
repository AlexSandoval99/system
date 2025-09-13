<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Cookie firmada (recordar dispositivo)
        $cookie = $request->cookie('2fa_passed');
        if ($cookie && hash_equals($cookie, hash('sha256', auth()->id().'|'.$request->userAgent()))) {
            return $next($request);
        }

        // Flag de sesión
        if (session('2fa_passed') === true) {
            return $next($request);
        }

        // Permitir challenge/verify/resend/logout
        if ($request->routeIs(['2fa.challenge','2fa.verify','2fa.resend','logout'])) {
            return $next($request);
        }

        return redirect()->route('2fa.challenge');
    }
}
