<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class DeploymentController extends Controller
{
    /**
     * Güvenli dosya/klasör silme
     */
    private function deletePath($path)
    {
        if (is_dir($path)) {
            // Klasörü recursive sil
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $file) {
                $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
            }
            rmdir($path);
        } elseif (is_file($path)) {
            unlink($path);
        }
    }

    /**
     * ZIP dosyasını güvenli şekilde açma
     */
    private function unzipFile($zipFile, $extractTo)
    {
        $zip = new \ZipArchive;
        if ($zip->open($zipFile) === true) {
            $zip->extractTo($extractTo);
            $zip->close();
            return "Zip başarıyla açıldı.";
        } else {
            return "Zip açılamadı.";
        }
    }

    /**
     * Dağıtım ve Build komutlarını sunucuda çalıştıran ana metot.
     */
    public function runBuild(Request $request)
    {
        // 1. GÜVENLİK KONTROLÜ
        if ($request->token !== env('DEPLOY_TOKEN_FROM_ENV')) {
            Log::warning('Yetkisiz dağıtım denemesi.');
            abort(403, 'Yetkisiz erişim: Geçersiz token.');
        }

        $results = [
            'status' => 'Dağıtım Başladı',
            'timestamp' => now()->toDateTimeString(),
        ];

        try {
            // 2. TEMİZLEME
            $this->deletePath(base_path('vendor'));
            $this->deletePath(base_path('node_modules'));
            // !!! public komple silinmiyor !!!
            $results['clean_up'] = "vendor ve node_modules silindi.";

            // 3. ZIP'ten çıkarma
            $results['unzip'] = $this->unzipFile(
                base_path('deployment_assets.zip'),
                base_path()
            );

            // 4. ARTISAN KOMUTLARI (PHP içinden)
            Artisan::call('migrate', ['--force' => true]);
            $results['artisan_migrate'] = Artisan::output();

            Artisan::call('cache:clear');
            $results['artisan_cache_clear'] = Artisan::output();

            Artisan::call('config:cache');
            $results['artisan_config_cache'] = Artisan::output();

            $results['status'] = 'Dağıtım ve Build Başarıyla Tamamlandı.';

        } catch (\Exception $e) {
            Log::error("Dağıtım sırasında hata oluştu: " . $e->getMessage());
            return response()->json([
                'status' => 'HATA',
                'message' => 'Sunucuda komutlar çalıştırılırken bir sorun oluştu.',
                'error' => $e->getMessage(),
                'results' => $results,
            ], 500);
        }

        return response()->json($results);
    }
}
