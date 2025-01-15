@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('main')
<div class="container">
    <div class="row mb-4">
        <!-- タブ切り替え -->
        <div class="col text-center">
            <a href="{{ url('/?page=recommend') }}" class="btn btn-link active">おすすめ商品</a>
        </div>
        <div class="col text-center">
            <a href="{{ url('/?page=mylist') }}" class="btn btn-link">マイリスト</a>
        </div>
        <div class="items-container">
            @if($items->isNotEmpty())
            @foreach ($items as $item)
            @if ($item) <!-- nullチェックを追加 -->

            <div class="item">
                <!-- 商品画像 -->
                <a href="{{ route('items.detail', $item->id) }}">
                    @if (!empty($item->image_url)) <!-- image_urlの存在をチェック -->
                    <!-- <img src="{{ asset('storage/' . $item->image_url) }}"
                        class="card-img-top" alt="{{ $item->name }}"> -->
                    <img src="{{ $item->image_url }}"
                        class="card-img-top" alt="{{ $item->name }}">
                    @else
                    <!-- <img src="{{ $item->image_url }}" alt="{{ $item->name }}"> -->
                    <img src="{{ asset('images/no-image.png') }}" alt="No Image">
                    <!-- デフォルト画像 -->
                    @endif
                </a>

                <div class="item-details">
                    <!-- 商品情報 -->
                    <h5 class="item-title">{{ $item->name }}</h5>

                    <p class="item-description">{{ Str::limit($item->description, 100) }}</p>

                    <p class="item-price">価格: ¥{{ number_format($item->price) }}</p>

                    <p class="item-status">
                        状態: {{ $item->is_sold ? 'SOLD' : '出品中' }}
                    </p>

                    <a href="{{ route('items.detail', $item->id) }}" class="btn btn-primary">詳細を見る</a>
                </div>
            </div>
            @endif
            @endforeach
            @else
            <p class="text-center">現在、表示する商品がありません。</p>
            @endif
        </div>
    </div>
</div>
@endsection