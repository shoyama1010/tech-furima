<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function buyitem($id){
        // 商品データを取得

        $item = Item::where('id', $id)->where('is_sold', 0)->firstOrFail();
        // ログインユーザーの住所情報を取得
        // $user = auth()->user();
        $address = Address::where('user_id', auth()->id())->first();

        // 購入画面を表示
        return view('purchase.show', compact('item', 'address'));
    }

    public function purchase($id){

        // $item = Item::findOrFail($id);
        DB::beginTransaction();
        try {
            $item = Item::lockForUpdate()->findOrFail($id);

        if ($item->is_sold) {
                throw new \Exception('商品は既に購入済みです');
        }

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
        Order::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'quantity' => 1,
            'total_price' => $item->price,
            'status' => 'completed',
        ]);

        // 商品を "sold" 状態に変更
        $item->update(['is_sold' => 1, 'status' => 'sold', ]);

            DB::commit();
            return redirect($session->url);
           

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('購入処理エラー: ' . $e->getMessage());

            return redirect()->route('items.detail', $id)->with('error', '購入処理中にエラーが発生しました。');
        }  
    }

    public function success($id){
        // 商品の購入成功後の処理
        $item = Item::findOrFail($id);

        // 商品を "sold" 状態に変更
        // $item->update(['is_sold' => 1]);
        if (!$item->is_sold) {
            return redirect()->route('mypage')->with('error', 'この商品はまだ購入されていません。');
        }

        // Order にデータを保存
        $order = Order::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'quantity' => 1,
            'total_price' => $item->price,
            'status' => 'completed',
        ]);
        Payment::create([
            'user_id' => auth()->id(), // 購入者のID
            'order_id' => $order->id, // 関連する注文のID
            'payment_method' => 'stripe', // 支払い方法（例: stripe）
            'amount' => $item->price, // 支払い金額
            'status' => 'completed', // ステータス
        ]);
        return redirect()->route('mypage')->with('success', '購入が完了しました。');
    }
    

    public function history() {
        $orders = Order::where('user_id', auth()->id())->get();

        return view('purchase.history', compact('orders'));
    }

    public function show($id)
    {
        $item = Item::findOrFail($id); // 商品情報を取得
        $user = auth()->user(); // ログインしているユーザーを取得

        // ログインユーザーの住所情報を取得（データがない場合は新規インスタンス）
        // $address = Address::where('user_id', $user->id)->first() ?? new Address();
        $address = Address::where('user_id', $user->id)->first();

        return view('purchase.show', compact('item', 'address'));
    }

    // 配送先住所編集画面の表示
    public function editAddress($id)
    {
        $item = Item::findOrFail($id);
        $address = Address::where('user_id', auth()->id())->first() ?? new Address(); // ユーザーの住所情報
        return view('purchase.address_edit', compact('item','address'));
    }

    // 配送先住所の更新処理
    public function updateAddress(Request $request, $id)
    {      
        $request->validate([
            'postal_code' => 'required|string|max:8',
            // 'postal_code' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
        ]);    
        // $address = Address::where('user_id', auth()->id())->firstOrNew();
        // ログインしているユーザーの住所情報を取得、または新規作成
        $address = Address::where('user_id', auth()->id())->firstOrNew([
            'user_id' => auth()->id()
        ]);
        
        // $address->user_id = auth()->id(); // 明示的にuser_idを設定
        // 入力値を設定
        $address->postal_code = $request->input('postal_code');
        $address->address = $request->input('address');
        $address->is_default = true;
        
        // デバッグ用にデータ確認
        logger()->info('Address Data', $address->toArray());

        // 保存処理
        $address->save();

        return redirect()->route('purchase.show', $id)->with('success', '配送先住所が更新されました。');
    }
}
