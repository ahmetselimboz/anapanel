<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check() and Auth::user()->status==1)
        {
            return $next($request);
        }elseif(Auth::check() and Auth::user()->status==0){
            return redirect()->route('frontend.index');
        }else{
            return redirect()->route('login');
        }
    }
}
