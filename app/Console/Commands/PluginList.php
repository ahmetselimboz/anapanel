<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class PluginList extends Command
{
    protected $signature = 'plugin:list';
    protected $description = 'List all available and installed plugins';

    public function handle()
    {
        $this->info('Plugin Management System');
        $this->line('======================');

        // Kurulu plugin'leri listele
        $this->info("\n📦 Installed Plugins:");
        $this->line('-------------------');
        
        $installedPlugins = DB::table('plugins')->get();
        
        if ($installedPlugins->isEmpty()) {
            $this->line('No plugins installed.');
        } else {
            $headers = ['Name', 'Title', 'Version', 'Status', 'Author'];
            $rows = [];
            
            foreach ($installedPlugins as $plugin) {
                $status = $plugin->status === 'active' ? '✅ Active' : '❌ Inactive';
                $rows[] = [
                    $plugin->name,
                    $plugin->title,
                    $plugin->version,
                    $status,
                    $plugin->author
                ];
            }
            
            $this->table($headers, $rows);
        }

        // Mevcut plugin'leri listele
        $this->info("\n📁 Available Plugins:");
        $this->line('-------------------');
        
        $pluginsPath = base_path('plugins');
        if (!File::exists($pluginsPath)) {
            $this->line('No plugins directory found.');
            return;
        }

        $availablePlugins = File::directories($pluginsPath);
        
        if (empty($availablePlugins)) {
            $this->line('No plugins found in plugins directory.');
        } else {
            $headers = ['Name', 'Title', 'Version', 'Author', 'Installed'];
            $rows = [];
            
            foreach ($availablePlugins as $pluginPath) {
                $pluginName = basename($pluginPath);
                $configFile = "{$pluginPath}/plugin.json";
                
                if (File::exists($configFile)) {
                    $config = json_decode(File::get($configFile), true);
                    $installed = DB::table('plugins')->where('name', $pluginName)->exists();
                    $installedStatus = $installed ? '✅ Yes' : '❌ No';
                    
                    $rows[] = [
                        $pluginName,
                        $config['title'] ?? 'N/A',
                        $config['version'] ?? 'N/A',
                        $config['author'] ?? 'N/A',
                        $installedStatus
                    ];
                }
            }
            
            $this->table($headers, $rows);
        }

        $this->info("\n💡 Commands:");
        $this->line('plugin:install <name> - Install a plugin');
        $this->line('plugin:uninstall <name> - Uninstall a plugin');
        $this->line('plugin:enable <name> - Enable a plugin');
        $this->line('plugin:disable <name> - Disable a plugin');
    }
} 