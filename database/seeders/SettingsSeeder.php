<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Settings::create([
            'title' => "Ers Danışmanlık",
            'description' => "Ers Danışmanlık Hizmetleri tarafından hazırlanan proje için demo içerik ilk kurulumda otomatik olarak eklenmiştir lütfen güncelleyin.",
            'magicbox' => json_encode([
            "fb"=> null,
            "in"=> null,
            "ln"=> null,
            "tw"=> null,
            "yt"=> null,
            "email"=> null,
            "phone"=> "0",
            "title"=> "Haber Sistemi",
            "_token"=> "TmVFFYTlLTpspl2M3tM8eHD9CYgcVkpM3BeEnb63",
            "address"=> null,
            "adstext"=> "ads text icerisi güncelliyorum",
            "refresh"=> null,
            "bodycode"=> null,
            "headcode"=> null,
            "keywords"=> null,
            "smtpport"=> "25",
            "smtpuser"=> "spam@cihan.com",
            "copyright"=> null,
            "sitetheme"=> "custom",
            "footercode"=> null,
            "googlenews"=> null,
            "rightclick"=> "0",
            "smtpserver"=> "mail.cihan.com",
            "appstoreurl"=> "aaa",
            "description"=> "Ers Danışmanlık Hizmetleri tarafından hazırlanan proje için demo içerik ilk kurulumda otomatik olarak eklenmiştir lütfen güncelleyin.",
            "generalfont"=> "0",
            "maintenance"=> "1",
            "mansetlimit"=> "10",
            "prayer_city"=> "İstanbul",
            "externallink"=> "1",
            "incomingnews"=> "0",
            "playstoreurl"=> "bbbb",
            "smtppassword"=> "123455",
            "weather_city"=> "İstanbul",
            "author_status"=> "0",
            "prayer_status"=> "0",
            "generalcomment"=> "0",
            "generalhitshow"=> "0",
            "mansetaciklama"=> "0",
            "weateher_token"=> "apikey 0aOIX6WKD5A4YT0dxCqx2J=>0dbBrqK3Hp9JqyVn76V0di",
            "weather_status"=> "0",
            "currency_status"=> "0",
            "googleanalytics"=> null,
            "gemini_api_key"=> null,
            "google_property_id"=> null,
            "google_client_id"=> null,
            "google_client_secret"=> null,
            "google_redirect_url"=> null,
            "pharmacy_status"=> "0",
            "yandexanalytics"=> null,
            "yandexmetricaid"=> null,
            "apiservicestatus"=> "1",
            "mansetsabitreklamno"=> "6",
            "archive_recording_time" => "365",
            "iha_user_code"=> null,
            "iha_user_name"=> null,
            "iha_user_password"=> null,
            "weather_link"=> null,
            "pharmacy_link"=> null,
            "deceased_link"=> null,
            "traffic_link"=> null,
            "social_media_link1"=> null,
            "social_media_link2"=> null,
            "live_stream_name"=> null,
            "live_stream_link"=> null,
            "google_recaptcha_site_key"=> null,
            "google_recaptcha_secret_key"=> null,

            ])
        ]);
        
        Category::create([
            'title' => "Genel",
            'slug' => "genel",
            'category_type' => 0,
            'parent_category' => 0,
            'publish' => 0,
            'description' => "Genel haber kategorisi",
        ]);
    }
}