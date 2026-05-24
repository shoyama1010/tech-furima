@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('main')
<div class="mypage-page">
    <div class="mypage-container">

        <section class="mypage-profile">
            <div class="mypage-profile-left">
                <div class="mypage-profile-image-wrap">
                    <img id="profile_image_preview"
                        src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : '/images/default-profile.png' }}"
                        alt="プロフィール画像"
                        class="rounded-circle"
                        width="100" height="100">
                </div>
                <div class="mypage-profile-info">
                    <h1 class="mypage-user-name">{{ $user->name }}</h1>
                </div>
            </div>
            
            <div class="mypage-profile-right">
                <a href="{{ url('/mypage/profile') }}" class="mypage-edit-button">プロフィールを編集</a>

            </div>
        </section>

        <div class="mypage-tabs">
            <a
                href="{{ route('mypage', ['tab' => 'sell']) }}"
                class="mypage-tab {{ request('tab', 'sell') === 'sell' ? 'is-active' : '' }}">
                出品した商品
            </a>
            <a
                href="{{ route('mypage', ['tab' => 'buy']) }}"
                class="mypage-tab {{ request('tab') === 'buy' ? 'is-active' : '' }}">
                購入した商品
            </a>
        </div>
        @php
        $activeTab = request('tab', 'sell');
        $displayItems = $activeTab === 'buy' ? $itemsPurchased : $itemsSold;
        @endphp

        <section class="mypage-items-section">
            <div class="mypage-items-grid">
                @forelse ($displayItems as $item)
                <a href="{{ route('items.detail', $item->id) }}" class="mypage-item-card">
                    <div class="mypage-item-image-wrap">
                        <img
                            src="{{ $item->image_url }}"
                            alt="{{ $item->name }}"
                            class="mypage-item-image">
                    </div>
                    <p class="mypage-item-name">{{ $item->name }}</p>
                </a>
                @empty
                <p class="mypage-empty-message">商品はまだありません。</p>
                @endforelse
            </div>
        </section>

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