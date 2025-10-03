<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Plugin extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "plugins";
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_updated' => 'datetime',
        'installed_at' => 'datetime',
        'activated_at' => 'datetime',
        'settings' => 'array',
        'dependencies' => 'array',
        'screenshots' => 'array',
        'is_active' => 'boolean',
        'is_installed' => 'boolean',
        'rating' => 'decimal:2',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'version',
        'author',
        'author_url',
        'plugin_url',
        'documentation_url',
        'is_active',
        'is_installed',
        'settings',
        'dependencies',
        'requirements',
        'minimum_php_version',
        'minimum_laravel_version',
        'changelog',
        'license',
        'license_url',
        'icon',
        'screenshots',
        'download_count',
        'rating',
        'rating_count',
        'last_updated',
        'installed_at',
        'activated_at',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = \Illuminate\Support\Str::slug($model->name);
            }
        });

        static::created(function ($model) {
            Cache::forget('plugins');
            Cache::forget('active_plugins');
        });

        static::updated(function ($model) {
            Cache::forget('plugins');
            Cache::forget('active_plugins');
        });

        static::deleted(function ($model) {
            Cache::forget('plugins');
            Cache::forget('active_plugins');
        });
    }

    /**
     * Scope for active plugins
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for installed plugins
     */
    public function scopeInstalled($query)
    {
        return $query->where('is_installed', true);
    }

    /**
     * Scope for available plugins (not installed)
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_installed', false);
    }

    /**
     * Check if plugin is active
     */
    public function isActive(): bool
    {
        return $this->is_active && $this->is_installed;
    }

    /**
     * Check if plugin is installed
     */
    public function isInstalled(): bool
    {
        return $this->is_installed;
    }

    /**
     * Activate the plugin
     */
    public function activate(): bool
    {
        if (!$this->is_installed) {
            return false;
        }

        $this->update([
            'is_active' => true,
            'activated_at' => now(),
        ]);

        return true;
    }

    /**
     * Deactivate the plugin
     */
    public function deactivate(): bool
    {
        $this->update([
            'is_active' => false,
            'activated_at' => null,
        ]);

        return true;
    }

    /**
     * Install the plugin
     */
    public function install(): bool
    {
        $this->update([
            'is_installed' => true,
            'installed_at' => now(),
        ]);

        return true;
    }

    /**
     * Uninstall the plugin
     */
    public function uninstall(): bool
    {
        $this->update([
            'is_installed' => false,
            'is_active' => false,
            'installed_at' => null,
            'activated_at' => null,
        ]);

        return true;
    }

    /**
     * Get plugin settings
     */
    public function getSetting($key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    /**
     * Set plugin setting
     */
    public function setSetting($key, $value): void
    {
        $settings = $this->settings ?? [];
        data_set($settings, $key, $value);
        $this->update(['settings' => $settings]);
    }

    /**
     * Get plugin dependencies
     */
    public function getDependencies(): array
    {
        return $this->dependencies ?? [];
    }

    /**
     * Check if plugin has dependencies
     */
    public function hasDependencies(): bool
    {
        return !empty($this->dependencies);
    }

    /**
     * Get formatted version
     */
    public function getFormattedVersion(): string
    {
        return 'v' . $this->version;
    }

    /**
     * Get average rating
     */
    public function getAverageRating(): float
    {
        return $this->rating_count > 0 ? $this->rating : 0.00;
    }

    /**
     * Get plugin status text
     */
    public function getStatusText(): string
    {
        if (!$this->is_installed) {
            return 'Not Installed';
        }

        if (!$this->is_active) {
            return 'Installed (Inactive)';
        }

        return 'Active';
    }

    /**
     * Get plugin status class for UI
     */
    public function getStatusClass(): string
    {
        if (!$this->is_installed) {
            return 'badge-secondary';
        }

        if (!$this->is_active) {
            return 'badge-warning';
        }

        return 'badge-success';
    }
}
