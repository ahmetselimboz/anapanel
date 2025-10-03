<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PluginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Plugin;
use Illuminate\Http\JsonResponse;

class PluginController extends Controller
{
    protected $pluginService;

    public function __construct(PluginService $pluginService)
    {
        $this->pluginService = $pluginService;
    }

    public function index()
    {
        $installedPlugins = $this->pluginService->getInstalledPlugins();
        $availablePlugins = $this->pluginService->getAvailablePlugins();


        return view('admin.plugins.index', compact('installedPlugins', 'availablePlugins'));
    }
    
    public function getPlugins(): JsonResponse
    {
        
         // Eğer auth zorunluysa ama misafire de veri dönecekse:
        // if (!auth()->check()) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        
        $availablePlugins = $this->pluginService->getAvailablePlugins();
    
        return response()->json($availablePlugins);
    }

    public function create()
    {
        return view('admin.plugins.create');
    }

    public function edit($pluginName)
    {
        $pluginPath = base_path("plugins/{$pluginName}");
        $configFile = "{$pluginPath}/plugin.json";
        
        if (!File::exists($pluginPath) || !File::exists($configFile)) {
            return redirect()->route('admin.plugins.index')->with('error', 'Plugin bulunamadı!');
        }
        
        $config = json_decode(File::get($configFile), true);
        
        // Plugin kurulu mu kontrol et
        $installedPlugin = Plugin::where('name', $pluginName)->first();
        
        $plugin = [
            'name' => $pluginName,
            'title' => $config['title'] ?? '',
            'description' => $config['description'] ?? '',
            'version' => $config['version'] ?? '',
            'author' => $config['author'] ?? '',
            'has_files' => File::exists($pluginPath . '/src') || File::exists($pluginPath . '/routes'),
            'is_installed' => $installedPlugin ? true : false,
            'status' => $installedPlugin ? $installedPlugin->status : null,
        ];
        
        return view('admin.plugins.edit', compact('plugin'));
    }

