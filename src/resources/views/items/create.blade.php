@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items_create.css') }}">
@endsection

@section('main')
<div class="container">
    <h2 class="text-center mb-4">商品を出品する</h2>
    <!-- エラーメッセージ -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="image">商品画像</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">

            <!-- プレビュー -->
            @if(isset($item) && $item->image_url)
            <div class="mt-3">
                <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}" class="img-fluid">
            </div>
            @endif
        </div>

        <div class="form-group mb-3">
            <label for="name">商品名</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="description">商品の説明</label>
            <textarea id="description" name="description" class="form-control" rows="5" required></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="category_id">カテゴリー</label>
            <select id="category_id" name="category_id" class="form-control" required>
            <!-- <select name="categories[]" id="categories" class="form-control" required> -->
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="condition">商品の状態</label>
            <select id="condition" name="condition" class="form-control" required>
                <option value="new">良好</option>
                <option value="new">目立ったキズなし</option>
                <option value="used">ややキズあり</option>
                <option value="used">状態が悪い</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="condition">商品の価格</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">出品する</button>
    </form>
</div>
@endsection