<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PluginDisable extends Command
{
    protected $signature = 'plugin:disable {name}';
    protected $description = 'Disable a plugin';

    public function handle()
    {
        $pluginName = $this->argument('name');

        $this->info("Disabling plugin: {$pluginName}");

        // Plugin'in kurulu olup olmadığını kontrol et
        $plugin = DB::table('plugins')->where('name', $pluginName)->first();
        if (!$plugin) {
            $this->error("Plugin {$pluginName} is not installed");
            return 1;
        }

        if ($plugin->status === 'inactive') {
            $this->info("Plugin {$pluginName} is already disabled");
            return 0;
        }

        try {
            DB::table('plugins')->where('name', $pluginName)->update(['status' => 'inactive']);
            $this->info("Plugin {$pluginName} disabled successfully!");
            return 0;

        } catch (\Exception $e) {
            $this->error("Error disabling plugin: " . $e->getMessage());
            return 1;
        }
    }
} 