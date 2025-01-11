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

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); // 現在ログイン中のユーザーIDを取得
        $userId = $user ? $user->id : null; // ログインしていない場合はnull

        $status = $request->query('status', 'sell'); // 'sell'がデフォルト
        $viewType = $request->query('page', 'recommend'); // デフォルト:'recommend'

        //「mylist」機能
        if ($viewType === 'mylist' && $user) { // 認証済みかつ'mylist'が選択されている場合    
            // ユーザーが「いいね」した,かつ出品済み（is_sold = 1）の商品を取得
            $items = $user->likes()->with('item')->whereHas('item', function ($query) {
                $query->where('is_sold', 1); // is_soldが1の商品を取得
            })->get()->pluck('item');
              
        } else {
            // 商品一覧の取得
            $items = Item::where('status', '!=', 'draft') // 下書き商品を除外
                ->where('status', $status) // 'sell'または'sold'を選択
                ->where(function ($query) use ($userId) {
                    // 自分が出品した商品を除外（ログイン中のユーザーIDに基づく）
                    if (!is_null($userId)) { // ログイン済みの場合のみ除外処理を適用
                        $query->where('user_id', '!=', $userId);
                    }
                })->get();
        }
        return view('items.index',['items' => $items,'viewType' => $viewType] );
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
        // ユーザーが出品した商品
        $itemsPurchased = []; // 購入した商品（必要に応じて実装）

        return view('profile.mypage', compact('user', 'itemsSold', 'itemsPurchased'));
    }

    public function show($id)
    {
        // 商品情報を取得
        $item = Item::with(['likes', 'category', 'comments.user', 'images'])->findOrFail($id);
        // この商品が購入済みかどうかを確認
        $isSold = Order::where('item_id', $item->id)->exists();

        return view('items.detail', compact('item', 'isSold'));
    }

    // 商品カテゴリー
    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $validatedData = $request->validated();

        // 商品データーを保存
        $validatedData['user_id'] = auth()->id();
        $validatedData['status'] = 'sell'; // 初期状態を'sell'に設定
        $item = Item::create($validatedData);

        // 画像アップロード処理
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $path = $image->store('item_images', 'public');
                // item_images テーブルに画像パスを保存

                $item->images()->create(['image_url' => $path,]);
            }

            return redirect()->route('items.index')->with('success', '商品を出品しました！');
        }
    }

    public function like(Request $request, $id)
    {
        $userId = auth()->id();

        // すでにいいねしているか確認
        $like = Like::where('user_id', $userId)->where('item_id', $id)->first();

        if ($like) {
            // いいねを解除
            $like->delete();
            return response()->json(['message' => 'いいねを解除しました', 'liked' => false]);
        } else {
            // いいねを登録
            Like::create([
                'user_id' => $userId,
                'item_id' => $id,
            ]);
            return response()->json(['message' => 'いいねしました', 'liked' => true]);
        }
    }
}
