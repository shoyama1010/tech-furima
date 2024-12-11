@extends('layouts.app')

@section('main')
<div class="container">
    <h2 class="text-center mb-4">ログイン</h2>
    
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group mb-3">
            <label for="email">ユーザー名 / メールアドレス</label>
            <input type="text" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">ログインする</button>
        
        <a href="{{ route('register') }}" class="d-block text-center mt-3">会員登録はこちら</a>
    </form>
</div>
@endsection