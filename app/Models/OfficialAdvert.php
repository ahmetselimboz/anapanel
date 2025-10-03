<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OfficialAdvert extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = "official_advert";
    protected $guarded= [];

    protected $casts = [
        'publish' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'clsfadmagicbox'=> 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['title','detail','publish']);
    }


    public function category() {
        return $this->hasOne(Category::class,'id','category_id');
    }

}
