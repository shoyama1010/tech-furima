<header>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">

        <div class="header-container">
            <!-- ロゴ -->
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="/images/logo.svg" style="height: 39px;" alt="coachtech">
            </a>

            <!-- 登録ユーザーのみ認証チェック -->
            @if(Auth::check())
            <!-- 検索バー -->
            <div class="mx-3 flex-grow-1">
                <!-- <form class="flex" action="/search" method="GET"> -->
                <form action="{{ route('items.search') }}" method="GET" class="d-flex mx-3 flex-grow-1">
                    @csrf
                    <input type="text" class="form-control" placeholder="なにをお探しですか？" value="{!! $keyword ?? '' !!}" name="keyword" />
                    <!-- <input type="submit" class="search__btn" value="検索" /> -->
                </form>
            </div>

            <!-- ボタン -->
            <div class="logout">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">ログアウト</button>
                </form>
                <a href="{{ route('mypage')}}" class="btn btn-secondary">マイページ</a>
                <a href="{{ url('/items/create') }}" class="btn btn-sell">出品</a>
            </div>

            @endif
        </div>
    </nav>
</header>