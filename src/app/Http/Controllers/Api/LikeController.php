<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, Item $item): JsonResponse
    {
        $user = $request->user();
        // $user = $request->user() ?? \App\Models\User::first();

        if (!$user) {
            return response()->json([
                'message' => 'ログインが必要です。',
            ], 401);
        }

        $like = $item->likes()
            ->where('user_id', $user->id)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $item->likes()->create([
                'user_id' => $user->id,
            ]);
            $liked = true;
        }

        return response()->json([
            'liked_by_me' => $liked,
            'likes_count' => $item->likes()->count(),
        ]);
    }
}
