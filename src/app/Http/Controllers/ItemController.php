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

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $items = Item::all(); // データベースから商品全体を取得

        $viewType = $request->query('page', 'recommend'); // デフォルトは'recommend'
        // 認証済みかつ'mylist'が選択されている場合
        if ($viewType === 'mylist' && $user) {
            // ユーザーが「いいね」した商品を取得
            $items = $user->likes()->with('item')->get()->pluck('item');
        }
        // ビューにデータを渡して表示
        return view(
            'items.index',
            [
                'items' => $items,
                'viewType' => $viewType, // 現在の表示タイプ
            ]
        );
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
        $itemsSold = Item::where('user_id', $user->id)->get(); 
        // ユーザーが出品した商品
        $itemsPurchased = []; // 購入した商品（必要に応じて実装）

        return view('profile.mypage', [
            'user' => $user,
            'itemsSold' => $itemsSold,
            'itemsPurchased' => $itemsPurchased
        ]);
    }

    // // プロフィール編集画面を表示
    // public function editProfile()
    // {
    //     $user = Auth::user(); // ログインユーザー情報を取得
        
    //     return view('profile.edit', compact('user'));
    // }
    // // プロフィールを更新
    // public function updateProfile(ProfileRequest $request)
    // {
    //     $user = Auth::user();
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'postal_code' => 'nullable|string|max:7',
    //         'address' => 'nullable|string|max:255',
    //         'building' => 'nullable|string|max:255',
    //         'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);
    //     // プロフィール画像の保存
    //     if ($request->hasFile('profile_image')) {
    //         if ($user->profile_image) {
    //             Storage::delete($user->profile_image); // 既存の画像を削除
    //         }

    //         $path = $request->file('profile_image')->store('profile_images', 'public');
    //         // $validated['profile_image'] = $path; // 公開ディレクトリに保存
    //         $user->profile_image = $path;
    //     }
    //     // ユーザー情報の更新
    //     // $user->update($request->validated());
    //     $user->update($request->only(['name', 'postal_code', 'address', 'building']));

    //     return redirect()->route('mypage')->with('success', 'プロフィールを更新しました。');
    // }

    public function show($id)
    {
        // 商品情報を取得
        $item = Item::with(['likes', 'category', 'comments.user'])->findOrFail($id);

        return view('items.detail', compact('item'));
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
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('item_images', 'public');
            $validatedData['image_url'] = $path;
        }

        $validatedData['user_id'] = auth()->id();
        
        // 商品を保存
        Item::create($validatedData);
        
        return redirect()->route('items.index')->with('success', '商品を出品しました！');
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
