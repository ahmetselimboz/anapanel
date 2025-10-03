<?php

namespace App\Models;

use App\Models\PostLocation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Artisan;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    // LogsActivity;

    protected $table = "post";
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // 'extra' => 'array',
    ];


    protected $fillable = [
        'title',
        'category_id',
        'hit',
        'publish',
        'slug',
        'keywords',
        'description',
        'meta_title',
        'meta_description',
        'detail',
        'position',
        'redirect_link',
        'show_title_slide',
        'editor_state',
        'hit',
        'created_at',
        'updated_at',
        'publish',
        'images',
        'extra',
        'old_data',
        'author_id'
    ];


    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->author_id = auth()->id();
        });


        static::addGlobalScope('notArchived', function (Builder $builder) {
            if ($builder->getQuery()->from && $builder->getQuery()->columns &&
                $builder->getQuery()->wheres &&
                $builder->getQuery()->columns !== ['*']) {
                if (
                    !$builder->getQuery()->wheres ||
                    !collect($builder->getQuery()->wheres)->contains('column', 'is_archived')
                ) {
                    $builder->where('is_archived', false);
                }
            }

        });


        static::created(function ($model) {
            cache()->forget('posts');
            cache()->forget('count_data');
            cache()->forget('post_position_json');
            Artisan::call('sitemap:generate news');
        });
        static::updated(function ($model) {
            cache()->forget('posts');
            cache()->forget('count_data');
            cache()->forget('post_position_json');
            Artisan::call('sitemap:generate news');
        });
        static::deleted(function ($model) {
            cache()->forget('posts');
            cache()->forget('count_data');
            cache()->forget('post_position_json');
            Artisan::call('sitemap:generate news');
        });
    }



    //create scope for archived
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }


    //with archive scope for all post
    public function scopeWithArchived($query)
    {
        return $query->where('is_archived', false)->orWhere('is_archived', true);
    }



    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()->logOnly(['title', 'category_id', 'hit', 'publish']);
    // }


    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id')->select('id','name','avatar','status');
    }

    public function postLocation()
    {
        return $this->belongsToMany(Post::class, 'post_location', 'post_id','location_id', 'id');
    }

    public function locations ()
    {
        return $this->hasMany(PostLocation::class, 'post_id', 'id');
    }

        public function incrementHit($amount = 1)
    {
        $originalTimestamps = $this->timestamps;
        $this->timestamps = false;
        $this->increment('hit', $amount);
        $this->timestamps = $originalTimestamps;
        return $this;
    }

      public function updateHitWithoutTimestamp($value)
    {
        return self::withoutTimestamps(function() use ($value) {
            $this->hit = $value++;
            return $this->save();
        });
    }

}
