<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class Ensure2FAIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next)
    {

         $user = auth()->user();

        Log::info('2FA Middleware Çalıştı', [
            'user_id' => optional($user)->id,
            'two_factor_enabled' => optional($user)->two_factor_enabled,
            'google2fa_secret' => optional($user)->google2fa_secret,
            '2fa_passed' => session()->get('2fa_passed'),
            'path' => $request->path()
        ]);

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->two_factor_enabled) {
            // Eğer kullanıcı 2FA'yı aktif etti ama secret'ı yoksa -> setup sayfasına yönlendir
            if (is_null($user->google2fa_secret)) {
                Log::info('2FA Middleware: Secret yok, setup sayfasına yönlendiriliyor.');
                return redirect()->route('2fa.setup');
            }

        
        }

        return $next($request);
    }

}
