<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Support\Facades\Log;

class LikeController extends Controller
{
    public function toggle(Request $request, Item $item)
    {
        $user = Auth()->user();
        // ログインしていない場合はエラーを返す
        if (!$user) {
            return response()->json(['error' => 'ログインが必要です'], 401);
        }
        // すでにいいねされているかチェック
        $like = Like::where('user_id', $user->id)->where('item_id', $item->id)->first();

        if ($like) {
            // いいねを解除
            $like->delete();
            // $status = 'unliked';
            $liked = false;
        } else {
            // いいねを登録
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
            // $status = 'liked';
            $liked = true;
        }

        Log::info('いいね処理完了:', ['liked' => $liked]);

        return response()->json([
            // 'status' => $status,
            'liked' => $liked,
            'like_count' => $item->likes()->count(),

        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
