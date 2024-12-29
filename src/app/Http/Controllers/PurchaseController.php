<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function buyitem($id)
    {
        // 商品データを取得
        $item = Item::findOrFail($id);

        // 購入画面を表示
        return view('purchase.show', compact('item'));
    }

    public function purchase($id)
    {
        $item = Item::findOrFail($id);

        // Stripe設定
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => ['name' => $item->name],
                        'unit_amount' => $item->price * 100, // 円をセントに変換
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => route('purchase.success', $id), // 成功後のリダイレクト先
            'cancel_url' => route('purchase.show', $id),
        ]);
        // 購入完了後のロジック
        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'quantity' => 1,
            'total_price' => $item->price,
            'status' => 'completed',
        ]);
        
        // 商品を "sold" 状態に変更
        $item->update(['is_sold' => true]);
        return redirect($session->url);
    }

    public function history()
    {
        $orders = \App\Models\Order::where('user_id', auth()->id())->get();

        return view('purchase.history', compact('orders'));
    }
    public function editAddress($id)
    {
        $item = Item::findOrFail($id);
        return view('purchase.address_edit', compact('item'));
    }

    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'postal_code' => 'required|string|max:7',
            'address' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        $item = Item::findOrFail($id);
        $item->shipping_address = $request->input('address');
        $item->save();

        return redirect()->route('purchase.show', $id)->with('success', '配送先住所が更新されました。');
    }
}
