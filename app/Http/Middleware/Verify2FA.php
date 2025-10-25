<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Verify2FA
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user) {
            // Jika 2FA belum di-setup, arahkan ke setup
            if (!$user->is2FAEnabled() && !$request->is('2fa/setup*') && !$request->is('logout')) {
                return redirect()->route('2fa.setup');
            }

            // Jika 2FA sudah di-setup tapi belum diverifikasi session
            if ($user->is2FAEnabled() && !session('2fa_verified') && !$request->is('2fa/verify*') && !$request->is('logout')) {
                return redirect()->route('2fa.verify');
            }
        }

        return $next($request);
    }
}
