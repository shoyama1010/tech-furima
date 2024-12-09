<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <title>商品一覧</title>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            background-color: black;
            color: white;
        }

        .item {
            border: 1px solid #ddd;
            margin: 10px;
            padding: 10px;
            border-radius: 5px;
            width: 300px;
        }

        .item img {
            max-width: 100%;
            height: auto;
        }

        .items-container {
            display: flex;
            flex-wrap: wrap;
        }
    </style>
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