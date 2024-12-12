@extends('layouts.app')

@section('main')

<div class="container">
    <div class="row mb-4">
        <div class="col text-center">
            <a href="#" class="btn btn-link active">おすすめ商品</a>
        </div>

        <div class="col text-center">
            <a href="#" class="btn btn-link">マイリスト</a>
        </div>

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
</div>
@endsection