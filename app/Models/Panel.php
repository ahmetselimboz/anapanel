<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Panel extends Model
{
    use HasFactory;
        
    protected $table = 'customer';

    protected $fillable = ['title', 'domain', 'slug', 'status', 'status_date'];
    
    protected $casts = [
        'status' => 'boolean',
        'status_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Slug otomatik oluşturma
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
                
                // Aynı slug varsa benzersiz hale getir
                $originalSlug = $model->slug;
                $count = 1;
                while (static::where('slug', $model->slug)->exists()) {
                    $model->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });

        // Slug güncelleme
        static::updating(function ($model) {
            if ($model->isDirty('title') && empty($model->slug)) {
                $model->slug = Str::slug($model->title);
                
                // Aynı slug varsa benzersiz hale getir (kendisi hariç)
                $originalSlug = $model->slug;
                $count = 1;
                while (static::where('slug', $model->slug)->where('id', '!=', $model->id)->exists()) {
                    $model->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });
    }
    
    public function notification()
    {
        return $this->hasMany(Notification::class, 'customer_id');
    }
    
    // Customer -> Reader ilişkisi (1 customer, çok reader)
    public function readers()
    {
        return $this->hasMany(Reader::class);
    }

    /**
     * Status tarihine göre otomatik status güncelleme
     */
    public static function updateExpiredStatuses()
    {
        return static::where('status', true)
            ->where('status_date', '<=', Carbon::now())
            ->whereNotNull('status_date')
            ->update(['status' => false]);
    }

    /**
     * Aktif customer'ları getir
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Pasif customer'ları getir
     */
    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }
}
