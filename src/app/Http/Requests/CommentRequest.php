<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize()
    {
        // 認証済みユーザーにのみ許可
        return auth()->check();
        // return true; // 一時的に全てのユーザーを許可
    }

    public function rules()
    {
        return [
            'content' => 'required|string|max:500',
            'item_id' => 'required|exists:items,id',
        ];
    }
}
