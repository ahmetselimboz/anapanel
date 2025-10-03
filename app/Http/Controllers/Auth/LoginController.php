<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
   public function authenticated(Request $request, $user)
    {


        if ($user->two_factor_enabled) {
            if (is_null($user->google2fa_secret)) {
                Log::info('2FA: Kullanıcı 2FA aktif ama secret yok, setup sayfasına yönlendiriliyor.');
                return redirect()->route('2fa.setup');
            }

        }
        
        return redirect()->intended('/secure');
    }


    protected function redirectTo()
    {
         $user = Auth::user();

        if ($user && $user->two_factor_enabled) {
            if (is_null($user->google2fa_secret)) {
                return route('2fa.setup');
            }

     
        }
        
        return route('secure.index');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
            $user = auth()->user();
        
        if ($user) {
            $user->google2fa_secret = null;
            $user->save();
        }

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $request->session()->forget('2fa_passed');

        return redirect('/login');
    }
}
