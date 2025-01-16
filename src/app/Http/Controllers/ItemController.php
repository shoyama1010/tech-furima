<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ExhibitionRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Like;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        // ユーザーが出品した商品
       
        $itemsPurchased = $user->purchasedItems;

        return view('mypage.mypage', compact('user', 'itemsSold', 'itemsPurchased'));
        // ユーザーが購入した商品のリストを格納するための変数
    }

    public function show($id)
    {
        // 商品情報を取得
        $item = Item::with(['likes', 'categories', 'comments.user'])->findOrFail($id);
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

        DB::beginTransaction(); // トランザクション開始

        try {
        // 商品データーを保存
        $validatedData['user_id'] = auth()->id();
        $validatedData['status'] = 'sell'; // 初期状態を'sell'に設定
        $item = Item::create($validatedData);

            
        // 画像アップロード処理
            if ($request->hasFile('image')) {
                $images = $request->file('image');
                foreach ($images as $key => $image) {         
                    // 画像を保存
                    $path = $image->store('item_images', 'public');
                    // 最初の画像を `image_url` カラムに設定
                    if ($key === 0) {
                        $item->update(['image_url' => $path]);
                        $item->save(); // 保存を確実に実行
                    }
                }
            }
            
            // カテゴリの紐付け
            if ($request->has('categories')) {
                // $item->categories()->sync($request->input('categories'));
                $categories = $request->input('categories');
                $item->categories()->syncWithPivotValues($categories, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } 

            DB::commit(); // トランザクション確定

            return redirect()->route('items.index')->with('success', '商品を出品しました！');

        } catch (\Exception $e) {
            DB::rollBack(); // トランザクションをロールバック
            // エラーメッセージを表示
            return back()->withErrors(['error' => '商品の登録中にエラーが発生しました: ' . $e->getMessage()]);

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
