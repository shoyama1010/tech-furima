@extends('layouts.app')

@section('main')
<div class="container">

    <!-- フォーム -->
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <!-- プロフィール画像 -->
        <div class="profile">
            <h1>プロフィール設定</h1>
            <div class="form-group">
                <label for="profile_image">プロフィール画像</label>

                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : '/images/default_profile.png' }}" alt="プロフィール画像">
                <input type="file" name="profile_image" id="profile_image" class="form-control">
            </div>
        </div>

        <div class="setting input">
            <!-- ユーザー名 -->
            <div class="form-group">
                <label for="name">ユーザー名</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <!-- 電話番号 -->
            <div class="form-group">
                <label for="postal_code">郵便番号</label>
                <input type="text" name="postal_code" id="postal_code" class="form-control" value="{{ old('postal_code', $user->postal_code) }}">
            </div>
            <!-- 住所 -->
            <div class="form-group">
                <label for="address">住所</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $user->address) }}">
            </div>
            <!-- 建物 -->
            <div class="form-group">
                <label for="building">建物名</label>
                <input type="text" name="building" id="building" class="form-control" value="{{ old('building', $user->building) }}">
            </div>
            <!-- 保存ボタン -->
            <button type="submit" class="btn btn-primary mt-3">更新する</button>
        </div>
    </form>
</div>
@endsection