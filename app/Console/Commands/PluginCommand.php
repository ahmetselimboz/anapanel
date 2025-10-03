<?php

namespace App\Console\Commands;

use App\Models\Plugin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Table;

class PluginCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:manage 
                            {action : The action to perform (list, install, uninstall, activate, deactivate, info)}
                            {plugin? : The plugin name or ID}
                            {--all : Perform action on all plugins}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage plugins from command line';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $pluginName = $this->argument('plugin');
        $all = $this->option('all');

        switch ($action) {
            case 'list':
                $this->listPlugins();
                break;
            case 'install':
                $this->installPlugin($pluginName, $all);
                break;
            case 'uninstall':
                $this->uninstallPlugin($pluginName, $all);
                break;
            case 'activate':
                $this->activatePlugin($pluginName, $all);
                break;
            case 'deactivate':
                $this->deactivatePlugin($pluginName, $all);
                break;
            case 'info':
                $this->showPluginInfo($pluginName);
                break;
            default:
                $this->error("Unknown action: {$action}");
                $this->showHelp();
                return 1;
        }

        return 0;
    }

    /**
     * List all plugins
     */
    private function listPlugins()
    {
        $plugins = Plugin::orderBy('name')->get();
        
        $headers = ['ID', 'Name', 'Version', 'Status', 'Rating', 'Downloads'];
        $rows = [];

        foreach ($plugins as $plugin) {
            $rows[] = [
                $plugin->id,
                $plugin->name,
                $plugin->getFormattedVersion(),
                $plugin->getStatusText(),
                $plugin->getAverageRating() . '/5',
                $plugin->download_count
            ];
        }

        $this->table($headers, $rows);
        
        $this->info("Total plugins: " . $plugins->count());
        $this->info("Active plugins: " . $plugins->where('is_active', true)->count());
        $this->info("Installed plugins: " . $plugins->where('is_installed', true)->count());
    }

    /**
     * Install a plugin
     */
    private function installPlugin($pluginName, $all)
    {
        if ($all) {
            $plugins = Plugin::where('is_installed', false)->get();
            $this->info("Installing all available plugins...");
        } else {
            $plugin = $this->findPlugin($pluginName);
            if (!$plugin) return;
            $plugins = collect([$plugin]);
        }

        $bar = $this->output->createProgressBar($plugins->count());
        $bar->start();

        foreach ($plugins as $plugin) {
            if ($plugin->install()) {
                $this->line(" ✓ {$plugin->name} installed successfully");
            } else {
                $this->line(" ✗ Failed to install {$plugin->name}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Installation completed!");
    }

    /**
     * Uninstall a plugin
     */
    private function uninstallPlugin($pluginName, $all)
    {
        if ($all) {
            $plugins = Plugin::where('is_installed', true)->get();
            $this->info("Uninstalling all installed plugins...");
        } else {
            $plugin = $this->findPlugin($pluginName);
            if (!$plugin) return;
            $plugins = collect([$plugin]);
        }

        $bar = $this->output->createProgressBar($plugins->count());
        $bar->start();

        foreach ($plugins as $plugin) {
            if ($plugin->uninstall()) {
                $this->line(" ✓ {$plugin->name} uninstalled successfully");
            } else {
                $this->line(" ✗ Failed to uninstall {$plugin->name}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Uninstallation completed!");
    }

    /**
     * Activate a plugin
     */
    private function activatePlugin($pluginName, $all)
    {
        if ($all) {
            $plugins = Plugin::where('is_installed', true)->where('is_active', false)->get();
            $this->info("Activating all installed plugins...");
        } else {
            $plugin = $this->findPlugin($pluginName);
            if (!$plugin) return;
            $plugins = collect([$plugin]);
        }

        $bar = $this->output->createProgressBar($plugins->count());
        $bar->start();

        foreach ($plugins as $plugin) {
            if ($plugin->activate()) {
                $this->line(" ✓ {$plugin->name} activated successfully");
            } else {
                $this->line(" ✗ Failed to activate {$plugin->name}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Activation completed!");
    }

    /**
     * Deactivate a plugin
     */
    private function deactivatePlugin($pluginName, $all)
    {
        if ($all) {
            $plugins = Plugin::where('is_active', true)->get();
            $this->info("Deactivating all active plugins...");
        } else {
            $plugin = $this->findPlugin($pluginName);
            if (!$plugin) return;
            $plugins = collect([$plugin]);
        }

        $bar = $this->output->createProgressBar($plugins->count());
        $bar->start();

        foreach ($plugins as $plugin) {
            if ($plugin->deactivate()) {
                $this->line(" ✓ {$plugin->name} deactivated successfully");
            } else {
                $this->line(" ✗ Failed to deactivate {$plugin->name}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Deactivation completed!");
    }

    /**
     * Show detailed plugin information
     */
    private function showPluginInfo($pluginName)
    {
        $plugin = $this->findPlugin($pluginName);
        if (!$plugin) return;

        $this->info("Plugin Information:");
        $this->line("Name: {$plugin->name}");
        $this->line("Version: {$plugin->getFormattedVersion()}");
        $this->line("Author: {$plugin->author}");
        $this->line("Description: {$plugin->description}");
        $this->line("Status: {$plugin->getStatusText()}");
        $this->line("Rating: {$plugin->getAverageRating()}/5 ({$plugin->rating_count} reviews)");
        $this->line("Downloads: {$plugin->download_count}");
        $this->line("License: {$plugin->license}");
        $this->line("Requirements: {$plugin->requirements}");
        
        if ($plugin->installed_at) {
            $this->line("Installed: " . $plugin->installed_at->format('Y-m-d H:i:s'));
        }
        
        if ($plugin->activated_at) {
            $this->line("Activated: " . $plugin->activated_at->format('Y-m-d H:i:s'));
        }

        if ($plugin->settings) {
            $this->line("Settings: " . json_encode($plugin->settings, JSON_PRETTY_PRINT));
        }

        if ($plugin->dependencies) {
            $this->line("Dependencies: " . json_encode($plugin->dependencies));
        }
    }

    /**
     * Find a plugin by name or ID
     */
    private function findPlugin($pluginName)
    {
        if (is_numeric($pluginName)) {
            $plugin = Plugin::find($pluginName);
        } else {
            $plugin = Plugin::where('name', 'like', "%{$pluginName}%")
                           ->orWhere('slug', 'like', "%{$pluginName}%")
                           ->first();
        }

        if (!$plugin) {
            $this->error("Plugin not found: {$pluginName}");
            return null;
        }

        return $plugin;
    }

    /**
     * Show help information
     */
    private function showHelp()
    {
        $this->line("Available commands:");
        $this->line("  plugin:manage list                    - List all plugins");
        $this->line("  plugin:manage install <plugin>        - Install a plugin");
        $this->line("  plugin:manage install --all           - Install all available plugins");
        $this->line("  plugin:manage uninstall <plugin>      - Uninstall a plugin");
        $this->line("  plugin:manage uninstall --all         - Uninstall all plugins");
        $this->line("  plugin:manage activate <plugin>       - Activate a plugin");
        $this->line("  plugin:manage activate --all          - Activate all installed plugins");
        $this->line("  plugin:manage deactivate <plugin>     - Deactivate a plugin");
        $this->line("  plugin:manage deactivate --all        - Deactivate all active plugins");
        $this->line("  plugin:manage info <plugin>           - Show plugin information");
    }
}
