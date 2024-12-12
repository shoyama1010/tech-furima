<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

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

        return view('mypage', [
            'user' => $user,
            'itemsSold' => $itemsSold,
            'itemsPurchased' => $itemsPurchased
        ]);
    }  
}
