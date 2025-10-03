<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PluginEnable extends Command
{
    protected $signature = 'plugin:enable {name}';
    protected $description = 'Enable a plugin';

    public function handle()
    {
        $pluginName = $this->argument('name');

        $this->info("Enabling plugin: {$pluginName}");

        // Plugin'in kurulu olup olmadığını kontrol et
        $plugin = DB::table('plugins')->where('name', $pluginName)->first();
        if (!$plugin) {
            $this->error("Plugin {$pluginName} is not installed");
            return 1;
        }

        if ($plugin->status === 'active') {
            $this->info("Plugin {$pluginName} is already enabled");
            return 0;
        }

        try {
            DB::table('plugins')->where('name', $pluginName)->update(['status' => 'active']);
            $this->info("Plugin {$pluginName} enabled successfully!");
            return 0;

        } catch (\Exception $e) {
            $this->error("Error enabling plugin: " . $e->getMessage());
            return 1;
        }
    }
} 