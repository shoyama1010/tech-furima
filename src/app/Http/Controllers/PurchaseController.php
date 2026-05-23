<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Address;
use App\Models\Item;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PurchaseController extends Controller
{
    public function buyitem($id)
    {
        $item = Item::findOrFail($id);
        $user = auth()->user();

        $address = Address::where('user_id', auth()->id())->first();

        if (!$address && ($user->postal_code || $user->address || $user->building)) {
            $address = (object) [
                'postal_code' => $user->postal_code,
                'address' => $user->address,
                'building' => $user->building,
            ];
        }

        return view('purchase.show', compact('item', 'address'));
    }

    public function purchase(PurchaseRequest $request, $id)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $item = null;
            $order = null;

            DB::transaction(function () use ($id, &$item, &$order) {
                $item = Item::lockForUpdate()->findOrFail($id);

                if ($item->is_sold || $item->status === 'sold') {
                    throw new \RuntimeException('商品は既に購入済みです。');
                }

                if ((int) $item->user_id === (int) auth()->id()) {
                    throw new \RuntimeException('自分の商品は購入できません。');
                }

                $order = Order::firstOrCreate(
                    [
                        'user_id' => auth()->id(),
                        'item_id' => $item->id,
                        'status' => 'pending',
                    ],
                    [
                        'quantity' => 1,
                        'total_price' => $item->price,
                    ]
                );
            });

            $session = Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->name,
                        ],
                        'unit_amount' => (int) $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'metadata' => [
                    'order_id' => (string) $order->id,
                    'item_id' => (string) $item->id,
                    'user_id' => (string) auth()->id(),
                ],
                'success_url' => route('purchase.success', ['id' => $item->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('purchase.show', ['id' => $item->id]),
            ]);

            $order->update([
                'payment_session_id' => $session->id,
            ]);

            return redirect($session->url);
        } catch (\Throwable $e) {
            logger()->error('購入処理エラー', [
                'user_id' => auth()->id(),
                'item_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('items.detail', $id)
                ->with('error', '購入処理中にエラーが発生しました。');
        }
    }

    public function success(Request $request, $id)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $sessionId = $request->query('session_id');
            if (empty($sessionId)) {
                return redirect()
                    ->route('items.detail', $id)
                    ->with('error', '決済セッションを確認できませんでした。');
            }

            $checkoutSession = Session::retrieve($sessionId);

            if ($checkoutSession->payment_status !== 'paid') {
                return redirect()
                    ->route('items.detail', $id)
                    ->with('error', '決済が完了していません。');
            }

            DB::transaction(function () use ($id, $sessionId) {
                $item = Item::lockForUpdate()->findOrFail($id);

                $order = Order::where('user_id', auth()->id())
                    ->where('item_id', $item->id)
                    ->where('status', 'pending')
                    ->latest('id')
                    ->first();

                if (!$order) {
                    throw new \RuntimeException('注文が見つかりませんでした。');
                }

                if (!$item->is_sold && $item->status !== 'sold') {
                    $item->update([
                        'is_sold' => true,
                        'status' => 'sold',
                    ]);
                }

                $order->update([
                    'status' => 'completed',
                    'payment_session_id' => $sessionId,
                ]);

                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'user_id' => auth()->id(),
                        'payment_method' => 'stripe',
                        'amount' => $item->price,
                        'status' => 'completed',
                    ]
                );
            });

            return redirect()
                ->route('mypage')
                ->with('success', '購入が完了しました。');
        } catch (\Throwable $e) {
            logger()->error('購入完了処理エラー', [
                'user_id' => auth()->id(),
                'item_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('items.detail', $id)
                ->with('error', '購入完了処理中にエラーが発生しました。');
        }
    }

    public function history()
    {
        $orders = Order::where('user_id', auth()->id())->get();

        return view('purchase.history', compact('orders'));
    }

    public function show($id)
    {
        $item = Item::findOrFail($id);

        $user = auth()->user();

        $address = Address::where('user_id', $user->id)->first();

        if (!$address && ($user->postal_code || $user->address || $user->building)) {
            $address = (object) [
                'postal_code' => $user->postal_code,
                'address' => $user->address,
                'building' => $user->building,
            ];
        }

        return view('purchase.show', compact('item', 'address'));
    }

    public function editAddress($id)
    {
        $item = Item::findOrFail($id);
        $user = auth()->user();

        $address = Address::where('user_id', $user->id)->first();

        if (!$address) {
            $address = new Address();
            $address->postal_code = $user->postal_code;
            $address->address = $user->address;
            $address->building = $user->building;
        }
        // $address = Address::where('user_id', auth()->id())->first() ?? new Address();
        return view('purchase.address_edit', compact('item', 'address'));
    }

    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'postal_code' => 'required|string|max:8',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $user = auth()->user();

            $address = Address::where('user_id', auth()->id())->firstOrNew([
                'user_id' => auth()->id(),
            ]);

            $address->postal_code = $request->input('postal_code');
            $address->address = $request->input('address');
            $address->building = $request->input('building');
            $address->is_default = true;
            $address->save();

        });

        return redirect()
            ->route('purchase.show', $id)
            ->with('success', '配送先住所が更新されました。');
    }
}
