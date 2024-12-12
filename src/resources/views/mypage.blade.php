@extends('layouts.app')

@section('main')
<div class="container">
    <div class="text-center mb-4">
        <h1>ユーザー名</h1>
        <p>{{ $user->name }}</p>
        <a href="{{ url('/mypage/profile') }}" class="btn btn-outline-primary">プロフィールを編集</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h3>出品した商品</h3>
            <div class="items-container">
                @forelse ($itemsSold as $item)
                <div class="item">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                    <h4>{{ $item->name }}</h4>
                    <p>価格: ¥{{ number_format($item->price) }}</p>
                </div>
                @empty
                <p>出品した商品はありません。</p>
                @endforelse
            </div>
        </div>

        <div class="col-md-6">
            <h3>購入した商品</h3>
            <div class="items-container">
                @forelse ($itemsPurchased as $item)
                <div class="item">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                    <h4>{{ $item->name }}</h4>
                    <p>価格: ¥{{ number_format($item->price) }}</p>
                </div>
                @empty
                <p>購入した商品はありません。</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection