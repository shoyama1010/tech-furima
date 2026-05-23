// src/resources/views/items/detail.blade.php
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('main')
<div class="item-detail-page">
    <div class="item-detail-wrap">
        <div class="item-detail-grid">
            <div class="item-image-area">
                <div class="item-image-box">
                    <img
                        src="{{ $item->image_url }}"
                        alt="{{ $item->name }}"
                        class="item-detail-image">
                </div>
            </div>

            <div class="item-info-area">
                <h1 class="item-name">{{ $item->name }}</h1>

                @if(!empty($item->brand))
                <p class="item-brand">{{ $item->brand }}</p>
                @endif

                <p class="item-price">¥ {{ number_format($item->price) }}</p>

                <div class="item-meta-icons">
                    <button
                        type="button"
                        id="like-button"
                        data-item-id="{{ $item->id }}"
                        data-liked="{{ ($likedByMe ?? false) ? '1' : '0' }}"
                        class="meta-icon-button like-button {{ ($likedByMe ?? false) ? 'is-liked' : '' }}">
                        <span class="meta-icon">{{ ($likedByMe ?? false) ? '♥' : '♡' }}</span>
                        <span id="like-count" class="meta-count">{{ $item->likes_count }}</span>
                    </button>

                    <div class="meta-icon-button comment-display">
                        <span class="meta-icon">💬</span>
                        <span class="meta-count">{{ $item->comments_count ?? $item->comments->count() }}</span>
                    </div>
                </div>

                <div class="purchase-area">
                    <a href="{{ route('purchase.show', $item->id) }}" class="purchase-button">
                        購入手続きへ
                    </a>
                </div>

                <section class="info-section">
                    <h2 class="section-title">商品説明</h2>
                    <p class="section-text">{{ $item->description }}</p>
                </section>

                <section class="info-section">
                    <h2 class="section-title">商品の情報</h2>

                    <div class="info-row">
                        <div class="info-label">カテゴリー</div>
                        <div class="info-value category-list">
                            @if ($item->categories->isNotEmpty())
                            @foreach ($item->categories as $category)
                            <span class="category-badge">{{ $category->name }}</span>
                            @endforeach
                            @else
                            <span class="category-badge">未設定</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">商品の状態</div>
                        <div class="info-value">
                            @php
                            $conditionLabels = [
                            'good' => '良好',
                            'used_good' => '目立った傷や汚れなし',
                            'used_fair' => 'やや傷や汚れあり',
                            'used_bad' => '状態が悪い',
                            ];
                            @endphp
                            {{ $conditionLabels[$item->condition] ?? $item->condition }}
                        </div>
                    </div>
                </section>

                <section class="comment-section">
                    <h2 class="section-title">コメント ({{ $item->comments_count ?? $item->comments->count() }})</h2>

                    <div class="comment-list">
                        @forelse ($item->comments as $comment)
                        <div class="comment-card">
                            <div class="comment-user">
                                <span class="comment-user-icon">{{ mb_substr($comment->user->name, 0, 1) }}</span>
                                <span class="comment-user-name">{{ $comment->user->name }}</span>
                            </div>
                            <div class="comment-body">{{ $comment->content }}</div>
                        </div>
                        @empty
                        <p class="empty-comment">まだコメントはありません。</p>
                        @endforelse
                    </div>

                    <div class="comment-form-area">
                        <h3 class="comment-form-title">商品へのコメント</h3>

                        @if (session('success'))
                        <p class="comment-success-message">{{ session('success') }}</p>
                        @endif

                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">

                            <textarea
                                name="content"
                                class="comment-textarea"
                                placeholder="コメントを入力">{{ old('content') }}</textarea>

                            @error('content')
                            <p class="comment-error-message">{{ $message }}</p>
                            @enderror

                            @error('item_id')
                            <p class="comment-error-message">{{ $message }}</p>
                            @enderror

                            <button type="submit" class="comment-submit-button">コメントを送信する</button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const likeButton = document.getElementById('like-button');
        const likeCount = document.getElementById('like-count');

        if (!likeButton || !likeCount) {
            return;
        }

        likeButton.addEventListener('click', async function() {
            const itemId = likeButton.dataset.itemId;

            try {
                const response = await fetch(`/items/${itemId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (response.status === 401) {
                    alert('ログインが必要です');
                    return;
                }

                if (!response.ok) {
                    throw new Error('いいねの更新に失敗しました');
                }

                const data = await response.json();

                likeButton.dataset.liked = data.liked ? '1' : '0';
                likeButton.classList.toggle('is-liked', !!data.liked);
                likeButton.querySelector('.meta-icon').textContent = data.liked ? '♥' : '♡';
                likeCount.textContent = data.likes_count;
            } catch (error) {
                alert(error.message);
            }
        });
    });
</script>
@endpush