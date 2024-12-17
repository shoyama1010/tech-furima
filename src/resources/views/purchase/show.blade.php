@extends('layouts.app')

@section('main')
<div class="container">
    <!-- <h2>商品購入画面</h2> -->
    <div class="card p-4">
        <div class="image-container">
            <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
            <p>価格: ¥{{ number_format($item->price) }}</p>
        </div>

        <form method="POST" action="#">
            @csrf
            <div class="payment-container">
                <label for="payment_method">支払い方法</label>
                <select name="payment_method" id="payment_method" class="form-control">
                    <option value="コンビニ払い">コンビニ払い</option>
                    <option value="カード支払い">カード支払い</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">購入する</button>
        </form>
    </div>
</div>
@endsection