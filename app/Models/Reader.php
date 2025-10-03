<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reader extends Model
{
    use HasFactory;
    
        protected $table = 'reader';
    
       protected $fillable = [
        'customer_id',
        'email',
        'phone_number',
        // diğer alanlar
    ];

    // Reader -> Panel ilişkisi (her reader bir customer'a ait)
    public function panel()
    {
        return $this->belongsTo(Panel::class);
    }
}
