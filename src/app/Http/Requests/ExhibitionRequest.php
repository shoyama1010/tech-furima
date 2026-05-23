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
            'image' => ['required', 'image', 'max:3072'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください。',
            'name.max' => '商品名は255文字以内で入力してください。',
            'price.required' => '販売価格を入力してください。',
            'price.integer' => '販売価格は整数で入力してください。',
            'price.min' => '販売価格は1円以上で入力してください。',
            'description.required' => '商品の説明を入力してください。',
            'description.max' => '商品の説明は1000文字以内で入力してください。',
            'categories.required' => 'カテゴリを1つ以上選択してください。',
            'categories.array' => 'カテゴリの指定が不正です。',
            'categories.min' => 'カテゴリを1つ以上選択してください。',
            'categories.*.exists' => '選択したカテゴリが不正です。',
            'condition.required' => '商品の状態を選択してください。',
            'image.image' => '画像ファイルを選択してください。',
            'image.max' => '画像サイズは3MB以下にしてください。',
        ];
    }
}
