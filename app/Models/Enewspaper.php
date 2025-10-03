<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Enewspaper extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = "enewspaper";
    protected $guarded= [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['title','detail']);
    }



    public function getImages()
    {
        return $this->hasMany(EnewspaperImages::class, 'gallery_id', 'id')->where('model_path', Enewspaper::class)->orderBy('sortby','asc');
    }

}
