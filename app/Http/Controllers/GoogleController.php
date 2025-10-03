<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use App\Models\User;


class GoogleController extends Controller
{
    public function authGoogle()
    {
        session(['redirect_url' => url()->previous()]);

        return Socialite::driver('google')->scopes([
            'https://mail.google.com/',
            'https://www.googleapis.com/auth/analytics.readonly',
            'https://www.googleapis.com/auth/analytics',
            'https://www.googleapis.com/auth/youtube.readonly',
            'https://www.googleapis.com/auth/youtube.force-ssl'
        ])->redirect();
    }

    public function callbackGoogle()
    {
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->user();

        // Token'ı oturuma kaydet
        session(['google_access_token' => $googleUser->token]);

        // Kullanıcının email adresini de oturuma kaydet
        session(['google_user_email' => $googleUser->email]);

        return redirect(session('redirect_url', '/secure'));
    }

    public function logout(Request $request)
    {
        session()->forget('google_access_token');
        session()->forget('google_user_email');

        return redirect()->back();
    }

    public function serp()
    {
        return view('backend.google.serp');
    }

    public function trends()
    {
        return view('backend.google.trend');
    }

    public function getTrends()
    {
        // Türkçe için Carbon ayarı
        Carbon::setLocale('tr');

        $rssUrl = 'https://trends.google.com/trending/rss?geo=TR';
        $response = Http::get($rssUrl);

        if ($response->failed()) {
            return response()->json(['error' => 'RSS verisi alınamadı'], 500);
        }

        // XML parse işlemi
        $xml = simplexml_load_string($response->body(), "SimpleXMLElement", LIBXML_NOCDATA);
        if (!$xml) {
            return response()->json(['error' => 'Geçersiz XML verisi'], 500);
        }

        $items = [];

        foreach ($xml->channel->item as $item) {
            $ht = $item->children('ht', true);

            // Zaman işlemleri
            $pubDateRaw = (string) $item->pubDate;
            $timeAgo = Carbon::parse($pubDateRaw)->diffForHumans();

            // Alt haberleri işle
            $newsItems = [];
            foreach ($ht->news_item ?? [] as $news) {
                $newsItems[] = [
                    'title' => (string) $news->news_item_title,
                    'url' => (string) $news->news_item_url,
                    'image' => (string) $news->news_item_picture,
                    'source' => (string) $news->news_item_source,
                ];
            }

            // Ana item yapısı
            $items[] = [
                'title' => (string) $item->title,
                'traffic' => (string) $ht->approx_traffic,
                'pubDate' => $pubDateRaw,
                'timeAgo' => $timeAgo,
                'picture' => (string) $ht->picture,
                'picture_source' => (string) $ht->picture_source,
                'news' => $newsItems,
            ];
        }

        return response()->json($items);
    }
    
    public function active(){
            $updated = User::whereIn('status', [1, 2])
        ->update(['two_factor_enabled' => true]);
        toastr()->success('İşlem başarıyla gerçekleşti.','BAŞARILI', ['timeOut' => 5000]);
        return redirect()->back();
    }


    public function setup()
    {
        $user = Auth::user();
        $google2fa = new Google2FA();

        $secret = $google2fa->generateSecretKey();
    

        $qrUrl = $google2fa->getQRCodeUrl(
            config("app.name"),
            $user->name,
            $secret
        );

        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(200),
                new SvgImageBackEnd()
            )
        );

        $qrCode = $writer->writeString($qrUrl);
      
        
        return view('auth.2fa.setup', compact('qrCode','secret'));

    }

    public function enable(Request $request)
    {
        $request->validate(['code' => 'required']);
        $secret = $request->input("secret");

        $google2fa = new Google2FA();
        $user = Auth::user();

        if ($google2fa->verifyKey($secret, $request->input('code'))) {
            $user->two_factor_enabled = true;
            $user->google2fa_secret = $secret;
          
            $user->save();
            toastr()->success('2FA etkinleştirildi!', 'BAŞARILI', ['timeOut' => 5000]);
            return redirect('/secure');
        }
        toastr()->error('Geçersiz doğrulama kodu', 'BAŞARISIZ', ['timeOut' => 5000]);
        return back();
    }

    public function verifyForm()
    {
         $user = Auth::user();
 
        $google2fa = new Google2FA();

        $secret = $google2fa->generateSecretKey();
     

        $qrUrl = $google2fa->getQRCodeUrl(
            config("app.name"),
            $user->name,
            $secret
        );

        $writer = new Writer(
            new ImageRenderer(
                new RendererStyle(200),
                new SvgImageBackEnd()
            )
        );

        $qrCode = $writer->writeString($qrUrl);
        // return response()->json([
        //     'qrCode' => $qrCode,
        //     'secret' => $secret
        // ]);

        
        
        return view('auth.2fa.setup', compact('qrCode','secret'));
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required']);
        $user = Auth::user();

        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($user->google2fa_secret, $request->input('code'))) {
            session(['2fa_passed' => true]);
            return redirect()->intended('/secure');
        }

        return back()->withErrors(['code' => 'Kod hatalı']);
    }

    public function disable(Request $request)
    {
        $user = Auth::user();

        // 2FA bilgilerini sıfırla
        $user->google2fa_secret = null;
        $user->two_factor_enabled = false;
        $user->save();

        // Oturumdaki doğrulama geçişini de sıfırla
        session()->forget('2fa_passed');

        toastr()->success('İki Faktörlü Doğrulama başarıyla devre dışı bırakıldı!', 'BAŞARILI', ['timeOut' => 5000]);
        return redirect()->back();
    }
}