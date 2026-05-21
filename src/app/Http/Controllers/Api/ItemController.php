<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $keyword = $request->query('keyword');

        $items = Item::query()
            ->when($keyword, function ($query, $keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            })
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'condition' => $item->condition,
                    'is_sold' => (bool) $item->is_sold,
                    'status' => $item->status,
                    'image_url' => $item->image_url,
                ];
            });

        return response()->json([
            'data' => $items,
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $item = Item::with([
            'categories:id,name',
            'comments' => function ($query) {
                $query->latest();
            },
            'comments.user:id,name',
            'likes',
        ])->withCount([
            'likes',
            'comments',
        ])->findOrFail($id);

        $likedByMe = false;

        if ($user) {
            $likedByMe = $item->likes->contains('user_id', $user->id);
        }

        return response()->json([
            'data' => [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'description' => $item->description,
                'condition' => $item->condition,
                'image_url' => $item->image_full_url,
                'is_sold' => $item->isSold(),
                'status' => $item->status,
                'likes_count' => $item->likes_count,
                'comments_count' => $item->comments_count,
                'liked_by_me' => $likedByMe,
                'categories' => $item->categories->map(fn($category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                ])->values(),

                'comments' => $item->comments->map(fn($comment) => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'created_at' => $comment->created_at ? $comment->created_at->format('Y-m-d H:i:s') : null,
                    // 'created_at' => $comment->created_at?->format('Y-m-d H:i:s'),
                    'user' => [
                        'id' => $comment->user ? $comment->user->id : null,
                        'name' => $comment->user ? $comment->user->name : null,
                        // 'id' => $comment->user?->id,
                        // 'name' => $comment->user?->name,
                    ],
                ])->values(),
            ],
        ]);
    }

    public function like(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'ログインが必要です'], 401);
        }

        $item = Item::findOrFail($id);

        $like = $item->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();

            return response()->json([
                'message' => 'いいねを解除しました',
                'liked' => false,
                'likes_count' => $item->likes()->count(),
            ]);
        }

        $item->likes()->create([
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'いいねしました',
            'liked' => true,
            'likes_count' => $item->likes()->count(),
        ]);
    }
}
