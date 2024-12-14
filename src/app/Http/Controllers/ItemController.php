<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

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
        // return view('items.index', compact('items'));
        return view('items.index',
            [
                'items' => $items,
                'viewType' => $viewType, // 現在の表示タイプ
            ]
        );
    }

    public function mypage()
    {
        $user = Auth::user(); // ログインユーザー情報を取得
        $itemsSold = Item::where('user_id', $user->id)->get(); // ユーザーが出品した商品
        $itemsPurchased = []; // 購入した商品（必要に応じて実装）

        return view('profile.mypage', [
            'user' => $user,
            'itemsSold' => $itemsSold,
            'itemsPurchased' => $itemsPurchased
        ]);
    }

    // プロフィール編集画面を表示
    public function editProfile()
    {
        $user = Auth::user(); // ログインユーザー情報を取得
        return view('profile.edit', ['user' => $user]);
    }
    
    // プロフィールを更新
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048',
        ]);

        // プロフィール画像の保存
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::delete($user->profile_image); // 既存の画像を削除
            }
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $validated['profile_image'] = $path; // 公開ディレクトリに保存
        }

        // ユーザー情報の更新
        $user->update($validated);

        return redirect()->route('mypage')->with('success', 'プロフィールを更新しました。');
    }

    public function show($id)
    {
        // 商品情報を取得
        $item = Item::with('category', 'comments.user')->findOrFail($id);

        // 商品が見つからなかった場合に404エラー
        if (!$item) {
            abort(404, '商品が見つかりませんでした。');
        }
        
        return view('items.detail', compact('item'));
    }

}
