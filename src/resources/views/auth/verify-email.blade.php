@extends('layouts.app')

<!-- @section('content') -->
@section('main')
<div class="container">
    <h1>メールアドレス認証</h1>
    <p>確認メールが送信されました。メールを確認して認証リンクをクリックしてください。</p>
   
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <p>メールが届かない場合は、以下のボタンをクリックして再送してください。</p>
        <button type="submit" class="btn btn-primary">認証メールを再送する</button>
    </form>
</div>
@endsection