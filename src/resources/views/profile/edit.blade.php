@extends('layouts.app')

@section('main')
<div class="container">
    <h1>プロフィール編集</h1>

    <!-- フォーム -->
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')

        <!-- ユーザー名 -->
        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <!-- 電話番号 -->
        <div class="form-group">
            <label for="phone">電話番号</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
        </div>

        <!-- 住所 -->
        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $user->address) }}">
        </div>

        <!-- プロフィール画像 -->
        <div class="form-group">
            <label for="profile_image">プロフィール画像</label>
            <input type="file" name="profile_image" id="profile_image" class="form-control">
        </div>

        <!-- 保存ボタン -->
        <button type="submit" class="btn btn-primary mt-3">更新する</button>
    </form>
</div>
@endsection