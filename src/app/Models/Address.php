<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'postal_code',
        'address', 
        'is_default',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
