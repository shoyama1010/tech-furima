<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class PurchaseController extends Controller
{
    // public function show($id)
    public function buyitem($id)
    {
        // 商品データを取得
        $item = Item::findOrFail($id);

        // 購入画面を表示
        return view('purchase.show', compact('item'));
    }
}
