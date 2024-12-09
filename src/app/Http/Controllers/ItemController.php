<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
