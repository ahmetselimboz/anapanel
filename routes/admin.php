<?php

use App\Http\Controllers\Admin\PluginController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth', 'rolecheck'])->group(function () {
    
    // Plugin Yönetimi
    Route::prefix('plugins')->group(function () {
        Route::get('/', [PluginController::class, 'index'])->name('admin.plugins.index');
        Route::post('/install/{pluginName}', [PluginController::class, 'install'])->name('admin.plugins.install');
        Route::post('/uninstall/{pluginName}', [PluginController::class, 'uninstall'])->name('admin.plugins.uninstall');
        Route::post('/enable/{pluginName}', [PluginController::class, 'enable'])->name('admin.plugins.enable');
        Route::post('/disable/{pluginName}', [PluginController::class, 'disable'])->name('admin.plugins.disable');
        Route::get('/settings/{pluginName}', [PluginController::class, 'settings'])->name('admin.plugins.settings');
        Route::put('/settings/{pluginName}', [PluginController::class, 'updateSettings'])->name('admin.plugins.update-settings');
    });
}); 