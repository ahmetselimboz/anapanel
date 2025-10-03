<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('robots.txt', function() {
    return response()
        ->view('robots')
        ->header('Content-Type', 'text/plain');
});
Route::get('aktar-te', [App\Http\Controllers\AktarController::class, 'aktarte'])->name('aktarte');
Route::get('bakim-modu', [App\Http\Controllers\HomeController::class, 'maintenance'])->name('frontend.maintenance');
Route::get('/deploy', [App\Http\Controllers\DeploymentController::class, 'runBuild']);

Auth::routes();

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('throttle:5,1');



Route::get('/get-plugins', [App\Http\Controllers\PluginsController::class, 'getPlugins']);
Route::get('/get-notifications/{domain}', [App\Http\Controllers\NotificationController::class, 'getNotifications'])->withoutMiddleware(['auth']);
Route::get('/get-panel-info/{domain}', [App\Http\Controllers\PanelController::class, 'getPanelInfo'])->withoutMiddleware(['auth']);
Route::get('/get-information', [App\Http\Controllers\SecureController::class, 'getInformation'])->withoutMiddleware(['auth']);
Route::post('/reader-info', [App\Http\Controllers\PanelController::class, 'postReaderInfo'])->withoutMiddleware(['auth']);

Route::prefix('/')->group(function () {

    //Route::get('/', [App\Http\Controllers\SecureController::class, 'index'])->name('secure.index');
    
    // Plugin Yönetimi Route'ları
    // require __DIR__ . '/admin.php';
    Route::prefix('plugins')->group(function () {
        
        #  Eklentiler
        Route::get('/', [App\Http\Controllers\PluginsController::class, 'index'])->name('plugin.index');
        Route::get('/create', [App\Http\Controllers\PluginsController::class, 'create'])->name('plugin.create');
        Route::post('/create', [App\Http\Controllers\PluginsController::class, 'store'])->name('plugin.store');
        Route::get('/show/{id}', [App\Http\Controllers\PluginsController::class, 'show'])->name('plugin.show');
        Route::get('/edit/{id}', [App\Http\Controllers\PluginsController::class, 'edit'])->name('plugin.edit');
        Route::post('/edit/{id}', [App\Http\Controllers\PluginsController::class, 'update'])->name('plugin.update');
        Route::get('/destroy/{id}', [App\Http\Controllers\PluginsController::class, 'destroy'])->name('plugin.destroy');
        Route::get('/trashed', [App\Http\Controllers\PluginsController::class, 'trashed'])->name('plugin.trashed');
        Route::get('/restore/{id}', [App\Http\Controllers\PluginsController::class, 'restore'])->name('plugin.restore');
        
        # Plugin Management Routes
        Route::get('/install/{id}', [App\Http\Controllers\PluginsController::class, 'install'])->name('plugin.install');
        Route::get('/uninstall/{id}', [App\Http\Controllers\PluginsController::class, 'uninstall'])->name('plugin.uninstall');
        Route::get('/activate/{id}', [App\Http\Controllers\PluginsController::class, 'activate'])->name('plugin.activate');
        Route::get('/deactivate/{id}', [App\Http\Controllers\PluginsController::class, 'deactivate'])->name('plugin.deactivate');
        Route::post('/settings/{id}', [App\Http\Controllers\PluginsController::class, 'updateSettings'])->name('plugin.settings');
        Route::get('/statistics', [App\Http\Controllers\PluginsController::class, 'statistics'])->name('plugin.statistics');
        Route::get('/search', [App\Http\Controllers\PluginsController::class, 'search'])->name('plugin.search');
        Route::post('/bulk-action', [App\Http\Controllers\PluginsController::class, 'bulkAction'])->name('plugin.bulk-action');
    
    });
    

    
    Route::get('/optimize', [App\Http\Controllers\SecureController::class, 'optimize'])->name('optimize');
    Route::get('burak-migrate', [App\Http\Controllers\SecureController::class, 'migrate'])->name('migrate');
    Route::get('/jsonsystemcreate', [App\Http\Controllers\SecureController::class, 'jsonsystemcreate'])->name('jsonsystemcreate');
    Route::get('/apijsonfileupdate', [App\Http\Controllers\SettingsController::class, 'apiupdate'])->name('apiupdate');
    Route::get('/activitylogs', [App\Http\Controllers\SecureController::class, 'activitylogs'])->name('activitylogs');

    # USERS
    Route::get('users', [App\Http\Controllers\UsersController::class, 'index'])->name('users.index');
    Route::get('users/create', [App\Http\Controllers\UsersController::class, 'create'])->name('users.create');
    Route::post('users/create', [App\Http\Controllers\UsersController::class, 'store'])->name('users.store');
    Route::get('users/edit/{id}', [App\Http\Controllers\UsersController::class, 'edit'])->name('users.edit');
    Route::post('users/edit/{id}', [App\Http\Controllers\UsersController::class, 'update'])->name('users.update');
    Route::get('users/destroy/{id}', [App\Http\Controllers\UsersController::class, 'destroy'])->name('users.destroy');
    Route::get('users/trashed', [App\Http\Controllers\UsersController::class, 'trashed'])->name('users.trashed');
    Route::get('users/restore/{id}', [App\Http\Controllers\UsersController::class, 'restore'])->name('users.restore');
    Route::get('users/delete/{id}', [App\Http\Controllers\UsersController::class, 'deleted'])->name('users.delete');

    Route::post('ckeditorimageupload', [App\Http\Controllers\PostController::class, 'ckeditorimageupload'])->name('ckeditorimageupload');
  

    # SETTİNGS
    Route::get('settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
    Route::post('settings', [App\Http\Controllers\SettingsController::class, 'settingsupdate'])->name('settings.update');




    
    Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notification.index');
    Route::get('notification/create', [App\Http\Controllers\NotificationController::class, 'createPage'])->name('notification.create.page');
    Route::post('notification/create', [App\Http\Controllers\NotificationController::class, 'create'])->name('notification.create');
    Route::get('notification/delete/{id}', [App\Http\Controllers\NotificationController::class, 'delete'])->name('notification.delete');
    Route::get('notification/update/{id}', [App\Http\Controllers\NotificationController::class, 'updatePage'])->name('notification.update.page');
    Route::post('notification/update', [App\Http\Controllers\NotificationController::class, 'update'])->name('notification.update');

    
    
    Route::get('/', [App\Http\Controllers\PanelController::class, 'index'])->name('panels.index');
    Route::get('/panels/create', [App\Http\Controllers\PanelController::class, 'createPage'])->name('panel.create.page');
    Route::post('/panels/create', [App\Http\Controllers\PanelController::class, 'create'])->name('panel.create');
    Route::get('/panels/delete/{slug}', [App\Http\Controllers\PanelController::class, 'delete'])->name( 'panel.delete');
    Route::get('/panels/edit/{slug}', [App\Http\Controllers\PanelController::class, 'edit'])->name('panel.edit');
    Route::post('/panels/update/{slug}', [App\Http\Controllers\PanelController::class, 'update'])->name('panel.update');
    Route::post('/panels/toggle-status', [App\Http\Controllers\PanelController::class, 'toggleStatus'])->name('panel.toggle.status');


    
    Route::get('/information', [App\Http\Controllers\SecureController::class, 'information'])->name('information');
    Route::post('/information', [App\Http\Controllers\SecureController::class, 'informationUpdate'])->name('information.store');
    
    Route::get('/readers/{slug}', [App\Http\Controllers\PanelController::class, 'readerIndex'])->name('readers.index');
    Route::get('/readers/delete/{id}', [App\Http\Controllers\PanelController::class, 'readerDelete'])->name('readers.delete');
    

    



});


