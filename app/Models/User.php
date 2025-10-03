<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, LogsActivity;

    protected $dates = ['deleted_at']; // soft delete için
    protected $appends = ['role_name'];  // role_name alanını ekle

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['name', 'email']);
        // Chain fluent methods for configuration options
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'email'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        "google2fa_secret"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->avatar)) {
                $model->avatar = 'backend/assets/icons/avatar.png';
            }

            if ($model->status == 1) {
                // Get authenticated user (adjust based on your auth system)
                $user = auth()->user();

                // If no authenticated user or user's status is not 1, prevent setting status to 1
                if (!$user || $user->status != 1) {
                    $model->status = 2; // or whatever default status you want
                    // Alternatively, you could throw an exception:
                    // throw new \Exception('Only users with status 1 can set status to 1');
                }
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('status') && $model->status == 1) {
                $user = auth()->user();
                if (!$user || $user->status != 1) {
                    // Revert the status change
                    $model->status = $model->getOriginal('status');
                    // Or throw an exception
                }
            }
        });

    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'id', 'author_id')->orderBy('id','desc');
    }


    public function getRoleNameAttribute()
    {
        $role = $this->status; // status alanından rolü al
        return match ($role) {
            1 => 'Yönetici',
            2 => 'Editor',
            3 => 'Yazar',
            default => 'User',
        };
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id', 'id');
    }
    public function latestArticle()
    {
        return $this->hasOne(Article::class, 'author_id')->latestOfMany()->where('created_at', '<=', now());

    }
}































