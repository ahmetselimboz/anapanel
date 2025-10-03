<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RedirectIfNotSetup2FA
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        $path = $request->path(); // örnek: "secure", "login", "secure/homepage-stats"

        // Yalnızca "secure*" ya da "login" path'lerinde çalışsın
        if (($path === 'login' || str_starts_with($path, 'secure')) && $user) {

            Log::info('2FA Kontrolü: Middleware çalıştı', [
                'user_id' => $user->id,
                'two_factor_enabled' => $user->two_factor_enabled,
                'google2fa_secret' => $user->google2fa_secret,
                'path' => $path
            ]);

            if ($user->two_factor_enabled && $user->google2fa_secret === null) {
                Log::info('2FA Kontrolü: Secret yok, setup sayfasına yönlendiriliyor.');
                return redirect()->route('2fa.setup');
            }
        }

        return $next($request);
    }
}
