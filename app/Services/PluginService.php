<?php

namespace App\Services;

use App\Models\Plugin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class PluginService
{
    public function getInstalledPlugins()
    {
        return Plugin::all();
    }

    public function getActivePlugins()
    {
        return Plugin::active()->get();
    }

    public function getActivePluginsWithMenu()
    {
        $activePlugins = Plugin::active()->get();
        $pluginsWithMenu = [];

        foreach ($activePlugins as $plugin) {
            $config = $plugin->getConfig();
            if ($config && isset($config['menu'])) {
                // Route dosyası var mı kontrol et
                $routeFile = base_path("plugins/{$plugin->name}/routes/web.php");
                if (File::exists($routeFile)) {
                    $pluginsWithMenu[] = [
                        'plugin' => $plugin,
                        'menu' => $config['menu']
                    ];
                }
            }
        }

        return $pluginsWithMenu;
    }

    public function getPluginMenuItems()
    {
        $activePlugins = $this->getActivePluginsWithMenu();
        $menuItems = [];

        foreach ($activePlugins as $pluginData) {
            $plugin = $pluginData['plugin'];
            $menu = $pluginData['menu'];
            
            // Route dosyası var mı kontrol et
            $routeFile = base_path("plugins/{$plugin->name}/routes/web.php");
            if (!File::exists($routeFile)) {
                // Route dosyası yoksa bu plugin'i menüye ekleme
                continue;
            }

            // Plugin ana menü öğesi
            $pluginMenuItem = [
                'type' => 'plugin',
                'plugin' => $plugin,
                'title' => $plugin->title,
                'icon' => $menu['main']['icon'] ?? 'fa fa-cube',
                'route' => $menu['main']['route'] ?? '#',
                'submenu' => []
            ];

            // Alt menü öğeleri
            if (isset($menu['submenu'])) {
                foreach ($menu['submenu'] as $submenu) {
                    $pluginMenuItem['submenu'][] = [
                        'type' => 'submenu',
                        'title' => $submenu['title'],
                        'icon' => $submenu['icon'] ?? 'icon-Commit',
                        'route' => $submenu['route'] ?? '#'
                    ];
                }
            }

            $menuItems[] = $pluginMenuItem;
        }

        return $menuItems;
    }

    public function getAvailablePlugins()
    {

        $pluginsPath = base_path('plugins');
        $plugins = [];

        if (!File::exists($pluginsPath)) {
            return $plugins;
        }

        $directories = File::directories($pluginsPath);
        
        foreach ($directories as $directory) {
            $pluginName = basename($directory);
            $configFile = "{$directory}/plugin.json";
            
            if (File::exists($configFile)) {
                $config = json_decode(File::get($configFile), true);
                $installed = Plugin::where('name', $pluginName)->first();
                
                $plugins[] = [
                    'name' => $pluginName,
                    'title' => $config['title'] ?? $pluginName,
                    'description' => $config['description'] ?? '',
                    'version' => $config['version'] ?? '1.0.0',
                    'author' => $config['author'] ?? '',
                    'installed' => $installed,
                    'active' => $installed ? $installed->isActive() : false,
                    'path' => $directory
                ];
            }
        }

        return $plugins;
    }

    public function installPlugin($pluginName)
    {
        $pluginPath = base_path("plugins/{$pluginName}");
        $configFile = "{$pluginPath}/plugin.json";

        if (!File::exists($pluginPath)) {
            throw new \Exception("Plugin directory not found: {$pluginPath}");
        }

        if (!File::exists($configFile)) {
            throw new \Exception("Plugin config file not found: {$configFile}");
        }

        $config = json_decode(File::get($configFile), true);
        if (!$config) {
            throw new \Exception("Invalid plugin config file");
        }

        // Plugin'i veritabanına kaydet
        $plugin = Plugin::updateOrCreate(
            ['name' => $pluginName],
            [
                'title' => $config['title'] ?? $pluginName,
                'description' => $config['description'] ?? '',
                'version' => $config['version'] ?? '1.0.0',
                'author' => $config['author'] ?? '',
                'status' => 'active'
            ]
        );

        // Migration'ları çalıştır
        $this->runPluginMigrations($pluginPath);

        // Service provider'ı kaydet
        if (isset($config['service_provider'])) {
            $this->registerServiceProvider($config['service_provider']);
        }

        // Route'ları kaydet
        $this->registerPluginRoutes($pluginPath, $pluginName);

        // Asset'leri kopyala
        $this->copyPluginAssets($pluginPath, $pluginName);

        return $plugin;
    }

    public function uninstallPlugin($pluginName)
    {
        $plugin = Plugin::where('name', $pluginName)->first();
        if (!$plugin) {
            throw new \Exception("Plugin {$pluginName} is not installed");
        }

        // Plugin'i devre dışı bırak
        $plugin->update(['status' => 'inactive']);

        // Service provider'ı kaldır
        $pluginPath = base_path("plugins/{$pluginName}");
        $configFile = "{$pluginPath}/plugin.json";
        
        if (File::exists($configFile)) {
            $config = json_decode(File::get($configFile), true);
            if (isset($config['service_provider'])) {
                $this->unregisterServiceProvider($config['service_provider']);
            }
        }

        // Route'ları kaldır
        $this->unregisterPluginRoutes($pluginName);

        // Asset'leri kaldır
        $this->removePluginAssets($pluginName);

        return $plugin;
    }

    public function enablePlugin($pluginName)
    {
        $plugin = Plugin::where('name', $pluginName)->first();
        if (!$plugin) {
            throw new \Exception("Plugin {$pluginName} is not installed");
        }

        $plugin->update(['status' => 'active']);
        return $plugin;
    }

    public function disablePlugin($pluginName)
    {
        $plugin = Plugin::where('name', $pluginName)->first();
        if (!$plugin) {
            throw new \Exception("Plugin {$pluginName} is not installed");
        }

        $plugin->update(['status' => 'inactive']);
        return $plugin;
    }

    private function runPluginMigrations($pluginPath)
    {
        $migrationsPath = "{$pluginPath}/database/migrations";
        if (File::exists($migrationsPath)) {
            Artisan::call('migrate', ['--path' => $migrationsPath]);
        }
    }

    private function registerServiceProvider($providerClass)
    {
        $appConfig = config_path('app.php');
        $content = File::get($appConfig);

        if (strpos($content, $providerClass) === false) {
            $content = str_replace(
                "App\Providers\CustomServiceProvider::class,",
                "App\Providers\CustomServiceProvider::class,\n        {$providerClass},",
                $content
            );
            File::put($appConfig, $content);
        }
    }

    private function unregisterServiceProvider($providerClass)
    {
        $appConfig = config_path('app.php');
        $content = File::get($appConfig);
        $content = str_replace("        {$providerClass},\n", '', $content);
        File::put($appConfig, $content);
    }

    private function registerPluginRoutes($pluginPath, $pluginName)
    {
        $routesFile = "{$pluginPath}/routes/web.php";
        if (File::exists($routesFile)) {
            $webRoutes = File::get(base_path('routes/web.php'));
            $routeInclude = "\n// Plugin: {$pluginName}\nrequire __DIR__ . '/../../plugins/{$pluginName}/routes/web.php';\n";
            
            if (strpos($webRoutes, $routeInclude) === false) {
                File::append(base_path('routes/web.php'), $routeInclude);
            }
        }
    }

    private function unregisterPluginRoutes($pluginName)
    {
        $webRoutes = File::get(base_path('routes/web.php'));
        $routeInclude = "// Plugin: {$pluginName}\nrequire __DIR__ . '/../../plugins/{$pluginName}/routes/web.php';\n";
        $content = str_replace($routeInclude, '', $webRoutes);
        File::put(base_path('routes/web.php'), $content);
    }

    private function copyPluginAssets($pluginPath, $pluginName)
    {
        $assetsPath = "{$pluginPath}/public";
        if (File::exists($assetsPath)) {
            $targetPath = public_path("plugins/{$pluginName}");
            if (!File::exists($targetPath)) {
                File::makeDirectory($targetPath, 0755, true);
            }
            File::copyDirectory($assetsPath, $targetPath);
        }
    }

    private function removePluginAssets($pluginName)
    {
        $targetPath = public_path("plugins/{$pluginName}");
        if (File::exists($targetPath)) {
            File::deleteDirectory($targetPath);
        }
    }

    public function updateComposerAutoload()
    {
        $composerPath = base_path('composer.json');
        $composer = json_decode(File::get($composerPath), true);
        
        // Mevcut plugin autoload'larını al
        $existingAutoloads = $composer['autoload']['psr-4'] ?? [];
        
        // Plugin dizinlerini tara
        $pluginsPath = base_path('plugins');
        if (!File::exists($pluginsPath)) {
            return;
        }
        
        $directories = File::directories($pluginsPath);
        $newAutoloads = [];
        
        foreach ($directories as $directory) {
            $pluginName = basename($directory);
            $studlyName = \Illuminate\Support\Str::studly($pluginName);
            $autoloadKey = "App\\Plugins\\{$studlyName}\\";
            $autoloadPath = "plugins/{$pluginName}/src/";
            
            // Sadece src dizini varsa ekle
            if (File::exists($directory . '/src')) {
                $newAutoloads[$autoloadKey] = $autoloadPath;
            }
        }
        
        // Yeni autoload'ları mevcut olanlarla birleştir
        $composer['autoload']['psr-4'] = array_merge($existingAutoloads, $newAutoloads);
        
        // composer.json'ı güncelle
        File::put($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        
        // // Autoload'u yeniden oluştur
        // exec('composer dump-autoload', $output, $returnCode);
        
        return 0;
    }
} 