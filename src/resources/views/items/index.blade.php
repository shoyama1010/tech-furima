// src/resources/views/items/index.blade.php
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('main')
<div class="items-page">
    <div class="items-header">
        <div class="items-tabs">
            <a href="{{ url('/?page=recommend') }}" class="items-tab {{ $viewType === 'recommend' ? 'is-active' : '' }}">
                おすすめ
            </a>
            <a href="{{ url('/?page=mylist') }}" class="items-tab {{ $viewType === 'mylist' ? 'is-active' : '' }}">
                マイリスト
            </a>
        </div>
    </div>

    <div class="items-content">
        @if($items->isNotEmpty())
        <div class="items-grid">
            @foreach ($items as $item)
            <a href="{{ route('items.detail', $item->id) }}" class="item-card">
                <div class="item-card-image-wrap">
                    <img
                        src="{{ $item->image_url }}"
                        class="item-card-image"
                        alt="{{ $item->name }}">
                </div>
                <p class="item-card-name">{{ $item->name }}</p>
            </a>
            @endforeach
        </div>
        @else
        <p class="items-empty">現在、表示する商品がありません。</p>
        @endif
    </div>
</div>
@endsection