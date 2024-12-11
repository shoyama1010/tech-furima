@extends('layouts.app')

@section('main')

<div class="main">
    <h2>おすすめ商品</h2>
    <h2>マイリスト</h2>
    <div class="items-container">
        @foreach ($items as $item)
        <div class="item">
            <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
            <h2>{{ $item->name }}</h2>
            <p>{{ $item->description }}</p>
            <p><strong>価格:</strong> ¥{{ number_format($item->price) }}</p>
            <p><strong>状態:</strong> {{ $item->condition }}</p>
        </div>
        @endforeach
    </div>
</div>
@endsection