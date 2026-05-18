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
}
