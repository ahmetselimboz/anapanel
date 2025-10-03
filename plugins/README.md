# Haber Sitesi Plugin Sistemi

Bu sistem, haber siteniz için özel eklentiler geliştirmenizi ve yönetmenizi sağlar.

## 📁 Plugin Dizin Yapısı

```
plugins/
├── plugin-adi/
│   ├── plugin.json              # Plugin konfigürasyon dosyası
│   ├── src/                     # Plugin kaynak kodları
│   │   ├── Controllers/         # Controller'lar
│   │   ├── Models/             # Model'ler
│   │   ├── Views/              # View dosyaları
│   │   ├── Services/           # Service sınıfları
│   │   └── Providers/          # Service Provider'lar
│   ├── routes/
│   │   └── web.php             # Plugin route'ları
│   ├── database/
│   │   └── migrations/         # Database migration'ları
│   ├── public/                 # Statik dosyalar (CSS, JS, resimler)
│   └── README.md               # Plugin dokümantasyonu
```

## 📋 Plugin Konfigürasyonu (plugin.json)

```json
{
    "name": "plugin-adi",
    "title": "Plugin Başlığı",
    "description": "Plugin açıklaması",
    "version": "1.0.0",
    "author": "Plugin Yazarı",
    "service_provider": "App\\Plugins\\PluginAdi\\Providers\\PluginAdiServiceProvider",
    "dependencies": [],
    "settings": {
        "title": "Plugin Başlığı",
        "description": "Plugin açıklaması",
        "custom_settings": {
            "api_key": "",
            "api_secret": ""
        }
    }
}
```

## 🚀 Plugin Geliştirme Rehberi

### 1. Plugin Dizini Oluşturma

```bash
mkdir -p plugins/benim-pluginim
cd plugins/benim-pluginim
```

### 2. Konfigürasyon Dosyası

`plugin.json` dosyası oluşturun:

```json
{
    "name": "benim-pluginim",
    "title": "Benim Pluginim",
    "description": "Bu benim ilk pluginim",
    "version": "1.0.0",
    "author": "Adınız",
    "service_provider": "App\\Plugins\\BenimPluginim\\Providers\\BenimPluginimServiceProvider"
}
```

### 3. Service Provider Oluşturma

```php
<?php

namespace App\Plugins\BenimPluginim\Providers;

use Illuminate\Support\ServiceProvider;

class BenimPluginimServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Dependency injection kayıtları
    }

    public function boot()
    {
        // Plugin başlatma işlemleri
        $this->loadViewsFrom(__DIR__ . '/../Views', 'benim-pluginim');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
```

### 4. Controller Oluşturma

```php
<?php

namespace App\Plugins\BenimPluginim\Controllers;

use App\Http\Controllers\Controller;

class BenimPluginimController extends Controller
{
    public function index()
    {
        return view('benim-pluginim::index');
    }
}
```

### 5. Route Tanımlama

`routes/web.php` dosyası oluşturun:

```php
<?php

use App\Plugins\BenimPluginim\Controllers\BenimPluginimController;
use Illuminate\Support\Facades\Route;

Route::prefix('benim-pluginim')->group(function () {
    Route::get('/', [BenimPluginimController::class, 'index'])->name('benim-pluginim.index');
});
```

### 6. View Oluşturma

`Views/index.blade.php` dosyası oluşturun:

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Benim Pluginim</h1>
    <p>Bu benim ilk pluginim!</p>
</div>
@endsection
```

## 🛠️ Komut Satırı Kullanımı

### Plugin Listesi
```bash
php artisan plugin:list
```

### Plugin Kurulumu
```bash
php artisan plugin:install plugin-adi
```

### Plugin Kaldırma
```bash
php artisan plugin:uninstall plugin-adi
```

### Plugin Etkinleştirme
```bash
php artisan plugin:enable plugin-adi
```

### Plugin Devre Dışı Bırakma
```bash
php artisan plugin:disable plugin-adi
```

## 🌐 Admin Panel Kullanımı

1. Admin paneline giriş yapın
2. "Plugin Yönetimi" menüsüne gidin
3. Mevcut plugin'leri görüntüleyin
4. İstediğiniz plugin'i kurun/etkinleştirin/devre dışı bırakın

## 📝 Örnek Plugin: Haber Arşivi

### plugin.json
```json
{
    "name": "haber-arsivi",
    "title": "Haber Arşivi",
    "description": "Eski haberleri arşivleme ve görüntüleme sistemi",
    "version": "1.0.0",
    "author": "Haber Sitesi",
    "service_provider": "App\\Plugins\\HaberArsivi\\Providers\\HaberArsiviServiceProvider"
}
```

### Service Provider
```php
<?php

namespace App\Plugins\HaberArsivi\Providers;

use Illuminate\Support\ServiceProvider;

class HaberArsiviServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('haber-arsivi', function ($app) {
            return new \App\Plugins\HaberArsivi\Services\HaberArsiviService();
        });
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'haber-arsivi');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
```

### Migration
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('haber_arsivi', function (Blueprint $table) {
            $table->id();
            $table->string('baslik');
            $table->text('icerik');
            $table->string('kategori');
            $table->date('yayin_tarihi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('haber_arsivi');
    }
};
```

## 🔧 Gelişmiş Özellikler

### Plugin Ayarları
Plugin'ler kendi ayarlarını tanımlayabilir:

```json
{
    "settings": {
        "api_key": "",
        "max_items": 10,
        "auto_archive": true
    }
}
```

### Bağımlılıklar
Plugin'ler diğer plugin'lere bağımlı olabilir:

```json
{
    "dependencies": ["user-management", "file-upload"]
}
```

### Hook Sistemi
Plugin'ler ana sistemdeki olaylara tepki verebilir:

```php
// Event listener örneği
Event::listen('haber.yayinlandi', function ($haber) {
    // Plugin işlemleri
});
```

## 📚 İyi Uygulamalar

1. **Namespace Kullanımı**: Plugin kodlarınızı uygun namespace'ler altında organize edin
2. **Dokümantasyon**: Her plugin için README dosyası oluşturun
3. **Hata Yönetimi**: Plugin'lerinizde uygun hata yönetimi yapın
4. **Güvenlik**: Kullanıcı girdilerini doğrulayın ve sanitize edin
5. **Performans**: Plugin'lerinizin performansını optimize edin

## 🐛 Sorun Giderme

### Plugin Kurulmuyor
- `plugin.json` dosyasının doğru formatta olduğunu kontrol edin
- Dizin izinlerini kontrol edin
- Laravel log dosyalarını kontrol edin

### Plugin Çalışmıyor
- Service provider'ın doğru kayıtlı olduğunu kontrol edin
- Route'ların doğru tanımlandığını kontrol edin
- Migration'ların çalıştırıldığını kontrol edin

### Admin Panel Erişimi
- Kullanıcının gerekli yetkilere sahip olduğunu kontrol edin
- Route'ların doğru tanımlandığını kontrol edin

## 📞 Destek

Plugin geliştirme ile ilgili sorularınız için:
- Dokümantasyonu kontrol edin
- Laravel log dosyalarını inceleyin
- Test ortamında deneyin 