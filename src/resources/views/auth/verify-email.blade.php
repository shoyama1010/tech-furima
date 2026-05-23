{{-- src/resources/views/auth/verify-email.blade.php --}}
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/email_verify.css') }}">
@endsection

@section('main')
<div class="verify-page">
    <div class="verify-container">
        <p class="verify-message">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        <div class="verify-action">
            <a
                href="http://localhost:8025"
                target="_blank"
                rel="noopener noreferrer"
                class="verify-mailhog-button">
                認証はこちらから
            </a>
        </div>

        @if (session('success'))
        <p class="verify-success-message">
            {{ session('success') }}
        </p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="verify-resend-form">
            @csrf
            <button type="submit" class="verify-resend-button">
                認証メールを再送する
            </button>
        </form>
    </div>
</div>
@endsection