@extends('layouts.app')

@section('main')
<div class="container">
    <!-- 商品画像部分 -->
    <div class="image-container">
        <!-- <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}" class="img-fluid"> -->
        <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
    </div>

    <!-- 商品情報 -->
    <div class="details-container">
        <h2>{{ $item->name }}</h2>
        <p><strong>ブランド名:</strong> {{ $item->brand }}</p>
        <p><strong>価格:</strong> ¥{{ number_format($item->price) }}</p>
        <p><strong>いいね数:</strong> {{ $item->likes_count ?? 0 }}</p>
        <p><strong>コメント数:</strong> {{ $item->comments->count() }}</p>
        <a href="#" class="btn btn-danger">購入手続きへ</a>
        <p><strong>商品説明:</strong> {{ $item->description }}</p>
        <p><strong>カテゴリ:</strong> {{ $item->category->name ?? 'なし' }}</p>
        <p><strong>状態:</strong> {{ $item->condition }}</p>

        <!-- コメント -->
        <div class="comments-section">
            <!-- コメント履歴 -->
            <div class="comments-list">
                <h3>コメント ({{ $item->comments->count() }})</h3>
                @foreach ($item->comments as $comment)
                <div class="comment">
                    <p><strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}</p>
                    <p class="text-muted"><small>{{ $comment->created_at->format('Y-m-d H:i') }}</small></p>
                </div>
                @endforeach
            </div>

            <h3>商品へのコメント</h3>
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <textarea name="content" class="form-control" placeholder="コメントを入力"></textarea>
                <button type="submit" class="btn btn-primary mt-2">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection