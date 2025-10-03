<?php

namespace App\Http\Controllers;

use Detection\MobileDetect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    public function resizeImage(Request $request)
    {

        $imageUrl = $request->input('i_url');
        $baseWidth = request('w', 100);
        $baseHeight = request('h', 100);
        $deviceType = $this->detectDeviceType();
       (list($targetWidth, $targetHeight) = $this->calculateDimensions($baseWidth, $baseHeight, $deviceType));

        $cacheKey = md5($imageUrl . $targetWidth . $targetHeight . $deviceType);
        $cachePath = public_path('cache/images/' . $cacheKey . '.jpg');
        // Return cached image if exists

        if (file_exists($cachePath)) {
            return response()->file($cachePath);
        }

        $cacheDir = public_path('cache/images');
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }


        try {

            // $imageContents = $this->fetchImageFromUrl($imageUrl);
            // if (!$imageContents) {
            //     throw new \Exception("Resim alınamadı: $imageUrl");
            // }
            // $image = Image::make($imageContents);
            // $image->fit($targetWidth, $targetHeight);
            // $image->save($cachePath, 80);

            $image = Image::make($imageUrl);
            $image->fit($targetWidth, $targetHeight);
            $image->save($cachePath, 80); // 80% kalite ile kaydet - isteğe bağlı olarak ayarlanabilir

            return response()->file($cachePath);
        } catch (\Exception $e) {
            // Hata durumunda log tut
            Log::error('Resim yeniden boyutlandırma hatası: ' . $e);
            return $this->getPlaceholderImage($targetWidth, $targetHeight);
        }
    }


    /**
     * Resim gelmeme durumu için alternatif function
     */
        private function fetchImageFromUrl($url)
        {


            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Gerekirse true yapın
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; LaravelBot/1.0)');

            $data = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if ($httpCode === 200 && $data) {
                return $data;
            }

            return false;
        }


    /**
     * Detect device type
     *
     * @return string
     */
    private function detectDeviceType() {
        $detect = new MobileDetect();

        if ($detect->isMobile() && !$detect->isTablet()) {
            return 'mobile';
        } elseif ($detect->isTablet()) {
            return 'tablet';
        }
        return 'desktop';
    }

    /**
     * Calculate dimensions based on device type
     *
     * @param int $baseWidth
     * @param int $baseHeight
     * @param string $deviceType
     * @return array
     */
    private function calculateDimensions($baseWidth, $baseHeight, $deviceType) {
        switch ($deviceType) {
            case 'mobile':
                return [(int)($baseWidth * 3.5), (int)($baseHeight * 3.5)];
            case 'tablet':
                return [(int)($baseWidth * 2.25), (int)($baseHeight * 2.25)];
            default:
                return [(int)$baseWidth, (int)$baseHeight];
        }
    }


    /**
     * Get placeholder image
     *
     * @param int $width
     * @param int $height
     * @return \Illuminate\Http\Response
     */
    private function getPlaceholderImage($width, $height) {
        // Create a simple placeholder or return a static placeholder
        $placeholderPath = public_path('uploads/defaultimage.png');

        $settings = json_decode(Storage::disk('public')->get("settings.json"), TRUE);
        $placeholderPath = asset('uploads/' . $settings['defaultimage']);

        if (file_exists($placeholderPath)) {
            return response()->file($placeholderPath);
        }

        $img = Image::canvas($width, $height, '#f0f0f0');
        return $img->response('jpg');
    }



}
