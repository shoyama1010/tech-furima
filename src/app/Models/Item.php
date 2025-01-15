<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['is_sold','name','description','price',
        'image_url','user_id','category_id','condition',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        // return $this->belongsTo(Category::class);
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class); //１対多
    }
    

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // 購入済みチェックメソッド
    public function isSold()
    {
        // return $this->orders()->exists();
        return $this->is_sold === 1; // is_sold カラムが 1 の場合、購入済み
    }
}
