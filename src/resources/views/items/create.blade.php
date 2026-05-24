@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items_create.css') }}">
@endsection

@section('main')
<div class="sell-page">
    <div class="sell-container">
        <h1 class="sell-title">商品の出品</h1>

        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            <section class="sell-section">
                <h2 class="sell-section-title">商品画像</h2>
                <p class="sell-note">※ アップロードできる画像は3MB以下です。</p>

                <div class="sell-image-upload-box @error('image') is-invalid @enderror">
                    <img
                        id="image-preview"
                        src="{{ !empty($item->image_url) ? Storage::url($item->image_url) : asset('images/no-image.png') }}"
                        alt="{{ $item->name ?? '' }}"
                        class="sell-image-preview">

                    <label for="image" class="sell-image-select-button">画像を選択する</label>

                    <input
                        type="file"
                        name="image"
                        id="image"
                        class="sell-file-input"
                        accept="image/*"
                        onchange="previewImage(event)">
                </div>

                @error('image')
                <p class="sell-field-error">{{ $message }}</p>
                @enderror
            </section>

            <section class="sell-section">
                <h2 class="sell-section-heading">商品の詳細</h2>

                <div class="sell-form-group">
                    <label for="categories" class="sell-label">カテゴリー</label>

                    <div class="sell-category-list @error('categories') is-invalid @enderror @error('categories.*') is-invalid @enderror">
                        @foreach ($categories as $category)
                        <div class="sell-category-item">
                            <input
                                class="sell-category-input"
                                type="checkbox"
                                id="category_{{ $category->id }}"
                                name="categories[]"
                                value="{{ $category->id }}"
                                {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                            <label class="sell-category-label" for="category_{{ $category->id }}">
                                {{ $category->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>

                    @error('categories')
                    <p class="sell-field-error">{{ $message }}</p>
                    @enderror

                    @error('categories.*')
                    <p class="sell-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sell-form-group">
                    <label for="condition" class="sell-label">商品の状態</label>

                    <select id="condition" name="condition" class="sell-input @error('condition') is-invalid @enderror" required>
                        <option value="">選択してください</option>
                        <option value="good" {{ old('condition') === 'good' ? 'selected' : '' }}>良好</option>
                        <option value="used_good" {{ old('condition') === 'used_good' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                        <option value="used_fair" {{ old('condition') === 'used_fair' ? 'selected' : '' }}>やや傷や汚れあり</option>
                        <option value="used_bad" {{ old('condition') === 'used_bad' ? 'selected' : '' }}>状態が悪い</option>
                    </select>

                    @error('condition')
                    <p class="sell-field-error">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <section class="sell-section">
                <h2 class="sell-section-heading">商品名と説明</h2>

                <div class="sell-form-group">
                    <label for="name" class="sell-label">商品名</label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="sell-input @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        required>

                    @error('name')
                    <p class="sell-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sell-form-group">
                    <label for="description" class="sell-label">商品の説明</label>

                    <textarea
                        id="description"
                        name="description"
                        class="sell-textarea @error('description') is-invalid @enderror"
                        required>{{ old('description') }}</textarea>

                    @error('description')
                    <p class="sell-field-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sell-form-group">
                    <label for="price" class="sell-label">販売価格</label>

                    <div class="sell-price-input-wrap">
                        <span class="sell-price-prefix">￥</span>
                        <input
                            type="number"
                            name="price"
                            id="price"
                            class="sell-input sell-price-input @error('price') is-invalid @enderror"
                            value="{{ old('price') }}"
                            min="0"
                            required>
                    </div>

                    @error('price')
                    <p class="sell-field-error">{{ $message }}</p>
                    @enderror
                </div>
            </section>

            <button type="submit" class="sell-submit-button">出品する</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.previewImage = function(event) {
            const file = event.target.files && event.target.files[0];
            const preview = document.getElementById('image-preview');

            if (!file || !preview) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        };
    });
</script>
@endpush