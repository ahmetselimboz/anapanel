<?php

namespace App\Providers;

use App\Models\OfficialAdvert;
use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Settings;
use Carbon\Carbon;

class CustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {


    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('page')) {

            $pages = DB::connection()->table('page')->where('publish', 0)->whereNull('deleted_at')->select('id', 'slug', 'title')->get()->toArray();
            Config::set(['pages' => $pages]);
        }

        if (Schema::hasTable('settings')) {
            $settings = Settings::first();
            if ($settings) {
                $jsondata = json_decode($settings->magicbox, true);
                $google_client_id = $jsondata['google_client_id'] ?? 0000;
                $google_client_secret = $jsondata['google_client_secret'] ?? 0000;
                $google_redirect_url = $jsondata['google_redirect_url'] ?? 0000;
                if ($settings && isset($settings->magicbox)) {
                    Config::set('services.google', [
                        'client_id' => $google_client_id,
                        'client_secret' => $google_client_secret,
                        'redirect' => $google_redirect_url,
                    ]);
                }
            }
        }
        if (Schema::hasTable('official_advert')) {
            $officialExists=  OfficialAdvert::where('publish',0)
                ->whereDate('create_date', Carbon::today())
                ->orderBy('id', 'desc')->exists();
            if (!blank( $officialExists)) {
                $officialAdvert=  OfficialAdvert::where('publish',0)
                ->select('id', 'title', 'created_at', 'images')
                ->whereDate('create_date', Carbon::today())
                ->orderBy('id', 'desc')->get();
                Config::set('official_advert', $officialAdvert->toArray());
            }
        }

       


    }
}
