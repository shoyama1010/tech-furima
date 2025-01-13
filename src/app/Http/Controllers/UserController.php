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
        // プロフィール画像の保存
        $validatedData['profile_image'] = $path;

        if ($request->hasFile('profile_image')) {
            
            $profileImage = $request->file('profile_image');
            
            // 既存の画像があれば削除
            if ($user->profile_image) {
                Storage::delete('public/' . $user->profile_image);
            }
            // 新しい画像を保存
            $path = $profileImage->store('profile_images', 'public');
            $user->profile_image = $path;
    
            // users テーブルの profile_image カラムにパスを保存
            $user->update([
                'profile_image' => $path, // ここで画像パスを設定
                
                'name' => $request->input('name'), // 他のフィールドも更新
                'postal_code' => $request->input('postal_code'),
                'address' => $request->input('address'),
                'building' => $request->input('building'),
            ]);
            
        } else {
            // プロフィール画像以外の更新
            $user->update($request->only('name', 'postal_code', 'address', 'building'));
        }

        return redirect()->route('mypage')->with('success', 'プロフィールを更新しました。');
    }
}
