<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{

    public function authorize()
    {
        // 認証済みユーザーにのみ許可
        // return auth()->check();
        return true; // 一時的に全てのユーザーを許可
    }

    public function rules()
    {
        return [
            'content' => 'required|string|max:500',
            'item_id' => 'required|exists:items,id',
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'コメント内容を入力してください。',
            'content.max' => 'コメントは500文字以内で入力してください。',
            'item_id.exists' => '該当する商品が見つかりません。',
        ];
    }
}
