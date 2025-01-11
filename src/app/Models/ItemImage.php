<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'image_url',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id','id');
    }
}
