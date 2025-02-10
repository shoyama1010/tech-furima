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
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    public function buyitem($id)
    {
        // 商品データを取得
        $item = Item::where('id', $id)->firstOrFail();
        // $item = Item::where('id', $id)->where('is_sold', 0)->firstOrFail();
        // ログインユーザーの住所情報を取得
        $address = Address::where('user_id', auth()->id())->first();
        // 購入画面を表示
        return view('purchase.show', compact('item', 'address'));
    }

    // public function purchase($id)
    public function purchase(PurchaseRequest $request, $id)
    {
        try {
            // Stripe設定
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = null;
            DB::transaction(function () use ($id, &$session, $request) {
                // 商品をロックして取得
                $item = Item::lockForUpdate()->findOrFail($id);
                if ($item->is_sold) {
                    throw new \Exception('商品は既に購入済みです');
                }
                // 商品を "sold" 状態に変更
                $item->update(['is_sold' => 1, 'status' => 'sold',]);
                // 既存のオーダーがあるか確認し、なければ作成
                $existingOrder = Order::where('user_id', auth()->id())
                    ->where('item_id', $item->id)
                    ->exists();
                if (!$existingOrder) {
                    // 購入完了後のロジック
                    Order::create([
                        'user_id' => auth()->id(),
                        'item_id' => $item->id,
                        'quantity' => 1,
                        'total_price' => $item->price, 
                        // 'status' => 'completed',
                        'status' => 'pending', // ステータスを "pending" に設定
                    ]);
                }
                // Stripe 決済セッション作成
                $session = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'price_data' => [
                                'currency' => 'jpy','product_data' => ['name' => $item->name],
                                'unit_amount' => $item->price * 100, // 円をセントに変換
                            ],
                            'quantity' => 1,
                        ]
                    ],
                    'mode' => 'payment',
                    'success_url' => route('purchase.success', $id), // 成功後のリダイレクト先
                    'cancel_url' => route('purchase.show', $id),
                ]);
            });
            // トランザクション外でリダイレクトを実行
            return redirect($session->url);
        } catch (\Exception $e) {
            logger()->error('購入処理エラー: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'item_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('items.detail', $id)->with('error', '購入処理中にエラーが発生しました。');
        }
    }

    public function success($id)
    {
        // 商品の購入成功後の処理
        $item = Item::findOrFail($id);

        // 商品を "sold"(売り切れかどうか) 状態に変更
        if (!$item->is_sold) {
            return redirect()->route('mypage')->with('error', 'この商品はまだ購入済です。');
        }

        // 既に注文済みか確認
        $order = Order::where('user_id', auth()->id())
            ->where('item_id', $item->id)
            ->where('status', 'pending')
            ->first();

        if ($order) {
            // 支払いが成功したので、注文ステータスを "completed" に変更
            $order->update(['status' => 'completed']);

            // 支払い記録を保存（重複防止）
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'user_id' => auth()->id(),
                    'payment_method' => 'stripe',
                    'amount' => $item->price,
                    'status' => 'completed',
                ]
            );

            return redirect()->route('mypage')->with('success', '購入が完了しました。');
        }
        return redirect()->route('mypage')->with('error', '注文が見つかりませんでした。');
    }

    public function history()
    {
        $orders = Order::where('user_id', auth()->id())->get();

        return view('purchase.history', compact('orders'));
    }

    public function show($id)
    {
        $item = Item::findOrFail($id); // 商品情報を取得
        $user = auth()->user(); // ログインしているユーザーを取得

        $address = Address::where('user_id', $user->id)->first();

        return view('purchase.show', compact('item', 'address'));
    }

    // 配送先住所編集画面の表示
    public function editAddress($id)
    {
        $item = Item::findOrFail($id);
        $address = Address::where('user_id', auth()->id())->first() ?? new Address(); // ユーザーの住所情報
        return view('purchase.address_edit', compact('item', 'address'));
    }

    // 配送先住所の更新処理
    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'postal_code' => 'required|string|max:8',
            // 'postal_code' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
        ]);

        // ログインしているユーザーの住所情報を取得、または新規作成
        $address = Address::where('user_id', auth()->id())->firstOrNew([
            'user_id' => auth()->id()
        ]);

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
