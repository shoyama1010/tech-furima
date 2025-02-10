@extends('layouts.app')

@section('main')
<div class="container">
    <div class="card p-4">
        <div class="row">
            <!-- 左側の商品画像＆支払い選択＆住所-->
            <div class="image-container">
                <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                <div class="mt-4">
                    <h5>配送先住所</h5>
                    @if ($address)
                    <!-- $address が存在するか確認 -->
                    <p>
                        <i class="bi bi-envelope"></i> 〒{{ $address->postal_code }}<br>
                        <i class="bi bi-geo-alt"></i> {{ $address->address }}<br>
                        @if ($address->building)
                        <i class="bi bi-building"></i> {{ $address->building }}
                        @endif
                    </p>
                    @else
                    <p>配送先住所が未設定です。</p>
                    @endif
                    <a href="{{ route('purchase.address.edit', $item->id) }}" class="btn btn-link">変更する</a>
                </div>
            </div>

            <!-- 右側（購入情報） -->
            <div class="col-md-6">
                <h2>{{ $item->name }}</h2>
                <p><strong>価格:</strong> ¥{{ number_format($item->price) }}</p>

                <div class="purchase-summary border p-3">
                    <h5>商品代金</h5>
                    <p id="total-price">¥{{ number_format($item->price) }}</p>

                    <h5>支払い方法</h5>
                    <p id="payment-method-display">コンビニ支払い</p>
                </div>

                <form id="purchase-form" method="POST" action="{{ route('purchase.process', $item->id) }}">
                    @csrf

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="form-group mt-3">
                        <label for="payment_method">支払い方法</label>
                        <select name="payment_method" id="payment_method" class="form-control">
                            <option value="convenience_store">コンビニ支払い</option>
                            <option value="credit_card">カード支払い</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        @if(!$item->is_sold)
                        <button type="submit" id="submit-button" class="btn btn-danger btn-block">購入する</button>
                        @else
                        <button type="button" class="btn btn-secondary btn-block" disabled>購入済み</button>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- <JavaScriptでボタン無効化 -->
    <script>
        document.getElementById('payment_method').addEventListener('change', function() {
            const selectedMethod = this.value;
            document.getElementById('payment-method-display').textContent = selectedMethod;
        });

        document.getElementById('purchase-form').addEventListener('submit', function(event) {
            const submitButton = document.getElementById('submit-button');
            submitButton.disabled = true; // ボタンを無効化
            submitButton.textContent = '処理中...'; // ボタンのテキストを変更

            setTimeout(() => {
                submitButton.disabled = false;
                submitButton.textContent = '購入する';
            }, 5000); // 5秒後に再有効化（エラー対策）
        });
    </script>
    @endsection