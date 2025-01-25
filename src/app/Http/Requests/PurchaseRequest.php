<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return true;
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'id' => 'required|exists:items,id',
            // 'item_id' => 'required|exists:items,id',
            // 'quantity' => 'required|integer|min:1',
            // 'address'  => 'required|string|max:255',
            // 'payment_method' => 'required|in:credit_card,bank_transfer,cash_on_delivery',
            // 'payment_method' => 'required|string|in:card,bank_transfer',
            'payment_method' => 'required|in:convenience_store,credit_card',
        ];
    }

    /**
     * エラーメッセージのカスタマイズ
     */
    public function messages()
    {
        return [
            'item_id.required' => '商品IDは必須です。',
            'item_id.exists' => '選択された商品は存在しません。',
            'quantity.required' => '数量は必須です。',
            'quantity.integer' => '数量は整数でなければなりません。',
            'quantity.min' => '数量は1以上にしてください。',
            'address.required' => '配送先住所は必須です。',
            'payment_method.required' => '支払い方法を選択してください。',
            'payment_method.in' => '支払い方法は「クレジットカード」「銀行振込」「代引き」から選択してください。',
        ];
    }
}
