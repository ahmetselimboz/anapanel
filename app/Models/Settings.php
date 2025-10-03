<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Settings extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "settings";

    public function getActivitylogOptions(): LogOptions
    {
        //return LogOptions::defaults()->logOnly(['title', 'description', 'magicbox','maintenance']);
        return LogOptions::defaults()->logAll();
    }
    protected static function booted()
    {
        static::created(function ($settings) {
            Cache::forget('settings');
        });

        static::updated(function ($settings) {
            Cache::forget('settings');
        });
    }


    protected $fillable = [ 'title', 'description','magicbox','maintenance'];

    // protected $casts = [
    //     'magicbox' => 'array',
    // ];  
}
