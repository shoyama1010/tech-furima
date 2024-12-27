{{-- resources/views/search.blade.php --}}
@extends('layouts.app')

@section('main')
<div class="container">
    <h1>検索結果</h1>
    <p>検索ワード: <strong>{{ $keyword }}</strong></p>

    @if($items->isEmpty())
    <p>該当する商品が見つかりませんでした。</p>
    @else
    <div class="row">
        @foreach($items as $item)
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="{{ $item->image_url }}" class="card-img-top" alt="{{ $item->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $item->name }}</h5>
                    <p class="card-text">{{ $item->description }}</p>
                    <p><strong>価格:</strong> ¥{{ number_format($item->price) }}</p>
                    <a href="{{ route('items.detail', $item->id) }}" class="btn btn-primary">詳細を見る</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- ページネーション -->
    {{ $items->links() }}
</div>
@endsection