    public function update(Request $request, $pluginName)
    {
        $request->validate([
            'plugin_title' => 'required|string|max:100',
            'plugin_description' => 'nullable|string',
            'plugin_version' => 'required|string|max:20',
            'plugin_author' => 'required|string|max:100',
            'add_files' => 'nullable|boolean',
        ]);

        try {
            $pluginPath = base_path("plugins/{$pluginName}");
            $configFile = "{$pluginPath}/plugin.json";
            
            if (!File::exists($pluginPath) || !File::exists($configFile)) {
                return redirect()->route('admin.plugins.index')->with('error', 'Plugin bulunamadı!');
            }
            
            $config = json_decode(File::get($configFile), true);
            
            // Config'i güncelle
            $config['title'] = $request->input('plugin_title');
            $config['description'] = $request->input('plugin_description');
            $config['version'] = $request->input('plugin_version');
            $config['author'] = $request->input('plugin_author');
            
            // Dosya ekleme isteği varsa ve henüz dosyalar yoksa
            if ($request->input('add_files') && !File::exists($pluginPath . '/src')) {
                $this->createBasicPluginFiles($pluginPath, $pluginName);
                $config['service_provider'] = 'App\\Plugins\\' . Str::studly($pluginName) . '\\Providers\\' . Str::studly($pluginName) . 'ServiceProvider';
            }
            
            // plugin.json'ı güncelle
            File::put($configFile, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            // Composer autoload'u güncelle
            $this->pluginService->updateComposerAutoload();
            
            return redirect()->route('admin.plugins.index')
                           ->with('success', 'Plugin başarıyla güncellendi!');
                           
        } catch (\Exception $e) {
            Log::error("Plugin update error: " . $e->getMessage());
            return back()->withErrors(['error' => 'Plugin güncellenirken hata oluştu: ' . $e->getMessage()])->withInput();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'plugin_file' => 'nullable|file|mimes:zip|max:10240', // 10MB max
            'plugin_name' => 'required|string|max:50|regex:/^[a-z][a-z0-9-]*$/',
            'plugin_title' => 'required|string|max:100',
            'plugin_description' => 'nullable|string',
            'plugin_version' => 'required|string|max:20',
            'plugin_author' => 'required|string|max:100',
        ], [
            'plugin_file.mimes' => 'Sadece ZIP dosyaları kabul edilir.',
            'plugin_file.max' => 'Dosya boyutu en fazla 10MB olabilir.',
            'plugin_name.required' => 'Plugin adı gereklidir.',
            'plugin_name.regex' => 'Plugin adı harf ile başlamalı ve sadece küçük harf, rakam ve tire içerebilir.',
            'plugin_title.required' => 'Plugin başlığı gereklidir.',
            'plugin_version.required' => 'Plugin versiyonu gereklidir.',
            'plugin_author.required' => 'Plugin yazarı gereklidir.',
        ]);

        try {
            $pluginName = $request->input('plugin_name');
            $pluginsPath = base_path('plugins');
            
            // Plugin dizini zaten var mı kontrol et
            if (File::exists($pluginsPath . '/' . $pluginName)) {
                return back()->withErrors(['plugin_name' => 'Bu isimde bir plugin zaten mevcut.'])->withInput();
            }

            $pluginOnlyName = !$request->hasFile('plugin_file');

            // ZIP dosyası yüklendiyse işle
            if ($request->hasFile('plugin_file')) {
                $uploadedFile = $request->file('plugin_file');
                $tempPath = $uploadedFile->storeAs('temp/plugins', $pluginName . '.zip');

                // ZIP dosyasını çıkart
                $zip = new \ZipArchive;
                if ($zip->open(storage_path('app/' . $tempPath)) === TRUE) {
                    $zip->extractTo($pluginsPath . '/' . $pluginName);
                    $zip->close();
                } else {
                    throw new \Exception('ZIP dosyası açılamadı.');
                }

                // Geçici dosyayı sil
                Storage::delete($tempPath);
            } else {
                // ZIP dosyası yoksa sadece plugin dizinini oluştur
                File::makeDirectory($pluginsPath . '/' . $pluginName, 0755, true, true);
            }

            // plugin.json dosyasını oluştur
            $pluginConfig = [
                'name' => $pluginName,
                'title' => $request->input('plugin_title'),
                'description' => $request->input('plugin_description'),
                'version' => $request->input('plugin_version'),
                'author' => $request->input('plugin_author'),
                'service_provider' => $pluginOnlyName ? null : 'App\\Plugins\\' . Str::studly($pluginName) . '\\Providers\\' . Str::studly($pluginName) . 'ServiceProvider',
                'dependencies' => [],
                'menu' => [
                    'main' => [
                        'title' => $request->input('plugin_title'),
                        'icon' => 'fa fa-cube',
                        'route' => $pluginName . '.index'
                    ],
                    'submenu' => [
                        [
                            'title' => 'Ana Sayfa',
                            'icon' => 'fa fa-home',
                            'route' => $pluginName . '.index'
                        ],
                        [
                            'title' => 'Ayarlar',
                            'icon' => 'fa fa-cog',
                            'route' => $pluginName . '.settings'
                        ]
                    ]
                ],
                'settings' => [
                    'title' => $request->input('plugin_title'),
                    'description' => $request->input('plugin_description'),
                    'custom_settings' => []
                ]
            ];

            File::put(
                $pluginsPath . '/' . $pluginName . '/plugin.json',
                json_encode($pluginConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            // Sadece isimle eklendiyse ekstra dosya/dizin oluşturma (route hariç)
            if (!$pluginOnlyName) {
                $this->createBasicPluginFiles($pluginsPath . '/' . $pluginName, $pluginName);
            }

            // Composer autoload'u güncelle
            $this->pluginService->updateComposerAutoload();

            return redirect()->route('admin.plugins.index')
                           ->with('success', 'Plugin başarıyla oluşturuldu! Şimdi kurabilirsiniz.');

        } catch (\Exception $e) {
            Log::error("Plugin creation error: " . $e->getMessage());
            
            // Hata durumunda geçici dosyaları temizle
            if (isset($tempPath)) {
                Storage::delete($tempPath);
            }
            if (isset($pluginName) && File::exists($pluginsPath . '/' . $pluginName)) {
                File::deleteDirectory($pluginsPath . '/' . $pluginName);
            }

            return back()->withErrors(['error' => 'Plugin oluşturulurken hata oluştu: ' . $e->getMessage()])->withInput();
        }
    }

    private function createBasicPluginFiles($pluginPath, $pluginName)
    {
        $studlyName = Str::studly($pluginName);
        
        // Temel dizin yapısını oluştur (route hariç)
        $directories = [
            'src/Controllers',
            'src/Models',
            'src/Views',
            'src/Providers',
            'src/Services',
            'src/Jobs',
            'src/Console',
            'database/migrations',
            'assets/css',
            'assets/js',
            'assets/images'
        ];

        foreach ($directories as $dir) {
            File::makeDirectory($pluginPath . '/' . $dir, 0755, true, true);
        }

        // Temel controller oluştur
        $controllerContent = $this->getControllerTemplate($studlyName, $pluginName);
        File::put($pluginPath . '/src/Controllers/' . $studlyName . 'Controller.php', $controllerContent);

        // Temel service provider oluştur (route yükleme olmadan)
        $providerContent = $this->getBasicServiceProviderTemplate($studlyName, $pluginName);
        File::put($pluginPath . '/src/Providers/' . $studlyName . 'ServiceProvider.php', $providerContent);

        // Temel view dosyaları oluştur
        $this->createViewFiles($pluginPath, $pluginName, $studlyName);

        // README dosyası oluştur
        $readmeContent = $this->getReadmeTemplate($pluginName);
        File::put($pluginPath . '/README.md', $readmeContent);
    }

    private function createPluginStructure($pluginPath, $pluginName)
    {
        $studlyName = Str::studly($pluginName);
        
        // Temel dizin yapısını oluştur
        $directories = [
            'src/Controllers',
            'src/Models',
            'src/Views',
            'src/Providers',
            'src/Services',
            'src/Jobs',
            'src/Console',
            'database/migrations',
            'routes',
            'assets/css',
            'assets/js',
            'assets/images'
        ];

        foreach ($directories as $dir) {
            File::makeDirectory($pluginPath . '/' . $dir, 0755, true, true);
        }

        // Temel controller oluştur
        $controllerContent = $this->getControllerTemplate($studlyName, $pluginName);
        File::put($pluginPath . '/src/Controllers/' . $studlyName . 'Controller.php', $controllerContent);

        // Temel service provider oluştur
        $providerContent = $this->getServiceProviderTemplate($studlyName, $pluginName);
        File::put($pluginPath . '/src/Providers/' . $studlyName . 'ServiceProvider.php', $providerContent);

        // Temel route dosyası oluştur
        $routeContent = $this->getRouteTemplate($studlyName, $pluginName);
        File::put($pluginPath . '/routes/web.php', $routeContent);

        // Temel view dosyaları oluştur
        $this->createViewFiles($pluginPath, $pluginName, $studlyName);

        // README dosyası oluştur
        $readmeContent = $this->getReadmeTemplate($pluginName);
        File::put($pluginPath . '/README.md', $readmeContent);
    }

    private function getControllerTemplate($studlyName, $pluginName)
    {
        return "<?php

namespace App\\Plugins\\{$studlyName}\\Controllers;

use App\\Http\\Controllers\\Controller;
use Illuminate\\Http\\Request;

class {$studlyName}Controller extends Controller
{
    public function index()
    {
        return view('{$pluginName}::index');
    }

    public function settings()
    {
        return view('{$pluginName}::settings');
    }
}";
    }

    private function getBasicServiceProviderTemplate($studlyName, $pluginName)
    {
        return "<?php

namespace App\\Plugins\\{$studlyName}\\Providers;

use Illuminate\\Support\\ServiceProvider;

class {$studlyName}ServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // View'ları yükle
        \$this->loadViewsFrom(base_path('plugins/{$pluginName}/src/Views'), '{$pluginName}');
    }
}";
    }

