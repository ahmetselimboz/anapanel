<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PluginInstall extends Command
{
    protected $signature = 'plugin:install {name} {--force}';
    protected $description = 'Install a custom plugin';

    public function handle()
    {
        $pluginName = $this->argument('name');
        $force = $this->option('force');

        $this->info("Installing plugin: {$pluginName}");

        // Plugin dizinini kontrol et
        $pluginPath = base_path("plugins/{$pluginName}");
        if (!File::exists($pluginPath)) {
            $this->error("Plugin directory not found: {$pluginPath}");
            return 1;
        }

        // Plugin config dosyasını kontrol et
        $configFile = "{$pluginPath}/plugin.json";
        if (!File::exists($configFile)) {
            $this->error("Plugin config file not found: {$configFile}");
            return 1;
        }

        $config = json_decode(File::get($configFile), true);
        if (!$config) {
            $this->error("Invalid plugin config file");
            return 1;
        }

        try {
            // Plugin'i veritabanına kaydet
            $this->registerPlugin($config, $pluginName);

            // Migration'ları çalıştır
            $this->runMigrations($pluginPath);

            // Service provider'ı kaydet
            $this->registerServiceProvider($config, $pluginName);

            // Route'ları kaydet
            $this->registerRoutes($pluginPath, $pluginName);

            // Asset'leri kopyala
            $this->copyAssets($pluginPath, $pluginName);

            $this->info("Plugin {$pluginName} installed successfully!");
            return 0;

        } catch (\Exception $e) {
            $this->error("Error installing plugin: " . $e->getMessage());
            return 1;
        }
    }

    private function registerPlugin($config, $pluginName)
    {
        if (!Schema::hasTable('plugins')) {
            $this->call('migrate', ['--path' => 'database/migrations/create_plugins_table.php']);
        }

        DB::table('plugins')->updateOrInsert(
            ['name' => $pluginName],
            [
                'name' => $pluginName,
                'title' => $config['title'] ?? $pluginName,
                'description' => $config['description'] ?? '',
                'version' => $config['version'] ?? '1.0.0',
                'author' => $config['author'] ?? '',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function runMigrations($pluginPath)
    {
        $migrationsPath = "{$pluginPath}/database/migrations";
        if (File::exists($migrationsPath)) {
            $this->info("Running plugin migrations...");
            $this->call('migrate', ['--path' => $migrationsPath]);
        }
    }

    private function registerServiceProvider($config, $pluginName)
    {
        if (isset($config['service_provider'])) {
            $providerClass = $config['service_provider'];
            $appConfig = config_path('app.php');
            $content = File::get($appConfig);

            // Service provider'ı zaten kayıtlı mı kontrol et
            if (strpos($content, $providerClass) === false) {
                $content = str_replace(
                    "App\Providers\CustomServiceProvider::class,",
                    "App\Providers\CustomServiceProvider::class,\n        {$providerClass},",
                    $content
                );
                File::put($appConfig, $content);
                $this->info("Service provider registered: {$providerClass}");
            }
        }
    }

    private function registerRoutes($pluginPath, $pluginName)
    {
        $routesFile = "{$pluginPath}/routes/web.php";
        if (File::exists($routesFile)) {
            $webRoutes = File::get(base_path('routes/web.php'));
            $routeInclude = "\n// Plugin: {$pluginName}\nrequire __DIR__ . '/../../plugins/{$pluginName}/routes/web.php';\n";
            
            if (strpos($webRoutes, $routeInclude) === false) {
                File::append(base_path('routes/web.php'), $routeInclude);
                $this->info("Routes registered for plugin: {$pluginName}");
            }
        }
    }

    private function copyAssets($pluginPath, $pluginName)
    {
        $assetsPath = "{$pluginPath}/public";
        if (File::exists($assetsPath)) {
            $targetPath = public_path("plugins/{$pluginName}");
            if (!File::exists($targetPath)) {
                File::makeDirectory($targetPath, 0755, true);
            }
            File::copyDirectory($assetsPath, $targetPath);
            $this->info("Assets copied for plugin: {$pluginName}");
        }
    }
} 