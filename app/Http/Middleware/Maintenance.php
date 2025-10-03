<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class Maintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Storage::disk('public')->exists("settings.json")==false){
             return \response()->json("Site Ayar Dosyası Bulunamadı. Panelden ayarları kontrol edin."); die();
             }

        $settings = json_decode(Storage::disk('public')->get("settings.json"), TRUE);

        if($settings["maintenance"]==0){
            return $next($request);
        }else{
            if (auth()->check() && auth()->user()){ 
                return $next($request);
            }else {
                return redirect(route('frontend.maintenance'));
            }
       
        }
    }
}
