@extends('layouts.app')

@section('main')
<div class="container">
    <div class="product-detail">

        <!-- 商品画像部分 -->
        <div class="image-container">
            <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
        </div>
        <!-- 商品情報 -->
        <div class="details-container">
            <h2>{{ $item->name }}</h2>
            <p><strong>ブランド名:</strong> {{ $item->brand }}</p>
            <p><strong>価格:</strong> ¥{{ number_format($item->price) }}</p>

            <!-- いいね機能 -->
            <div class="like-section">
                <strong>いいね数:</strong>
                <span id="like-count">{{ $item->likes->count() }}</span>
                <button id="like-button" class="btn btn-outline-secondary">
                    {{ $item->likes->contains('user_id', auth()->id()) ? '★' : '☆' }}
                </button>
            </div>
            <!-- コメント表示 -->
            <p><strong>コメント💭:</strong> {{ $item->comments->count() }}</p>
            <div class="purchase-btn">
                <a href="{{ route('purchase.show', $item->id) }}" class="btn btn-danger">購入手続きへ</a>
            </div>

            <div class="product-info">
                <h3>商品説明</h3>
                <p>{{ $item->description }}</p>

                <!-- カテゴリ表示 -->
                <h4>カテゴリ:</h4>
                @if ($item->categories->isNotEmpty())
                @foreach ($item->categories as $category)
                <span class="badge bg-primary">{{ $category->name }}</span>
                @endforeach
                @else
                <span class="badge bg-secondary">カテゴリ未設定</span>
                @endif

                <!-- コンディション状態 -->
                <h4>商品の状態:</h4>
                <p>{{ $item->condition }}</p>
            </div>

            <!-- コメント処理 -->
            <div class="comments-section">
                <!-- コメント履歴 -->
                <h3>コメント ({{ $item->comments->count() }})</h3>
                @foreach ($item->comments as $comment)
                <div class="comment">
                    <span class="user-icon">{{ substr($comment->user->name, 0, 1) }}</span>
                    <p><strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}</p>
                    <p class="text-muted"><small>{{ $comment->created_at->format('Y-m-d H:i') }}</small></p>
                    <!-- <p><strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}</p>
                        <p class="text-muted"><small>{{ $comment->created_at->format('Y-m-d H:i') }}</small></p> -->
                </div>
                @endforeach

                <h3>商品へのコメント</h3>
                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <div class="content">
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <textarea name="content" class="comment-input" placeholder="コメントを入力"></textarea>
                        <button type="submit" class="btn btn-primary mt-2">コメントを送信する</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<!-- @endsection -->

<!-- @section('scripts') -->
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
@endsection