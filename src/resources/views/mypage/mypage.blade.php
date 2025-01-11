@extends('layouts.app')

@section('main')
<div class="container">
    <!-- ユーザー情報セクション -->
    <div class="user-info text-center mb-4">
        
        <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : '/images/default-profile.png' }}" alt="プロフィール画像" class="rounded-circle" width="100" height="100">
        <h1>ユーザー名</h1>
        <p>{{ $user->name }}</p>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-2">プロフィールを編集</a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <!-- <div class="items-container"> -->
        <h3>出品した商品</h3>
        <div class="items-container d-flex flex-wrap">

            @forelse ($itemsSold as $item)
            <div class="item-card border m-2 p-2" style="width: 45%;">

                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="img-fluid">
                <!-- <h3>{{ $item->name }}</h> -->
                <h4 class="mt-2">{{ $item->name }}</h4>
                <p><strong>価格:</strong> ¥{{ number_format($item->price) }}</p>
            </div>
            @empty
            <p>出品した商品はありません。</p>
            @endforelse
        </div>
    </div>

    <div class="col-md-6">
        <h3>購入した商品</h3>
        <div class="items-container d-flex flex-wrap">

            @forelse ($itemsPurchased as $item)
            <div class="item-card border m-2 p-2" style="width: 35%;">
                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="img-fluid">
                <h4 class="mt-2">{{ $item->name }}</h4>
                <p><strong>価格:</strong> ¥{{ number_format($item->price) }}</p>
            </div>
            @empty
            <p>購入した商品はありません。</p>
            @endforelse
        </div>
    </div>
</div>
</div>
@endsection