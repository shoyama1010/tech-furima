<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'categories.*' => 'exists:categories,id',// 配列の各値がcategoriesテーブルに存在するかチェック
            'categories' => 'required|array|min:1', // 配列として受け取る
            'condition' => 'required|string|max:255',
            // 'image' => ['nullable', 'image', 'max:2048'],
            'image' => ['nullable', 'image', 'max:3072'],
        ];
    }

    public function messages()
    {
        return [
            'image.image' => '画像ファイルを選択してください。',
            'image.max' => '画像サイズは3MB以下にしてください。',
            'categories.required' => 'カテゴリを1つ以上選択してください。',
            'categories.min' => 'カテゴリを1つ以上選択してください。',
        ];
    }
}
