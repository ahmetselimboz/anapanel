<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Service_Gmail;
use Google_Client;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Category;
use App\Models\PhotoGallery;
use App\Models\Settings;

class MailController extends Controller
{
    public function getGmailPage(Request $request)
    {
        try {
   
            $settings = Settings::first();
            $jsondata = json_decode($settings->magicbox);
            $google_client_id = $jsondata->google_client_id;
            $google_client_secret = $jsondata->google_client_secret;
            $google_redirect_url = $jsondata->google_redirect_url;
            

            if($google_client_id === "#" || $google_client_secret === "#" || $google_redirect_url === "#"){
                toastr()->error('Ayarlar > Analitik&Kod sayfasındaki "Google Client ID" alanını doldurunuz!','BAŞARISIZ', ['timeOut' => 5000]);
                toastr()->error('Ayarlar > Analitik&Kod sayfasındaki "Google Client Secret" alanını doldurunuz!','BAŞARISIZ', ['timeOut' => 5000]);
                toastr()->error('Ayarlar > Analitik&Kod sayfasındaki "Google Redirect Url" alanını doldurunuz!','BAŞARISIZ', ['timeOut' => 5000]);
                return view("backend.google.gmail", ['emails' => [], 'nextPageToken' => null, 'prevPageToken' => null]);
            }
            
            
            if (!session()->has('google_access_token')) {
                return view("backend.google.gmail", ['emails' => [], 'nextPageToken' => null, 'prevPageToken' => null]);
            }

            $client = new Google_Client();

            $token = session('google_access_token');
            $client->setAccessToken($token);


            $service = new Google_Service_Gmail($client);

            // Sayfalama için API'den gelen token
            $pageToken = $request->query('pageToken', null);

            // Google API'den e-posta listesini çek
            $params = [
                'maxResults' => 10, // Sayfa başına 10 e-posta
            ];
            if ($pageToken) {
                $params['pageToken'] = $pageToken;
            }

            $messagesResponse = $service->users_messages->listUsersMessages('me', $params);

            $emails = [];
            foreach ($messagesResponse->getMessages() as $message) {
                $msg = $service->users_messages->get('me', $message->getId(), ['format' => 'full']);
                $payload = $msg->getPayload();
                $headers = $payload->getHeaders();
                $body = '';

                $from = $subject = $date = '';

                foreach ($headers as $header) {
                    if ($header->getName() == 'Subject') {
                        $subject = $header->getValue();
                    }
                    if ($header->getName() == 'From') {
                        $from = $header->getValue();
                    }
                    if ($header->getName() == 'Date') {
                        $date = $header->getValue();
                    }
                }

                // E-posta içeriğini al
                if ($payload->getParts()) {
                    foreach ($payload->getParts() as $part) {
                        if ($part->getMimeType() === 'text/html') {
                            $body = $this->base64url_decode($part->getBody()->getData());
                            break;
                        } elseif ($part->getMimeType() === 'text/plain' && empty($body)) {
                            $body = nl2br($this->base64url_decode($part->getBody()->getData()));
                        }
                    }
                } elseif ($payload->getBody()) {
                    $body = nl2br($this->base64url_decode($payload->getBody()->getData()));
                }

                $emails[] = [
                    'id' => $message->getId(),
                    'from' => $from,
                    'subject' => $subject,
                    'date' => $date,
                    'snippet' => rawurlencode($body),
                ];
            }

            // Sayfalama tokenlarını al
            $nextPageToken = $messagesResponse->getNextPageToken() ?? null;
            $prevPageToken = $pageToken; // Geri gitmek için mevcut sayfanın token'ını saklıyoruz

            return view('backend.google.gmail', compact('emails', 'nextPageToken', 'prevPageToken'));
        } catch (\Throwable $e) {

            session()->forget('google_access_token');

            return redirect()->back();

        }
    }

    // URL Safe Base64 Decode Fonksiyonu
    private function base64url_decode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }


    public function getGmailPost(Request $request)
    {
        $detailData = $request->get("detail");
        $detail = urldecode($detailData);


        $categories = Category::where('category_type', 0)->select('id', 'title')->get();
        $related_news = Post::where('publish', 0)->select('id', 'title')->get();
        $related_photos = PhotoGallery::where('publish', 0)->select('id', 'title')->limit(33)->get();
        $settings = Settings::first();
        $jsondata = json_decode($settings->magicbox);
        $gemini_api_key = $jsondata->gemini_api_key;

        return view("backend.post.create", compact('detail', 'categories', 'related_news', 'related_photos', "gemini_api_key"));

    }
}