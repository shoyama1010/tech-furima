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
            <p><strong>価格(税込):</strong> ¥{{ number_format($item->price) }}</p>

            <!-- いいね機能 -->
            <div class="like-section">
                <strong>いいね数:</strong>
                <span id="like-count-{{ $item->id }}">{{ $item->likes->count() }}</span>
                <button class="like-btn" data-id="{{ $item->id }}">
                    <!-- {{ $item->likes->contains('user_id', auth()->id()) ? '★' : '☆' }} -->
                    {{ $item->likes()->where('user_id', auth()->id())->exists() ? '★' : '☆' }}
                </button>
            </div>

            <!-- コメント表示 -->
            <p><strong>コメント💭:</strong> {{ $item->comments->count() ?? 0 }}</p>
            
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
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".like-btn").forEach(button => {
            button.addEventListener("click", function() {
                let itemId = this.dataset.itemId;
                console.log("クリックした商品ID:", itemId); // ★デバッグ用

                fetch(`/items/${itemId}/toggle-like`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        credentials: "same-origin",
                        body: JSON.stringify({})
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(text);
                            });
                        }
                        return response.json();
                    })

                    .then(data => {
                        console.log("レスポンス:", data); // ★デバッグ用

                        let likeCountElement = document.getElementById(`like-count-${itemId}`);

                        if (data.liked) { // いいねが追加された
                            button.classList.add("liked");
                            button.innerHTML = `いいね: ${data.like_count} ★`;
                        } else { // いいねが解除された
                            button.classList.remove("liked");
                            button.innerHTML = `いいね: ${data.like_count} ☆`;
                        }

                        if (likeCountElement) {
                            likeCountElement.innerText = data.like_count;
                        }
                    })
                    .catch(error => console.error("エラー:", error));
            });
        });
    });
</script>
@endsection
