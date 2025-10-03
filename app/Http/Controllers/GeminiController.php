<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Settings;

class GeminiController extends Controller
{
    public function chat(Request $request)
    {
        $settings = Settings::first();
        $jsondata = json_decode($settings->magicbox);
        $gemini_api_key = $jsondata->gemini_api_key;

        if($gemini_api_key==null){
            return response()->json([
                "error" => "Google Gemini API'ye bağlanılamadı! API Key'inizin var olduğundan emin olunuz."
            ]);
        }


        $userMessage = $request->input('message');
        $apiKey = $gemini_api_key;

        // System Prompt'u Kullanıcı Mesajının Başına Ekleyelim
        $systemPrompt = $request->input('prompt');

        // Prompt + Kullanıcı Mesajı
        $fullMessage = $systemPrompt . "\n\n" . $userMessage;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                    "contents" => [
                        [

                            "parts" => [
                                ["text" => $fullMessage]
                            ]
                        ],

                    ]
                ]);



            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                "error" => "Google Gemini API'ye bağlanılamadı! API Key'inizin doğru olduğundan emin olunuz."
            ]);
        }
    }
}
