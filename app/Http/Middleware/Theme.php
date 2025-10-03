<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Settings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;


class Theme
{
    public function handle($request, Closure $next)
    {


		 if(Storage::disk('public')->exists("settings.json")==false){
            return \response()->json("Ayarlar Dosyası Bulunamadı. Yönetim panelinden giriş yaparak ayar güncellemesi yapın."); die();
         }

		$settings = Cache::remember('settings', 60*60, function() {
				return Settings::first();
		});

        if($settings==null){ return \response()->json("Ayar tablosu boş !!! Kurucu yapı bulunamadı"); die(); }

        $jsondata = json_decode($settings->magicbox);
        $theme = $jsondata->sitetheme;

        if(is_null($theme) or empty($theme)) { $theme = 'default';}

        View::share([ 'theme' => $theme, 'theme_path' => 'themes.'.$theme ]);

        View::addNamespace('theme', resource_path('views/themes/'. $theme));

        return $next($request);
    }
}



















