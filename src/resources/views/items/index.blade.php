@extends('layouts.app')

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
            @foreach ($items as $item)
            <div class="item">
                <a href="{{ route('items.detail',$item->id) }}">

                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                </a>
                <h3>{{ $item->name }}</h3>
                <p>{{ $item->description }}</p>
                <p><strong>価格:</strong> ¥{{ number_format($item->price) }}</p>
                <p><strong>状態:</strong> {{ $item->condition }}</p>
                @if ($item->status == 'sell')
                出品中
                @elseif ($item->status == 'sold')
                Sold
                @endif
                <!-- @if($item->is_sold)
                <span class="badge bg-danger">SOLD</span>
                @else -->
                <a href="{{ route('purchase.show', $item->id) }}" class="btn btn-primary">購入手続きへ</a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection