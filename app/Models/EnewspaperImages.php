<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnewspaperImages extends Model
{
    use HasFactory;

    protected $table = "newspaper_gallery_images";
    protected $fillable = [
        'gallery_id',   // gallery_id'yi ekliyoruz
        'images',       // images alanını da ekliyoruz
        'title',        // title alanını da ekliyoruz
        'sortby',       // ve diğer alanları
        'model_path'
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            // if (!empty($image->model_path)) {
            //     $image->model_path = get_class($image);
            // }
        });
    }
}
