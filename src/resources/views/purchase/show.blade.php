@extends('layouts.app')

@section('main')
<div class="container">
    <!-- <h2>商品購入画面</h2> -->
    <div class="card p-4">
        <div class="image-container">
            <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
            <p>価格: ¥{{ number_format($item->price) }}</p>
        </div>

        <!-- 支払い方法選択フォーム -->
        <form method="POST" action="{{ route('purchase.process', $item->id) }}">
            @csrf
            <div class="payment-container">
                <label for="payment_method">支払い方法</label>
                <select name="payment_method" id="payment_method" class="form-control">
                    <option value="コンビニ払い">コンビニ払い</option>
                    <option value="カード支払い">カード支払い</option>
                </select>
            </div>
            <!-- <button type="submit" class="btn btn-primary mt-3">購入する</button> -->
            @if(!$item->is_sold)
            <button type="submit" class="btn btn-primary">購入する</button>
            @else
            <button type="button" class="btn btn-secondary" disabled>購入済み</button>
            @endif
        </form>

        <div class="mt-4">
            <h5>配送先住所</h5>
            @if ($address) <!-- $address が存在するか確認 -->
            <p>
                <i class="bi bi-envelope"></i> 〒{{ $address->postal_code }}<br>
                <i class="bi bi-geo-alt"></i> {{ $address->address }}<br>
                <!-- @if ($address->building) -->
                <i class="bi bi-building"></i> {{ $address->building }}
                <!-- @endif -->
            </p>
            @else
            <p>配送先住所が未設定です。</p>
            @endif
            <a href="{{ route('purchase.address.edit', $item->id) }}" class="btn btn-link">変更する</a>
        </div>
    </div>
</div>
@endsection