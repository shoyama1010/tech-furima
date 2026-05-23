<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ExhibitionRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Like;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = $user ? $user->id : null;
        $viewType = $request->query('page', 'recommend');
        $keyword = $request->query('keyword');

        //「mylist」機能
        if ($viewType === 'mylist' && $user) {
            $items = Like::with([
                'item' => function ($query) use ($keyword) {
                    $query->where('status', '!=', 'draft')
                        ->when($keyword, function ($q, $keyword) {
                            $q->where('name', 'like', '%' . $keyword . '%');
                            //   ->orWhere('description', 'LIKE', "%{$keyword}%");
                        })
                        ->withCount('likes'); // いいね数を取得
                }
            ])
                ->where('user_id', $user->id)
                // ->whereHas('item', function ($query) {
                ->get()
                ->pluck('item')
                ->filter();
        } else {
            // 商品一覧の取得
            $items = Item::query()
                ->where('status', '!=', 'draft')
                ->when(!is_null($userId), function ($query) use ($userId) {
                    $query->where('user_id', '!=', $userId);
                })

                ->when($keyword, function ($query, $keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                })
                ->withCount('likes')
                ->get();
        }

        return view('items.index', [
            'items' => $items,
            'viewType' => $viewType,
            'keyword' => $keyword,
        ]);
        // return view('items.index', ['items' => $items, 'viewType' => $viewType]);
    }

    // 検索機能    
    public function search(Request $request)
    {
        $keyword = $request->input('keyword'); // 検索キーワード取得

        // キーワード検索処理
        $items = Item::where('name', 'LIKE', '%' . $keyword . '%')
            // 商品名で部分一致で検索
            ->orWhere('description', 'LIKE', '%' . $keyword . '%')
            // 商品説明でも検索
            ->paginate(10); // ページネーション

        return view('search', compact('items', 'keyword'));
    }
    public function mypage()
    {
        $user = Auth::user(); // ログインユーザー情報を取得
        $itemsSold = $user->items; // ユーザーが出品した商品

        $itemsPurchased = $user->purchasedItems;

        return view('mypage.mypage', compact('user', 'itemsSold', 'itemsPurchased'));
    }

    public function show($id)
    {
        // 商品情報を取得
        $item = Item::with([
            'categories',
            'comments.user',
            'likes',
        ])->withCount([
            'likes',
            'comments',
        ])->findOrFail($id);

        $isSold = $item->isSold();

        $likedByMe = Auth::check()
            ? $item->likes->contains('user_id', Auth::id())
            : false;

        return view('items.detail', compact('item', 'isSold', 'likedByMe'));
    }

    // 商品カテゴリー
    public function create()
    {
        $categories = Category::all();
        $item = new Item(); // 空の Item オブジェクトを渡す
        return view('items.create', compact('categories', 'item'));
    }

    public function store(ExhibitionRequest $request)
    {
        $validatedData = $request->validated();
        DB::beginTransaction(); // トランザクション開始

        try {
            // 商品データーを保存
            $validatedData['user_id'] = auth()->id();
            $validatedData['status'] = 'available';
            $validatedData['is_sold'] = false;

            // カテゴリIDを取得（最初の1つを `category_id` に設定）
            if ($request->has('categories')) {
                $categories = $request->input('categories');
                $validatedData['category_id'] = is_array($categories) ? $categories[0] : null;
            }

            // 画像アップロード処理
            if ($request->hasFile('image')) {
                $validatedData['image_url'] = $request->file('image')->store('item_images', 'public');
            } else {
                $validatedData['image_url'] = null;
            }

            // 商品データを作成
            $item = Item::create($validatedData);

            // カテゴリの紐付け
            if ($request->has('categories')) {
                $item->categories()->sync($categories);
            }

            DB::commit(); // トランザクション確定
            return redirect()->route('items.index')->with('success', '商品を出品しました！');
            
        } catch (\Exception $e) {
            DB::rollBack(); // トランザクションをロールバック
            Log::error('商品の登録中にエラーが発生しました: ' . $e->getMessage());
            dd($e->getMessage());
        }
    }

    public function like(Request $request, $id): JsonResponse
    {
        $user = Auth()->user();

        if (!$user) {
            return response()->json(['error' => 'ログインが必要です'], 401);
        }

        $item = Item::findOrFail($id);

        $like = Like::where('user_id', $user->id)
            ->where('item_id', $id)
            ->first();

        if ($like) {  // いいねを解除
            $like->delete();

            return response()->json([
                'message' => 'いいねを解除しました',
                'liked' => false,
                'likes_count' => $item->likes()->count(), //いいね数を返す
            ]);
        }

        // いいねを登録
        Like::create([
            // 'user_id' => $userId,
            'user_id' => $user->id,
            'item_id' => $id,
        ]);

        return response()->json([
            'message' => 'いいねしました',
            'liked' => true,
            'likes_count' => $item->likes()->count(),
        ]);
    }
}
