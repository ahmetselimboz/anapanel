<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = "article";

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }


    protected static function boot(): void
    {
        parent::boot();
        static::created(function ($model) {
            Artisan::call('sitemap:generate article');
        });
        static::updated(function ($model) {

            Artisan::call('sitemap:generate article');
        });
        static::deleted(function ($model) {
            Artisan::call('sitemap:generate article');
        });
    }

}
