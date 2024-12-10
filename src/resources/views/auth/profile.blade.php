@extends('layouts.app')

@section('main')
<div class="container">
    <h2 class="text-center mb-4">プロフィール設定</h2>
    <form method="POST" action="{{ route('user-profile-information.update') }}">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="name">ユーザー名</label>
            <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="phone">電話番号</label>
            <input type="text" id="phone" name="phone" class="form-control">
        </div>
        <div class="form-group mb-3">
            <label for="address">住所</label>
            <textarea id="address" name="address" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">更新する</button>
    </form>
</div>
@endsection