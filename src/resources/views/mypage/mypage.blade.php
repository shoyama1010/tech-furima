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

    <!-- タブメニュー -->
    <ul class="nav nav-tabs" id="mypageTabs">
        <li class="nav-item">
            <a class="nav-link active" id="sold-tab" data-toggle="tab" href="#soldItems">出品した商品</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="purchased-tab" data-toggle="tab" href="#purchasedItems">購入した商品</a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- 出品した商品 -->
        <div class="tab-pane fade show active" id="soldItems">
            <h3>出品した商品</h3>
            <div class="items-container">
                @forelse ($itemsSold as $item)

                <div class="item">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="img-fluid">
                    <h4 class="mt-2">{{ $item->name }}</h4>
                    <p><strong>価格:</strong> ¥{{ number_format($item->price) }}</p>
                </div>

                @empty
                <p class="text-center">出品した商品はありません。</p>
                @endforelse
            </div>
        </div>

        <div class="tab-pane fade" id="purchasedItems">
            <h3>購入した商品</h3>
            <div class="items-container">
                @forelse ($itemsPurchased as $item)
                <div class="item">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="img-fluid">
                    <h4 class="mt-2">{{ $item->name }}</h4>
                    <p><strong>価格:</strong> ¥{{ number_format($item->price) }}</p>
                </div>
                @empty
                <p class="text-center">購入した商品はありません。</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Bootstrapのタブ機能を有効にする
    $(document).ready(function() {
        $('#mypageTabs a').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
@endsection