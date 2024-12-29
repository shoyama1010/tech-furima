<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        return view('mypage', compact('user', 'itemsSold', 'itemsPurchased'));
    }

}
