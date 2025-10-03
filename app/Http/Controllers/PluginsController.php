<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;


class PluginsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
     
    public function index()
    {
        $filter = request('filter', 'all');
        $search = request('search');

        $plugins = Plugin::query();

        // Apply filters
        switch ($filter) {
            case 'active':
                $plugins->active();
                break;
            case 'installed':
                $plugins->installed();
                break;
            case 'available':
                $plugins->available();
                break;
            case 'inactive':
                $plugins->where('is_installed', true)->where('is_active', false);
                break;
        }

        // Apply search
        if (!empty($search)) {
            $plugins->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $plugins = $plugins->orderBy('created_at', 'desc')->paginate(20);
        $plugins->appends(request()->query());

        $stats = [
            'total' => Plugin::count(),
            'active' => Plugin::active()->count(),
            'installed' => Plugin::installed()->count(),
            'available' => Plugin::available()->count(),
        ];

        return view('backend.plugins.index', compact('plugins', 'stats', 'filter', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.plugins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'version' => 'required|string|max:50',
            'author' => 'nullable|string|max:255',
            'author_url' => 'nullable|url',
            'plugin_url' => 'nullable|url',
            'documentation_url' => 'nullable|url',
            'requirements' => 'nullable|string',
            'minimum_php_version' => 'nullable|string|max:20',
            'minimum_laravel_version' => 'nullable|string|max:20',
            'changelog' => 'nullable|string',
            'license' => 'nullable|string|max:100',
            'license_url' => 'nullable|url',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'dependencies' => 'nullable|array',
            'settings' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Handle slug generation
        $data['slug'] = Str::slug($data['name']);

        // Handle icon upload
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('plugins/icons', 'public');
            $data['icon'] = $iconPath;
        }

        // Set default values
        $data['is_active'] = false;
        $data['is_installed'] = false;
        $data['download_count'] = 0;
        $data['rating'] = 0.00;
        $data['rating_count'] = 0;

        $plugin = Plugin::create($data);

        toastr()->success('Plugin başarıyla oluşturuldu.', 'BAŞARILI');
        return redirect()->route('plugin.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $plugin = Plugin::findOrFail($id);
        return view('backend.plugins.show', compact('plugin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $plugin = Plugin::findOrFail($id);
        return view('backend.plugins.edit', compact('plugin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $plugin = Plugin::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'version' => 'required|string|max:50',
            'author' => 'nullable|string|max:255',
            'author_url' => 'nullable|url',
            'plugin_url' => 'nullable|url',
            'documentation_url' => 'nullable|url',
            'requirements' => 'nullable|string',
            'minimum_php_version' => 'nullable|string|max:20',
            'minimum_laravel_version' => 'nullable|string|max:20',
            'changelog' => 'nullable|string',
            'license' => 'nullable|string|max:100',
            'license_url' => 'nullable|url',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'dependencies' => 'nullable|array',
            'settings' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Handle slug generation if name changed
        if ($data['name'] !== $plugin->name) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle icon upload
        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($plugin->icon && Storage::disk('public')->exists($plugin->icon)) {
                Storage::disk('public')->delete($plugin->icon);
            }

            $iconPath = $request->file('icon')->store('plugins/icons', 'public');
            $data['icon'] = $iconPath;
        }

        $plugin->update($data);

        toastr()->success('Plugin başarıyla güncellendi.', 'BAŞARILI');
        return redirect()->route('plugin.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $plugin = Plugin::findOrFail($id);

        // Delete icon if exists
        if ($plugin->icon && Storage::disk('public')->exists($plugin->icon)) {
            Storage::disk('public')->delete($plugin->icon);
        }

        $plugin->delete();

        toastr()->success('Plugin başarıyla silindi.', 'BAŞARILI');
        return redirect()->route('plugin.index');
    }

    /**
     * Show trashed plugins
     */
    public function trashed()
    {
        $plugins = Plugin::onlyTrashed()->orderBy('name')->paginate(20);
        return view('backend.plugins.trashed', compact('plugins'));
    }

    /**
     * Restore trashed plugin
     */
    public function restore(string $id)
    {
        $plugin = Plugin::onlyTrashed()->findOrFail($id);
        $plugin->restore();

        toastr()->success('Plugin başarıyla geri yüklendi.', 'BAŞARILI');
        return redirect()->route('plugin.index');
    }

    /**
     * Activate plugin
     */
    public function activate(string $id)
    {
        $plugin = Plugin::findOrFail($id);

        if (!$plugin->is_installed) {
            toastr()->error('Plugin önce yüklenmelidir.', 'HATA');
            return back();
        }

        if ($plugin->activate()) {
            toastr()->success('Plugin başarıyla aktifleştirildi.', 'BAŞARILI');
        } else {
            toastr()->error('Plugin aktifleştirilemedi.', 'HATA');
        }

        return back();
    }

    /**
     * Deactivate plugin
     */
    public function deactivate(string $id)
    {
        $plugin = Plugin::findOrFail($id);

        if ($plugin->deactivate()) {
            toastr()->success('Plugin başarıyla deaktifleştirildi.', 'BAŞARILI');
        } else {
            toastr()->error('Plugin deaktifleştirilemedi.', 'HATA');
        }

        return back();
    }

    /**
     * Install plugin
     */
    public function install(string $id)
    {
        $plugin = Plugin::findOrFail($id);

        if ($plugin->is_installed) {
            toastr()->warning('Plugin zaten yüklü.', 'UYARI');
            return back();
        }

        // Check dependencies
        if ($plugin->hasDependencies()) {
            $dependencies = $plugin->getDependencies();
            $missingDependencies = [];

            foreach ($dependencies as $dependency) {
                $depPlugin = Plugin::where('slug', $dependency)->first();
                if (!$depPlugin || !$depPlugin->is_installed) {
                    $missingDependencies[] = $dependency;
                }
            }

            if (!empty($missingDependencies)) {
                toastr()->error('Eksik bağımlılıklar: ' . implode(', ', $missingDependencies), 'HATA');
                return back();
            }
        }

        if ($plugin->install()) {
            toastr()->success('Plugin başarıyla yüklendi.', 'BAŞARILI');
        } else {
            toastr()->error('Plugin yüklenemedi.', 'HATA');
        }

        return back();
    }

    /**
     * Uninstall plugin
     */
    public function uninstall(string $id)
    {
        $plugin = Plugin::findOrFail($id);

        if (!$plugin->is_installed) {
            toastr()->warning('Plugin zaten yüklü değil.', 'UYARI');
            return back();
        }

        // Check if other plugins depend on this one
        $dependentPlugins = Plugin::whereJsonContains('dependencies', $plugin->slug)->get();
        if ($dependentPlugins->isNotEmpty()) {
            $dependentNames = $dependentPlugins->pluck('name')->implode(', ');
            toastr()->error('Bu plugin diğer pluginler tarafından kullanılıyor: ' . $dependentNames, 'HATA');
            return back();
        }

        if ($plugin->uninstall()) {
            toastr()->success('Plugin başarıyla kaldırıldı.', 'BAŞARILI');
        } else {
            toastr()->error('Plugin kaldırılamadı.', 'HATA');
        }

        return back();
    }

    /**
     * Update plugin settings
     */
    public function updateSettings(Request $request, string $id)
    {
        $plugin = Plugin::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $settings = $request->input('settings', []);

        foreach ($settings as $key => $value) {
            $plugin->setSetting($key, $value);
        }

        toastr()->success('Plugin ayarları güncellendi.', 'BAŞARILI');
        return back();
    }

    /**
     * Get plugin statistics
     */
    public function statistics()
    {
        $stats = [
            'total_plugins' => Plugin::count(),
            'active_plugins' => Plugin::active()->count(),
            'installed_plugins' => Plugin::installed()->count(),
            'available_plugins' => Plugin::available()->count(),
            'recently_updated' => Plugin::where('updated_at', '>=', now()->subDays(7))->count(),
            'top_rated' => Plugin::where('rating', '>', 0)->orderBy('rating', 'desc')->limit(5)->get(),
            'most_downloaded' => Plugin::orderBy('download_count', 'desc')->limit(5)->get(),
        ];

        return view('backend.plugins.statistics', compact('stats'));
    }

    /**
     * Search plugins
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return redirect()->route('plugin.index');
        }

        $plugins = Plugin::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('author', 'like', "%{$query}%")
            ->orderBy('name')
            ->paginate(20);

        return view('backend.plugins.search', compact('plugins', 'query'));
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $selectedIds = $request->input('selected', []);

        if (empty($selectedIds)) {
            toastr()->warning('Lütfen en az bir plugin seçin.', 'UYARI');
            return back();
        }

        $plugins = Plugin::whereIn('id', $selectedIds);

        switch ($action) {
            case 'activate':
                $plugins->update(['is_active' => true, 'activated_at' => now()]);
                toastr()->success('Seçili pluginler aktifleştirildi.', 'BAŞARILI');
                break;

            case 'deactivate':
                $plugins->update(['is_active' => false, 'activated_at' => null]);
                toastr()->success('Seçili pluginler deaktifleştirildi.', 'BAŞARILI');
                break;

            case 'install':
                $plugins->update(['is_installed' => true, 'installed_at' => now()]);
                toastr()->success('Seçili pluginler yüklendi.', 'BAŞARILI');
                break;

            case 'uninstall':
                $plugins->update([
                    'is_installed' => false,
                    'is_active' => false,
                    'installed_at' => null,
                    'activated_at' => null
                ]);
                toastr()->success('Seçili pluginler kaldırıldı.', 'BAŞARILI');
                break;

            case 'delete':
                $plugins->delete();
                toastr()->success('Seçili pluginler silindi.', 'BAŞARILI');
                break;

            default:
                toastr()->error('Geçersiz işlem.', 'HATA');
        }

        return back();
    }


    public function getPlugins()
    {
        $filter = request('filter', 'all');
        $search = request('search');
    
        // Plugin sorgusunu başlat
        $plugins = Plugin::query();
    
        // Arama filtresi uygula
        if (!empty($search)) {
            $plugins->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            });
        }
    
        // Gerekli alanları seç, tarihe göre sırala ve sayfala
        $plugins = $plugins->select(
                'id',
                'name',
                'slug',
                'description',
                'version',
                'author',
                'author_url',
                'plugin_url',
                'documentation_url',
                'created_at'
            )->orderBy('created_at', 'desc')->get();
            
        $plugins->transform(function ($plugin) {
            $plugin->created_at_formatted = Carbon::parse($plugin->created_at)->format('d.m.Y H:i');
            return $plugin;
         });
        
        // $plugins->appends(request()->query());
    
        return response()->json($plugins);
    }

}
