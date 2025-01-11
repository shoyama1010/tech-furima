<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // ストレージ用の名前空間も追加
use App\Http\Requests\ProfileRequest;

class UserController extends Controller
{
    public function show()
    {
        return view('user.mypage'); // マイページビューを指定
    }

    public function mypage()
    {
        $user = auth()->user();

        // ユーザーが出品した商品
        $itemsSold = $user->items()->where('is_sold', true)->get();
        // ユーザーが購入した商品
        $itemsPurchased = $user->purchasedItems;

        return view('mypage.mypage', compact('user', 'itemsSold', 'itemsPurchased'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('mypage.profile', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        // プロフィール画像の処理
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::delete('public/' . $user->profile_image); // 古い画像を削除
            }
            // 新しい画像の保存処理
            $path = $request->file('profile_image')->store('profile_images', 'public');
            // dd($path);
            $validated['profile_image'] = $path;
        }

        $user->update($validated);

        return redirect()->route('mypage')->with('success', 'プロフィールを更新しました。');
    }
}
