<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <title>商品一覧</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- 共通CSSを読み込み -->
    <!-- <link rel="stylesheet" href="{{ asset('css/common.css') }}"> -->
    <!--カスタムCSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/item.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/items_create.css') }}">
    <!-- !-- プロフィール関連CSS -->
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <!-- jQuery（BootstrapのJSより先に） -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- BootstrapのJS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
</head>

<body>
    <div id="app">
        <!-- ヘッダーコンポーネント -->
        @component('components.header')
        @endcomponent
        <!-- メインコンテンツ -->
        <main class="py-4">
            @yield('main')
        </main>
    </div>
</body>

</html>