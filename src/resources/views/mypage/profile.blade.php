@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('main')
<div class="container">
    <h2 class="text-center mb-4">プロフィール設定</h2>
    <form method="POST" action="{{ route('user-profile-information.update') }}">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : '/images/default-profile.png' }}" alt="プロフィール画像" class="rounded-circle" width="100" height="100">
            <input type="file" name="profile_image" id="profile_image" class="form-control">
        </div>
        <div class="form-group mb-3">
            <label for="name">ユーザー名</label>
            <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" class="form-control">
        </div>
        <div class="form-group mb-3">
            <label for="address">住所</label>
            <textarea id="address" name="address" class="form-control"></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="building">建物</label>
            <textarea id="building" name="building" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">更新する</button>
    </form>
</div>
@endsection