<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Exception;

class FacebookController extends Controller
{
    protected $fb;

    public function __construct()
    {
        $this->fb = new Facebook([
            'app_id' => config("services.facebook.app_id",00000),
            'app_secret' => config("services.facebook.app_secret",00000),
            'default_graph_version' => 'v12.0',
        ]);
    }

    /**
     * Kullanıcıyı Facebook giriş ekranına yönlendirir.
     */
    public function redirectToFacebook()
    {
        session(['fb_redirect_url' => url()->previous()]);

        $helper = $this->fb->getRedirectLoginHelper();
        $permissions = ['email', 'pages_show_list', 'pages_read_engagement'];
        $loginUrl = $helper->getLoginUrl(route('facebook.callback'), $permissions);

        return redirect($loginUrl);
    }

    /**
     * Facebook giriş sonrası callback işlemini yönetir.
     */
    public function handleFacebookCallback()
    {
        try {
            $helper = $this->fb->getRedirectLoginHelper();

            if (request('state')) {
                $helper->getPersistentDataHandler()->set('state', request('state'));
            }

            $accessToken = $helper->getAccessToken();
            if (!$accessToken) {
                throw new Exception('Access token could not be obtained.');
            }

            // Token'ı uzun ömürlü hale getir
            $oAuth2Client = $this->fb->getOAuth2Client();
            $longLivedToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

            session(["fb_access_token" => $longLivedToken]);

            return redirect(session('fb_redirect_url', '/secure'));

        } catch (Exception $e) {
            return response()->json(['error' => 'Graph API Error: ' . $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'Facebook SDK Error: ' . $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'General Error: ' . $e->getMessage()], 500);
        }
    }


    public function getPageId()
    {

        try {
            if (!session()->has('fb_access_token')) {

                return redirect()->route("facebook.redirect");
            }

            $longLivedToken = session("fb_access_token");

            // Kullanıcı bilgilerini al
            $userResponse = $this->fb->get('/me?fields=id,name,email', $longLivedToken->getValue());
            $user = $userResponse->getGraphUser();

            // Kullanıcının yönetici olduğu sayfaları al
            $pageResponse = $this->fb->get('/me/accounts', $longLivedToken->getValue());
            $pages = $pageResponse->getGraphEdge();

            // $pageInfo = collect($pages)->map(fn($page) => [
            //     'id' => $page->getField('id'),
            //     'name' => $page->getField('name'),
            //     'access_token' => $page->getField('access_token') ?? null
            // ])->toArray();

            session(['page_id' => $pages[0]->getField('id'), 'page_access_token' => $pages[0]->getField('access_token')]);

            return $pages[0]->getField('id');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function shareNews(Request $request)
    {
        try {

            if (!session()->has('page_id') || !session()->has('page_access_token')) {

                $this->getPageId();
            }

            $pageId = session('page_id');
            $pageAccessToken = session('page_access_token');
            // Haber bilgilerini al
            $newsTitle = $request->input('title');
            $newsUrl = $request->input('url');

            // Facebook gönderisi
            $response = $this->fb->post("/{$pageId}/feed", [
                'link' => $newsUrl, // 'url' yerine 'link' kullanılmalı
                'message' => "**$newsTitle**\n\n" . $newsUrl
            ], $pageAccessToken);

            toastr()->success('İşlem başarıyla gerçekleşti.', 'BAŞARILI', ['timeOut' => 5000]);

            if (!session()->has('fb_redirect_url')) {
                return redirect()->back();
            } else {
                return redirect("/secure/post");
            }

        } catch (Exception $e) {
            if($e->getMessage() === "You must provide an access token."){
                return redirect()->route("facebook.redirect");
            }
            return 'Graph Hatası: ' . $e->getMessage();
        } catch (Exception $e) {

            return 'Facebook SDK Hatası: ' . $e->getMessage();
        }
    }
}
