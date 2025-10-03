<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = "product";

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['title', 'category_id', 'detail','hit', 'publish']);
    }

    protected $fillable = [ 'title', 'category_id','detail','hit', 'publish' ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function Images()
    {
        return $this->hasMany(ProductImages::class, 'id','product_id');
    }



}























