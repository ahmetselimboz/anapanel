<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeploymentController extends Controller
{
    private function runCommand(string $command): string
    {
        // SQL veya diğer enjeksiyonları önlemek için kritik güvenlik adımı
        $safeCommand = escapeshellcmd($command);
        
        // shell_exec() yerine exec() kullanmak daha iyi olabilir, 
        // ancak shell_exec() tüm çıktıyı yakalar.
        $output = shell_exec($safeCommand . ' 2>&1'); // Hata ve çıktıyı yakalar
        
        Log::info("Command executed: {$safeCommand}");
        Log::info("Output: {$output}");
        
        return $output;
    }

    /**
     * Dağıtım ve Build komutlarını sunucuda çalıştıran ana metot.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function runBuild(Request $request)
    {
        // 1. GÜVENLİK KONTROLÜ (KRİTİK!)
        // DEPLOY_TOKEN_FROM_ENV değeri .env dosyanızda olmalıdır.
        if ($request->token !== env('DEPLOY_TOKEN_FROM_ENV')) {
            Log::warning('Yetkisiz dağıtım denemesi.');
            abort(403, 'Yetkisiz erişim: Geçersiz token.');
        }

        $results = [
            'status' => 'Dağıtım Başladı',
            'timestamp' => now()->toDateTimeString(),
        ];
        
        // Sunucu kök dizininde çalıştığımızdan emin olmak için
        chdir(base_path());

        try {
            // 2. TEMİZLEME ve ZIP'TEN ÇIKARMA (Vendor/Node_modules/Public)
            
            // Önceki vendor ve node_modules'ü kaldırıyoruz.
            $results['clean_up'] = $this->runCommand('rm -rf vendor node_modules public');

            // deployment_assets.zip dosyasını sunucuda açma.
            // Bu adım, vendor, node_modules ve public klasörlerini yerlerine yerleştirir.
            $results['unzip'] = $this->runCommand('unzip -o deployment_assets.zip');
            
            // 3. LARAVEL KOMUTLARI
            
            // Veritabanı güncellemeleri
            $results['artisan_migrate'] = $this->runCommand('php artisan migrate --force');

            // Cache temizleme ve optimizasyon
            $results['artisan_cache_clear'] = $this->runCommand('php artisan cache:clear');
            $results['artisan_config_cache'] = $this->runCommand('php artisan config:cache');
            
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
