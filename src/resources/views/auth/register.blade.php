@extends('layouts.app')

<!-- @section('title', '会員登録') -->
@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('main')
<div class="auth-container">

    <h2 class="text-center mb-4">会員登録</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group mb-3">    
            <label for="name">ユーザー名</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="password_confirmation">確認用パスワード</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">登録する</button>
        <a href="{{ route('login') }}" class="d-block text-center mt-3">ログインはこちら</a>
    </form>
</div>
@endsection