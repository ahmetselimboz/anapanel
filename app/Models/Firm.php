<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Firm extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = "firm";

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['title','detail','publish']);
    }

}