    private function getServiceProviderTemplate($studlyName, $pluginName)
    {
        return "<?php

namespace App\\Plugins\\{$studlyName}\\Providers;

use Illuminate\\Support\\ServiceProvider;
use Illuminate\\Support\\Facades\\Route;

class {$studlyName}ServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Route'ları yükle
        Route::middleware('web')
             ->group(base_path('plugins/{$pluginName}/routes/web.php'));

        // View'ları yükle
        \$this->loadViewsFrom(base_path('plugins/{$pluginName}/src/Views'), '{$pluginName}');
    }
}";
    }

    private function getRouteTemplate($studlyName, $pluginName)
    {
        return "<?php

use App\\Plugins\\{$studlyName}\\Controllers\\{$studlyName}Controller;
use Illuminate\\Support\\Facades\\Route;

Route::prefix('secure/{$pluginName}')->group(function () {
    Route::get('/', [{$studlyName}Controller::class, 'index'])->name('{$pluginName}.index');
    Route::get('/settings', [{$studlyName}Controller::class, 'settings'])->name('{$pluginName}.settings');
});";
    }

    private function createViewFiles($pluginPath, $pluginName, $studlyName)
    {
        // Index view
        $indexView = "@extends('backend.layout')

            @section('title', '{$studlyName}')

            @section('content')
            <div class=\"container-fluid\">
                <div class=\"row\">
                    <div class=\"col-12\">
                        <div class=\"card\">
                            <div class=\"card-header\">
                                <h3 class=\"card-title\">{$studlyName}</h3>
                            </div>
                            <div class=\"card-body\">
                                <p>Hoş geldiniz! Bu {$studlyName} plugin'inin ana sayfasıdır.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endsection";
        File::put($pluginPath . '/src/Views/index.blade.php', $indexView);

        // Settings view
        $settingsView = "@extends('backend.layout')

@section('title', '{$studlyName} - Ayarlar')

@section('content')
<div class=\"container-fluid\">
    <div class=\"row\">
        <div class=\"col-12\">
            <div class=\"card\">
                <div class=\"card-header\">
                    <h3 class=\"card-title\">{$studlyName} - Ayarlar</h3>
                </div>
                <div class=\"card-body\">
                    <p>Plugin ayarları burada yapılandırılabilir.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection";
        File::put($pluginPath . '/src/Views/settings.blade.php', $settingsView);
    }

    private function getReadmeTemplate($pluginName)
    {
        return "# {$pluginName} Plugin

Bu plugin, VMG Medya sistemi için geliştirilmiştir.

## Kurulum

1. Plugin'i sisteme yükleyin
2. Plugin'i etkinleştirin
3. Gerekli ayarları yapın

## Kullanım

Plugin kurulduktan sonra admin panelinde menüde görünecektir.

## Geliştirme

Bu plugin'i geliştirmek için:

1. `src/Controllers/` dizininde controller'ları düzenleyin
2. `src/Views/` dizininde view dosyalarını düzenleyin
3. `routes/web.php` dosyasında route'ları tanımlayın

## Destek

Sorularınız için geliştirici ile iletişime geçin.
";
    }

    public function install(Request $request, $pluginName)
    {
        try {
            $plugin = $this->pluginService->installPlugin($pluginName);
            
            return response()->json([
                'success' => true,
                'message' => "Plugin {$pluginName} başarıyla kuruldu!",
                'plugin' => $plugin
            ]);

        } catch (\Exception $e) {
            Log::error("Plugin installation error: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => "Plugin kurulum hatası: " . $e->getMessage()
            ], 500);
        }
    }

    public function uninstall(Request $request, $pluginName)
    {
        try {
            $plugin = $this->pluginService->uninstallPlugin($pluginName);
            
            return response()->json([
                'success' => true,
                'message' => "Plugin {$pluginName} başarıyla kaldırıldı!",
                'plugin' => $plugin
            ]);

        } catch (\Exception $e) {
            Log::error("Plugin uninstallation error: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => "Plugin kaldırma hatası: " . $e->getMessage()
            ], 500);
        }
    }

    public function enable(Request $request, $pluginName)
    {
        try {
            $plugin = $this->pluginService->enablePlugin($pluginName);
            
            return response()->json([
                'success' => true,
                'message' => "Plugin {$pluginName} etkinleştirildi!",
                'plugin' => $plugin
            ]);

        } catch (\Exception $e) {
            Log::error("Plugin enable error: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => "Plugin etkinleştirme hatası: " . $e->getMessage()
            ], 500);
        }
    }

    public function disable(Request $request, $pluginName)
    {
        try {
            $plugin = $this->pluginService->disablePlugin($pluginName);
            
            return response()->json([
                'success' => true,
                'message' => "Plugin {$pluginName} devre dışı bırakıldı!",
                'plugin' => $plugin
            ]);

        } catch (\Exception $e) {
            Log::error("Plugin disable error: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => "Plugin devre dışı bırakma hatası: " . $e->getMessage()
            ], 500);
        }
    }

    public function settings($pluginName)
    {
        $plugin = $this->pluginService->getInstalledPlugins()->where('name', $pluginName)->first();
        
        if (!$plugin) {
            return redirect()->route('admin.plugins.index')->with('error', 'Plugin bulunamadı!');
        }

        return view('admin.plugins.settings', compact('plugin'));
    }

    public function updateSettings(Request $request, $pluginName)
    {
        $plugin = $this->pluginService->getInstalledPlugins()->where('name', $pluginName)->first();
        
        if (!$plugin) {
            return redirect()->route('admin.plugins.index')->with('error', 'Plugin bulunamadı!');
        }

        try {
            $settings = $request->except(['_token', '_method']);
            $plugin->update(['settings' => $settings]);
            
            return redirect()->route('admin.plugins.settings', $pluginName)
                           ->with('success', 'Plugin ayarları güncellendi!');

        } catch (\Exception $e) {
            Log::error("Plugin settings update error: " . $e->getMessage());
            
            return redirect()->route('admin.plugins.settings', $pluginName)
                           ->with('error', 'Ayarlar güncellenirken hata oluştu!');
        }
    }
} 