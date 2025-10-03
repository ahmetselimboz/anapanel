<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = "category";

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['title']);
    }

    protected $fillable = [
        'category_type',
        'parent_category',
        'title',
        'slug',
        'description',
        'color',
        'text_color',
        'keywords',
        'show_category_ads',
        'countnews'];


        public function posts ()
        {
            return $this->hasMany(Post::class, 'category_id', 'id')->orderBy('id','desc');
        }


        protected static function boot(): void
        {
            parent::boot();
            static::created(function ($model) {
                Artisan::call('sitemap:generate category');
            });
            static::updated(function ($model) {

                Artisan::call('sitemap:generate category');
            });
            static::deleted(function ($model) {
                Artisan::call('sitemap:generate category');
            });
        }


}
