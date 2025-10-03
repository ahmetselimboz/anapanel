<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Settings;
use App\Models\Post;
use App\Models\Category;
use App\Models\PhotoGallery;

class AgenciesController extends Controller
{
 public function getNews(Request $request, $agency)
    {
        if($agency == 'iha'){
                $news = $this->fetchIhaNews();
                return view("backend.agencies.iha", compact("news","agency"));
        }else if($agency == 'hibya'){
            $news = $this->fetchHibyaNews();
            
             return view("backend.agencies.hibya", compact("news","agency"));
        }
    

        return redirect()->back();
    }

     public function fetchIhaNews()
    {

        try {
            $url = $this->getIhaUrl();

            $response = $this->fetchIhaUrl($url);

            if (!$response) {
                return [
                    "data" => [],
                    "error" => true,
                ];
            }

            $xmlObject = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);

            if ($xmlObject === false) {
                $errors = libxml_get_errors(); // XML hatalarını al
                libxml_clear_errors();         // Hata tamponunu temizle

                return [];
            }

            $rssItems = [];
            foreach ($xmlObject->channel->item as $item) {

                $cleanContent = preg_replace('/\s+/', ' ', $item->description);

                // <br/> tagine göre bölme işlemi
                $parts = preg_split('/<br\s*\/?>/i', $cleanContent, 2); // İlk <br />'e kadar böler

                // İlk kısmı al ve temizle
                $small_description = isset($parts[0]) ? trim(strip_tags($parts[0])) : '';

                $rssItems[] = [
                    'haber_kodu' => (string) $item->HaberKodu,
                    'kategori' => (string) $item->Kategori,
                    'sehir' => (string) $item->Sehir,
                    'title' => (string) $item->title,
                    'description' => (string) $item->description,
                    'small_description' => $small_description,
                    'pubDate' => (string) $item->pubDate,
                    'images' => (array) $item->images->image,
                    'small_images' => (array) $item->small_images->small_image,
                ];
            }
            // 📌 **Filtreleme için Kullanıcıdan Gelen Parametreleri Al**
            $filterCategory = request()->query('kategori');
            $filterCity = request()->query('sehir');
            $filterDate = request()->query('pubDate');

            $filteredItems = ($filterCategory || $filterCity || $filterDate)
                ? array_filter($rssItems, function ($item) use ($filterCategory, $filterCity, $filterDate) {
                    $matchCategory = !$filterCategory || $item['kategori'] === $filterCategory;
                    $matchCity = !$filterCity || $item['sehir'] === $filterCity;

                    $itemDate = \DateTime::createFromFormat("d.m.Y H:i", $item['pubDate']);
                    $formattedDate = $itemDate ? $itemDate->format("Y-m-d") : null;

                    $matchDate = !$filterDate || ($formattedDate === $filterDate);

                    return $matchCategory && $matchCity && $matchDate;
                })
                : $rssItems; // 📌 Eğer query gelmezse, tüm haberleri döndür

            $perPage = 10; // Sayfa başına gösterilecek haber sayısı
            $currentPage = request()->query('page', 0); // URL'den "page" parametresini al
            $currentItems = array_slice($filteredItems, ($currentPage) * $perPage, $perPage);

            // Kategorileri bir array olarak al
            $categories = array_map(fn($item) => $item["kategori"], $rssItems);
            $cities = array_map(fn($item) => $item["sehir"], $rssItems);

            $categories = array_unique($categories);
            $cities = array_unique($cities);

            $categories = array_values($categories);
            $cities = array_values($cities);

            return [
                'data' => $currentItems,
                'total' => count($filteredItems),
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => ceil(count($filteredItems) / $perPage),
                "categories" => $categories,
                "cities" => $cities,
            ];
        } catch (Exception $e) {

            return [
                "data" => [],
                "error" => true,
            ];
        }
    }

    protected function getIhaUrl()
    {
        $settings = Settings::first();
        $jsondata = json_decode($settings->magicbox);

        $user_code = $jsondata->iha_user_code ?? null;
        $user_name = $jsondata->iha_user_name ?? null;
        $password = $jsondata->iha_user_password ?? null;

        if ($user_code === null || $user_name === null || $password === null) {
            return null;
        }

        return
            "https://abone.iha.com.tr/HamHaber/Rss?Sehir=0&UserCode={$user_code}&UserName={$user_name}&UserPassword={$password}";
    }

    protected function fetchIhaUrl($url)
    {
        try {
            $client = new Client();
            $res = $client->get($url);

            if ($res->getStatusCode() == 200) {
                $body = (string) $res->getBody();

                // Eğer gelen cevap XML değilse boş array dön
                if (strpos(trim($body), '<') !== 0) {
                    return null;
                }
                return $body;
            }
        } catch (Exception $e) {
            return null;
        }
        return null;
    }
    
 public function fetchHibyaNews()
{
    try {
        
               $settings = Settings::first();
        $jsondata = json_decode($settings->magicbox);
        
        
        
        $url = $jsondata->hibya_rss_link ?? null;

        $response = $this->fetchIhaUrl($url); // Gerekirse fetchUrl() olarak da değiştirebilirsin

        if (!$response) {
            return [
                "data" => [],
                "error" => true,
            ];
        }

        $xmlObject = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($xmlObject === false) {
            libxml_clear_errors();
            return [];
        }

        $rssItems = [];

        foreach ($xmlObject->channel->item as $item) {
            $description = html_entity_decode((string) $item->description);
            $summary = (string) $item->summary;
            $cleanSummary = trim(strip_tags($summary));

            // pubDate formatlama
            $rawDate = (string) $item->pubDate;
            $dateObj = \DateTime::createFromFormat(DATE_RSS, $rawDate, new \DateTimeZone('UTC'));
            $formattedPubDate = $dateObj ? $dateObj->setTimezone(new \DateTimeZone('Europe/Istanbul'))->format('d.m.Y H:i') : null;
            $sqlDate = $dateObj ? $dateObj->format('Y-m-d') : null;

            // Kategorileri al
            $category = $item->category;
            // foreach ($item->category as $cat) {
            //     $categories[] = trim((string) $cat);
            // }

            $rssItems[] = [
                'id' => (string) $item->id,
                'title' => (string) $item->title,
                'description' => $description,
                'small_description' => $cleanSummary,
                'pubDate' => $formattedPubDate, // Kullanıcıya gösterilecek
                'sqlDate' => $sqlDate,           // Filtreleme için
                'sehir' => trim((string) $item->locationcity) ?: 'Bilinmiyor',
                'ulke' => trim((string) $item->location) ?: 'Türkiye',
                'kategori' => (string) $item->category,
                'image' => (string) $item->photos,
                'link' => (string) $item->link,
            ];
        }

        // Filtre parametreleri
        $filterCategory = request()->query('kategori');
        $filterCity = request()->query('sehir');
        $filterDate = request()->query('pubDate');

        // Filtreleme
        $filteredItems = ($filterCategory || $filterCity || $filterDate)
            ? array_filter($rssItems, function ($item) use ($filterCategory, $filterCity, $filterDate) {
                $matchCategory = !$filterCategory || str_contains($item['kategori'], $filterCategory);
                $matchCity = !$filterCity || str_contains($item['sehir'], $filterCity);
                $matchDate = !$filterDate || ($item['sqlDate'] === $filterDate);
                return $matchCategory && $matchCity && $matchDate;
            })
            : $rssItems;

        // Sayfalama
        $perPage = 10;
        $currentPage = request()->query('page', 0);
        $currentItems = array_slice($filteredItems, $currentPage * $perPage, $perPage);

        // Kategorileri ve şehirleri topluca al
        $categories = array_unique(array_reduce($rssItems, function ($carry, $item) {
            return array_merge($carry, explode(", ", $item["kategori"]));
        }, []));
        $cities = array_unique(array_map(fn($item) => $item["sehir"], $rssItems));

        return [
            'data' => $currentItems,
            'total' => count($filteredItems),
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'last_page' => ceil(count($filteredItems) / $perPage),
            "categories" => array_values($categories),
            "cities" => array_values($cities),
        ];
    } catch (Exception $e) {
        return [
            "data" => [],
            "error" => true,
        ];
    }
}



    public function getIhaNewsPost(Request $request)
    {

        $title = $request->get("title");
        $detail = $request->get("detail");
        $description = $request->get("description");
        $imageURL = $request->get("image");

        if (!$request->filled(['title', 'detail', 'description', 'image'])) {
            return redirect()->back();
        }

        $categories = Category::where('category_type', 0)->select('id', 'title')->get();
        $related_news = Post::where('publish', 0)->select('id', 'title')->get();
        $related_photos = PhotoGallery::where('publish', 0)->select('id', 'title')->limit(33)->get();

        return view("backend.post.create", compact(
            'detail',
            'title',
            'description',
            'categories',
            'related_news',
            'related_photos',
            "imageURL"
        ));
    }
}