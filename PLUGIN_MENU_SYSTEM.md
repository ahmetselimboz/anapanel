# Plugin Menü Sistemi

Bu sistem, aktif pluginlerin admin panelinde menü öğeleri olarak görünmesini sağlar.

## 🎯 Özellikler

- Aktif pluginler otomatik olarak admin sidebar'ında görünür
- Her plugin kendi menü öğelerini tanımlayabilir
- Plugin menüleri "Aktif Pluginler" başlığı altında gruplanır
- Menü öğeleri dinamik olarak yüklenir

## 📋 Plugin Konfigürasyonu

Plugin'lerin menü öğelerini tanımlamak için `plugin.json` dosyasına `menu` bölümü eklenmelidir:

```json
{
    "name": "plugin-adi",
    "title": "Plugin Başlığı",
    "description": "Plugin açıklaması",
    "version": "1.0.0",
    "author": "Plugin Yazarı",
    "service_provider": "App\\Plugins\\PluginAdi\\Providers\\PluginAdiServiceProvider",
    "dependencies": [],
    "menu": {
        "main": {
            "title": "Ana Menü Başlığı",
            "icon": "fa fa-icon",
            "route": "plugin.route.name"
        },
        "submenu": [
            {
                "title": "Alt Menü 1",
                "icon": "fa fa-dashboard",
                "route": "plugin.submenu1.route"
            },
            {
                "title": "Alt Menü 2",
                "icon": "fa fa-cog",
                "route": "plugin.submenu2.route"
            }
        ]
    },
    "settings": {
        // ... ayarlar
    }
}
```

## 🔧 Menü Yapısı

### Ana Menü (main)
- `title`: Menü başlığı
- `icon`: FontAwesome ikonu
- `route`: Laravel route adı

### Alt Menü (submenu)
- `title`: Alt menü başlığı
- `icon`: FontAwesome ikonu
- `route`: Laravel route adı

## 📁 Dosya Yapısı

```
app/
├── Services/
│   └── PluginService.php          # Plugin servis sınıfı
├── Providers/
│   └── CustomServiceProvider.php  # View composer
└── Models/
    └── Plugin.php                 # Plugin model

resources/views/backend/layouth/
└── aside.blade.php               # Admin sidebar

plugins/
├── plugin-adi/
│   └── plugin.json               # Plugin konfigürasyonu
```

## 🚀 Kullanım

### 1. Plugin Kurulumu
```bash
php artisan plugin:install plugin-adi
```

### 2. Plugin Etkinleştirme
```bash
php artisan plugin:enable plugin-adi
```

### 3. Menü Görünümü
Plugin etkinleştirildikten sonra, admin panelinde "Aktif Pluginler" menüsü altında plugin menü öğeleri görünecektir.

## 🔍 Test Etme

Plugin menü sistemini test etmek için:

```bash
# Test route'u çağır
curl http://localhost/test-plugin-menu
```

## 📝 Örnek Plugin Konfigürasyonları

### SEO Analyzer Plugin
```json
{
    "menu": {
        "main": {
            "title": "SEO Analyzer",
            "icon": "fa fa-search",
            "route": "seo-analyzer.index"
        },
        "submenu": [
            {
                "title": "SEO Dashboard",
                "icon": "fa fa-dashboard",
                "route": "seo-analyzer.dashboard"
            },
            {
                "title": "SEO Ayarları",
                "icon": "fa fa-cog",
                "route": "seo-analyzer.settings"
            },
            {
                "title": "Bildirimler",
                "icon": "fa fa-bell",
                "route": "seo-analyzer.notifications"
            }
        ]
    }
}
```

### Örnek Plugin
```json
{
    "menu": {
        "main": {
            "title": "Örnek Plugin",
            "icon": "fa fa-cube",
            "route": "ornek-plugin.index"
        },
        "submenu": [
            {
                "title": "Ana Sayfa",
                "icon": "fa fa-home",
                "route": "ornek-plugin.index"
            },
            {
                "title": "Ayarlar",
                "icon": "fa fa-cog",
                "route": "ornek-plugin.settings"
            }
        ]
    }
}
```

## ⚙️ Teknik Detaylar

### PluginService Sınıfı
- `getActivePluginsWithMenu()`: Menü bilgisi olan aktif pluginleri getirir
- `getPluginMenuItems()`: Menü öğelerini döndürür

### View Composer
`CustomServiceProvider` içinde `backend.layouth.aside` view'ına plugin menü verileri eklenir.

### Sidebar Entegrasyonu
Admin sidebar'ında "Aktif Pluginler" menüsü dinamik olarak oluşturulur.

## 🎨 Özelleştirme

### İkon Değiştirme
FontAwesome ikonlarını kullanarak menü ikonlarını değiştirebilirsiniz:
- `fa fa-dashboard`
- `fa fa-cog`
- `fa fa-home`
- `fa fa-bell`
- vb.

### Route Tanımlama
Plugin route'larını `routes/web.php` dosyasında tanımlayın:

```php
Route::prefix('secure/plugin-adi')->group(function () {
    Route::get('/', [PluginController::class, 'index'])->name('plugin-adi.index');
    Route::get('/settings', [PluginController::class, 'settings'])->name('plugin-adi.settings');
});
```

## 🔧 Sorun Giderme

### Menü Görünmüyor
1. Plugin'in aktif olduğundan emin olun
2. `plugin.json` dosyasında `menu` bölümünün doğru tanımlandığını kontrol edin
3. Route'ların doğru tanımlandığını kontrol edin

### Route Hatası
1. Route adlarının doğru olduğundan emin olun
2. Route'ların tanımlandığından emin olun
3. Laravel cache'ini temizleyin: `php artisan route:clear`

## 📞 Destek

Bu sistem ile ilgili sorularınız için:
- Plugin sistemi dokümantasyonunu inceleyin
- Laravel log dosyalarını kontrol edin
- Test ortamında deneyin 