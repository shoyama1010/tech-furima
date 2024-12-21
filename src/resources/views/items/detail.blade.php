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

        <!-- いいね機能 -->
        <div class="like-section">
            <!-- <p><strong>いいね数:</strong> {{ $item->likes_count ?? 0 }}</p> -->
            <strong>いいね数:</strong>
            <span id="like-count">{{ $item->likes->count() }}</span>
            <button id="like-button" class="btn btn-outline-secondary">
                {{ $item->likes->contains('user_id', auth()->id()) ? '★' : '☆' }}
            </button>
        </div>

        <!-- コメント表示 -->
        <p><strong>コメント数:</strong> {{ $item->comments->count() }}</p>
        <a href="{{ route('purchase.show', $item->id) }}" class="btn btn-danger">購入手続きへ</a>
        <p><strong>商品説明:</strong> {{ $item->description }}</p>

        <!-- カテゴリ表示 -->
        <p><strong>カテゴリ:</strong>
            @if ($item->category)
                <span class="badge bg-primary">{{ $item->category->name }}</span>
            @else
                なし
            @endif
        </p>

        <!-- コンディション状態 -->
        <p><strong>状態:</strong> {{ $item->condition }}</p>

        <!-- コメント処理 -->
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



<script>
    document.getElementById('like-button').addEventListener('click', function() {
        fetch(`/items/{{ $item->id }}/toggle-like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('like-count').textContent = data.like_count;
                this.textContent = data.status === 'liked' ? '★' : '☆';
            });
    });
</script>

<style>
    .category-badge {
        display: inline-block;
        margin-right: 10px;
        padding: 5px 10px;
        background-color: #f0f0f0;
        border-radius: 5px;
        font-size: 14px;
        color: #333;
    }

    .like-section {
        margin-bottom: 10px;
    }

    .like-section button {
        font-size: 20px;
        color: #f15b5b;
        background: none;
        border: none;
        cursor: pointer;
    }

    .like-section button:focus {
        outline: none;
    }
</style>
@endsection