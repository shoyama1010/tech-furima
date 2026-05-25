<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Item extends Model
{
    use HasFactory;

    protected $fillable = ['is_sold','name','description','price',
        'status','user_id','category_id','condition',
        'image_url'
    ];

    protected $appends = [
        'image_full_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'item_id', 'id'); //１対多
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    // 購入済みチェックメソッド
    public function isSold(): bool
    {
        return (bool) $this->is_sold || $this->orders()->exists();
        // return $this->is_sold === 1; // is_sold カラムが 1 の場合、購入済み
    }
    // 画像アップロード統一処理
    public function getImageUrlAttribute($value): string
    {
        // $value = $this->image_url;
        if (blank($value)) {
            return asset('images/no-image.png');
        }
        
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return asset('storage/' . ltrim($value, '/'));
    }

}
