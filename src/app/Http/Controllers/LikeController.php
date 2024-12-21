<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class LikeController extends Controller
{
    public function toggle(Request $request, Item $item)
    {
        $user = auth()->user();
        
        $liked = $item->likes()->where('user_id', $user->id)->exists();

        if ($liked) {
            $item->likes()->where('user_id', $user->id)->delete();
            $status = 'unliked';
        } else {
            $item->likes()->create(['user_id' => $user->id]);
            $status = 'liked';
        }

        return response()->json([
            'status' => $status,
            'like_count' => $item->likes()->count(),
        ]);
    }
}
