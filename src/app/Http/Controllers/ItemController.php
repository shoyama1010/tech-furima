<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        // データベースから商品を取得
        $items = Item::all(); // 必要ならpaginate()も利用可

        // ビューにデータを渡して表示
        return view('items.index', compact('items'));
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
            $path = $request->file('profile_image')->store('profile_images');
            $validated['profile_image'] = $path;
        }

        // ユーザー情報の更新
        $user->update($validated);

        return redirect()->route('mypage')->with('success', 'プロフィールを更新しました。');
    }
}
