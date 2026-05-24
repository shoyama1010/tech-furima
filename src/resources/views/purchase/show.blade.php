
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('main')
<div class="purchase-page">
    <div class="purchase-layout">
        <form method="POST" action="{{ route('purchase.process', $item->id) }}" class="purchase-form">
            @csrf

            <div class="purchase-left">
                <section class="purchase-product-section">
                    <div class="purchase-product-card">
                        <div class="purchase-product-image-wrap">
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="purchase-product-image">
                        </div>

                        <div class="purchase-product-meta">
                            <h1 class="purchase-product-name">{{ $item->name }}</h1>
                            <p class="purchase-product-price">¥{{ number_format($item->price) }}</p>
                        </div>
                    </div>
                </section>

                <section class="purchase-block">
                    <h2 class="purchase-block-title">支払い方法</h2>

                    <select
                        name="payment_method"
                        id="payment_method"
                        class="purchase-select @error('payment_method') is-invalid @enderror">
                        <option value="">選択してください</option>
                        <option value="convenience_store" {{ old('payment_method') === 'convenience_store' ? 'selected' : '' }}>
                            コンビニ支払い
                        </option>
                        <option value="credit_card" {{ old('payment_method') === 'credit_card' ? 'selected' : '' }}>
                            カード支払い
                        </option>
                    </select>

                    @error('payment_method')
                    <p class="purchase-field-error">{{ $message }}</p>
                    @enderror

                    @error('purchase')
                    <p class="purchase-field-error">{{ $message }}</p>
                    @enderror
                </section>

                <section class="purchase-block">
                    <div class="purchase-address-header">
                        <h2 class="purchase-block-title">配送先</h2>
                        <a href="{{ route('purchase.address.edit', $item->id) }}" class="purchase-address-link">変更する</a>
                    </div>

                    @if ($address)
                    <div class="purchase-address-text">
                        <p>〒{{ $address->postal_code }}</p>
                        <p>{{ $address->address }}</p>
                        @if (!empty($address->building))
                        <p>{{ $address->building }}</p>
                        @endif
                    </div>
                    @else
                    <p class="purchase-address-empty">配送先住所が未設定です。</p>
                    @endif
                </section>
            </div>

            <div class="purchase-right">
                <div class="purchase-summary">
                    <div class="purchase-summary-row">
                        <span class="purchase-summary-label">商品代金</span>
                        <span class="purchase-summary-value">¥{{ number_format($item->price) }}</span>
                    </div>

                    <div class="purchase-summary-row">
                        <span class="purchase-summary-label">支払い方法</span>
                        <span class="purchase-summary-value" id="payment-method-display">
                            {{ old('payment_method') === 'credit_card' ? 'カード支払い' : (old('payment_method') === 'convenience_store' ? 'コンビニ支払い' : '選択してください') }}
                        </span>
                    </div>
                </div>

                @if(!$item->is_sold)
                <button type="submit" class="purchase-submit-button">購入する</button>
                @else
                <button type="button" class="purchase-submit-button is-disabled" disabled>購入済み</button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentSelect = document.getElementById('payment_method');
        const paymentDisplay = document.getElementById('payment-method-display');

        if (!paymentSelect || !paymentDisplay) {
            return;
        }

        const labels = {
            '': '選択してください',
            'convenience_store': 'コンビニ支払い',
            'credit_card': 'カード支払い',
        };

        const updatePaymentDisplay = () => {
            paymentDisplay.textContent = labels[paymentSelect.value] ?? '選択してください';
        };

        paymentSelect.addEventListener('change', updatePaymentDisplay);
        updatePaymentDisplay();
    });
</script>
@endsection