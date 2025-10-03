<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
       protected $table = 'notification';
    
    protected $fillable = ['customer_id', 'title', 'message'];

    public function panel()
    {
        return $this->belongsTo(Panel::class);
    }
}
