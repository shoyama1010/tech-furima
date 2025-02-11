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
        // $status = $request->query('status', 'sell');
        $viewType = $request->query('page', 'recommend');

        //「mylist」機能
        if ($viewType === 'mylist' && $user) {
            $items = $user->likes()
                // ->with('item')
                ->with(['item' => function ($query) {
                    $query->where('is_sold', 1) // is_soldが1の商品を取得
                        ->withCount('likes'); // いいね数を取得
                }])
                ->whereHas('item', function ($query) {
                    $query->where('is_sold', 1); // is_soldが1の商品を取得
                })->get()->pluck('item');

        } else {
            // 商品一覧の取得
            $items = Item::where('status', '!=', 'draft')
                ->where(function ($query) use ($userId) {

                    if (!is_null($userId)) { // ログイン済みの場合のみ除外処理を適用
                        $query->where('user_id', '!=', $userId);
                    }
                })
                ->withCount('likes') // いいね数を取得
                ->get();
        }
        return view('items.index', ['items' => $items, 'viewType' => $viewType]);
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
        $item = Item::with(['likes', 'categories', 'comments.user'])->findOrFail($id)->fresh();

        // 追加: likes を明示的にロード
        $item->load('likes');

        // この商品が購入済みかどうかを確認
        $isSold = Order::where('item_id', $item->id)->exists();
        return view('items.detail', compact('item', 'isSold'));
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

            // カテゴリIDを取得（最初の1つを `category_id` に設定）
            if ($request->has('categories')) {
                $categories = $request->input('categories');
                $validatedData['category_id'] = is_array($categories) ? $categories[0] : null;
            }

            // 商品データを作成
            $item = Item::create($validatedData);

            // 画像アップロード処理
            if ($request->hasFile('image')) {
                $image = $request->file('image'); // 複数ではなく1つ目を取得
                $path = $image->store('public/item_images');
                $item->update(['image_url' => str_replace('public/', 'storage/', $path)]);
                // $path = str_replace('public/', '', $path); // 「storage/」をつけずに保存
                // $item->update(['image_url' => "storage/{$path}"]); // 正しいURLにする
            } else {
                $item->update(['image_url' => 'storage/item_images/no-image.png']);        
            }
            // カテゴリの紐付け
            if ($request->has('categories')) {
                $categories = $request->input('categories');
                $item->categories()->sync($categories);
            }

            DB::commit(); // トランザクション確定
            return redirect()->route('items.index')->with('success', '商品を出品しました！');
        } catch (\Exception $e) {
            DB::rollBack(); // トランザクションをロールバック

            return back()->withErrors(['error' => '商品の登録中にエラーが発生しました: ' . $e->getMessage()]);
        }
    }

    public function like(Request $request, $id): JsonResponse
    {
        $user = Auth()->user();

        if (!$user) {
            return response()->json(['error' => 'ログインが必要です'], 401);
        }

        $like = Like::where('user_id', $user->id)->where('item_id', $id)->first();

        if ($like) {
            // いいねを解除
            $like->delete();
            return response()->json(['message' => 'いいねを解除しました', 'liked' => false]);
        } else {
            // いいねを登録
            Like::create([
                // 'user_id' => $userId,
                'user_id' => $user->id,
                'item_id' => $id,
            ]);

            return response()->json([
                'message' => 'いいねしました',
                'liked' => true
            ]);
        }
    }
}
