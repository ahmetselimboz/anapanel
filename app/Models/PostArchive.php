<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostArchive extends Model
{
    use HasFactory;
    // LogsActivity;

    protected $table = "post_archive";

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()->logOnly(['title', 'category_id', 'hit', 'publish']);
    // }


    protected $fillable = [ 'title', 'category_id','hit', 'publish' ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
