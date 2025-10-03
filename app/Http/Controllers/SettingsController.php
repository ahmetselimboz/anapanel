<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menus;
use App\Models\Page;
use App\Models\Settings;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::first();
        $jsondata = json_decode($settings->magicbox);
        return view('backend.settings', compact('settings','jsondata'));
    }

    public function settingsupdate(Request $request)
    {
        $settings = Settings::find(1);
        $settings->title = strip_tags($request->title);
        $settings->description = strip_tags($request->description);
        $settings->maintenance = strip_tags($request->maintenance);

        $disk = Storage::build([ 'driver' => 'local',  'root' => '../public', ]);
        $disk->put('ads.txt', $request->adstext);

        $settings->magicbox = json_encode($request->except('_token'));

        if($request->hasFile('favicon')){
            if ($request->file('favicon')->isValid()) {
                $favicon_name = 'favicon.ico';
                $request->favicon->move(public_path('/'), $favicon_name);
                $settings->favicon = $favicon_name;
            }
        }

        if($request->hasFile('logo')){
            if ($request->file('logo')->isValid()) {
                $logo_name = 'logo-'.time().'.'.$request->logo->extension();
                $request->logo->move(public_path('uploads'), $logo_name);
                $settings->logo = $logo_name;
            }
        }

        if($request->hasFile('defaultimage')){
            if ($request->file('defaultimage')->isValid()) {
                $defaultimage = 'defaultimage.'.$request->defaultimage->extension();
                $request->defaultimage->move(public_path('uploads'), $defaultimage);
                $settings->defaultimage = $defaultimage;
            }
        }




        if($settings->save()){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            Storage::disk('public')->put('settings.json', json_encode($settings));
            return redirect(route('settings'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function menus()
    {
        $menus = Menus::all();

        return view('backend.menu.menus', compact('menus'));
    }

    public function menusCreate()
    {
        $menus = Menus::all();
        $categories = Category::where('category_type',0)->select('id','title','slug')->get();
        $pages = Page::select('id','title','slug')->get();

        return view('backend.menu.create', compact('menus','categories','pages'));
    }

    public function menusID(Request $request, $id)
    {
        $menus = Menus::findOrFail($id);
        $categories = Category::where('category_type',0)->select('id','title','slug')->get();
        $pages = Page::select('id','title','slug')->get();

        return view('backend.menu.edit', compact('menus','categories','pages'));
    }

    public function menusAjax(Request $request)
    {
        if($request->menu_id!=null){
            $menu = Menus::findOrFail($request->menu_id);
            $menu->title =  ($request->menu_title!="") ? strip_tags($request->menu_title) : "isimsiz";
            $menu->slug = slug_format($menu->title);
            $menu->jsonmenu = json_encode($request->serializedata);
            $menu->menulistdata = $request->menulistdata;
            $menu->save();
        }else{
            $menu = new Menus();
            $menu->title =  ($request->menu_title!="") ? strip_tags($request->menu_title) : "isimsiz";
            $menu->slug = slug_format($menu->title);
            $menu->jsonmenu = json_encode($request->serializedata);
            $menu->menulistdata = $request->menulistdata;
            if($menu->save()){
                Storage::disk('public')->put('menu'.$menu->id.'.json', json_encode($request->serializedata));
                return response()->json(["menu_id" => $menu->id], 200);
            }else{
                return response()->json("error", 200);
            }
        }

        $menujson = json_encode($request->serializedata);
        Storage::disk('public')->put('menu'.$menu->id.'.json', $menujson);

        if($menu->save()){
            return response()->json("ok", 200);
        }else{
            return response()->json("error", 200);
        }

    }

    public function menuTrashed(string $id)
    {
        $result = Menus::destroy($id);
        if($result){
            toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
            return redirect(route('menus'));
        }else{
            toastr()->error('Bir hata meydana geldi.','BAŞARISIZ', ['timeOut' => 5000]);
            return back();
        }
    }

    public function weather()
    {
        $settings = Settings::first();
        $jsondata = json_decode($settings->magicbox);
        if(isset($jsondata->weather_city) and $jsondata->weather_city!=0){
            if(isset($jsondata->weateher_token)){
                $weathercity = slug_format($jsondata->weather_city);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.collectapi.com/weather/getWeather?data.lang=tr&data.city=$weathercity",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "authorization: $jsondata->weateher_token",
                        "content-type: application/json"
                    ),
                ));
                $response = curl_exec($curl); $err = curl_error($curl); curl_close($curl);
                if ($err) {
                    return "cURL Hatası #:" . $err;
                } else {
                    Storage::disk('public')->put('weather.json', $response);
                }
            }else{
                return "Hava Durumu API Token tanımlı değil";
            }
        }else{
            return "Hava Durumu İl Seçimi Yapılmamış";
        }
    }

    public function prayer()
    {
        $settings = Settings::first();
        $jsondata = json_decode($settings->magicbox);
        if(isset($jsondata->prayer_city) and $jsondata->prayer_city!=0){
            if(isset($jsondata->weateher_token)){
                $prayercity = slug_format($jsondata->prayer_city);
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.collectapi.com/pray/all?data.city=$prayercity",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "authorization: $jsondata->weateher_token",
                        "content-type: application/json"
                    ),
                ));
                $response = curl_exec($curl); $err = curl_error($curl); curl_close($curl);
                if ($err) {
                    echo "cURL Hatası #:" . $err;
                } else {
                    Storage::disk('public')->put('prayer.json', $response);
                }
            }else{
                return "Namaz Vakti API Token tanımlı değil";
            }
        }else{
            return "Namaz Vakti İl Seçimi Yapılmamış";
        }
    }

    public function currency()
    {
        $settings = Settings::first();
        $jsondata = json_decode($settings->magicbox);

        if(isset($jsondata->weateher_token)){
            ## Kurlar
            $currencycurl = curl_init();
            curl_setopt_array($currencycurl, array(
                CURLOPT_URL => "https://api.collectapi.com/economy/allCurrency",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array("authorization: $jsondata->weateher_token", "content-type: application/json"),
            ));
            $currencyresult = curl_exec($currencycurl); $err = curl_error($currencycurl); curl_close($currencycurl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                Storage::disk('public')->put('currency.json', $currencyresult);
            }
        }else{
            return "Döviz Kurları API Token tanımlı değil";
        }

    }

    public function gold()
    {
        $settings = Settings::first();
        $jsondata = json_decode($settings->magicbox);

        if(isset($jsondata->weateher_token)){
            ## Altın
            $gold = curl_init();
            curl_setopt_array($gold, array(
                CURLOPT_URL => "https://api.collectapi.com/economy/goldPrice",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array( "authorization: $jsondata->weateher_token", "content-type: application/json" ),
            ));
            $goldresult = curl_exec($gold); $err = curl_error($gold); curl_close($gold);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                Storage::disk('public')->put('gold.json', $goldresult);
            }
        }else{
            return "Altın API Token tanımlı değil";
        }

    }

    public function coin()
    {
        $settings = Settings::first();
        $jsondata = json_decode($settings->magicbox);

        if(isset($jsondata->weateher_token)){
            ## Coin
            $coincurl = curl_init();
            curl_setopt_array($coincurl, array(
                CURLOPT_URL => "https://api.collectapi.com/economy/cripto",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array("authorization: $jsondata->weateher_token", "content-type: application/json"),
            ));
            $coinresult = curl_exec($coincurl); $err = curl_error($coincurl); curl_close($coincurl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                Storage::disk('public')->put('coin.json', $coinresult);
            }
        }else{
            return "Döviz Kurları API Token tanımlı değil";
        }

    }

    public function bist()
    {
        $settings = Settings::first();
        $jsondata = json_decode($settings->magicbox);

        if(isset($jsondata->weateher_token)){
            ## BIST
            $bistcurl = curl_init();
            curl_setopt_array($bistcurl, array(
                CURLOPT_URL => "https://api.collectapi.com/economy/borsaIstanbul",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array("authorization: $jsondata->weateher_token", "content-type: application/json"),
            ));
            $bistresult = curl_exec($bistcurl); $err = curl_error($bistcurl); curl_close($bistcurl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                Storage::disk('public')->put('bist.json', $bistresult);
            }
        }else{
            return "Bist API Token tanımlı değil";
        }

    }

    public function apiupdate()
    {
        $settings = Settings::first();
        $jsondata = json_decode($settings->magicbox);

        if($jsondata->apiservicestatus!=0){ return "Modül Ayarları > API SERVİSİ KAPALI OLDUĞUNDAN API SİSTEMİ ÇALIŞTIRILAMAZ"; }

        $this->weather();
        $this->prayer();
        $this->currency();
        $this->gold();
        $this->coin();
        $this->bist();

        toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
        return back();
    }
}
