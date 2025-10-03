<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class PluginUninstall extends Command
{
    protected $signature = 'plugin:uninstall {name} {--force}';
    protected $description = 'Uninstall a custom plugin';

    public function handle()
    {
        $pluginName = $this->argument('name');
        $force = $this->option('force');

        $this->info("Uninstalling plugin: {$pluginName}");

        // Plugin'in kurulu olup olmadığını kontrol et
        $plugin = DB::table('plugins')->where('name', $pluginName)->first();
        if (!$plugin) {
            $this->error("Plugin {$pluginName} is not installed");
            return 1;
        }

        try {
            // Plugin'i devre dışı bırak
            DB::table('plugins')->where('name', $pluginName)->update(['status' => 'inactive']);

            // Service provider'ı kaldır
            $this->unregisterServiceProvider($pluginName);

            // Route'ları kaldır
            $this->unregisterRoutes($pluginName);

            // Asset'leri kaldır
            $this->removeAssets($pluginName);

            $this->info("Plugin {$pluginName} uninstalled successfully!");
            return 0;

        } catch (\Exception $e) {
            $this->error("Error uninstalling plugin: " . $e->getMessage());
            return 1;
        }
    }

    private function unregisterServiceProvider($pluginName)
    {
        $appConfig = config_path('app.php');
        $content = File::get($appConfig);

        // Plugin service provider'ını bul ve kaldır
        $pluginPath = base_path("plugins/{$pluginName}");
        $configFile = "{$pluginPath}/plugin.json";
        
        if (File::exists($configFile)) {
            $config = json_decode(File::get($configFile), true);
            if (isset($config['service_provider'])) {
                $providerClass = $config['service_provider'];
                $content = str_replace("        {$providerClass},\n", '', $content);
                File::put($appConfig, $content);
                $this->info("Service provider unregistered: {$providerClass}");
            }
        }
    }

    private function unregisterRoutes($pluginName)
    {
        $webRoutes = File::get(base_path('routes/web.php'));
        $routeInclude = "// Plugin: {$pluginName}\nrequire __DIR__ . '/../../plugins/{$pluginName}/routes/web.php';\n";
        
        $content = str_replace($routeInclude, '', $webRoutes);
        File::put(base_path('routes/web.php'), $content);
        $this->info("Routes unregistered for plugin: {$pluginName}");
    }

    private function removeAssets($pluginName)
    {
        $targetPath = public_path("plugins/{$pluginName}");
        if (File::exists($targetPath)) {
            File::deleteDirectory($targetPath);
            $this->info("Assets removed for plugin: {$pluginName}");
        }
    }
} 