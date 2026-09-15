<header class="site-header">
    <div class="site-header__inner">
        <a class="site-header__logo" href="{{ url('/') }}">
            <!-- <img src="{{ asset('images/logo.svg') }}" alt="coachtech"> -->
            <span class="site-header__logo-icon">T</span>
            <span class="site-header__logo-tech">Tech</span>
            <span class="site-header__logo-furima">furima</span>
        </a>

        @if(Auth::check())
        <div class="site-header__search">
            <form action="{{ route('items.search') }}" method="GET">
                <input
                    type="text"
                    name="keyword"
                    class="site-header__search-input"
                    placeholder="なにをお探しですか？"
                    value="{{ $keyword ?? '' }}">
            </form>
        </div>

        <nav class="site-header__nav">
            <form method="POST" action="{{ route('logout') }}" class="site-header__logout-form">
                @csrf
                <button type="submit" class="site-header__link-button">ログアウト</button>
            </form>

            <a href="{{ route('mypage') }}" class="site-header__link">マイページ</a>

            <a href="{{ url('/items/create') }}" class="site-header__sell-button">出品</a>
        </nav>
        @else
        <div class="site-header__search">
            <form action="{{ route('items.search') }}" method="GET">
                <input
                    type="text"
                    name="keyword"
                    class="site-header__search-input"
                    placeholder="なにをお探しですか？"
                    value="{{ $keyword ?? '' }}">
            </form>
        </div>

        <nav class="site-header__nav">
            <a href="{{ route('login') }}" class="site-header__link">ログイン</a>
            <a href="{{ route('register') }}" class="site-header__link">会員登録</a>
            <a href="{{ url('/items/create') }}" class="site-header__sell-button">出品</a>
        </nav>
        @endif
    </div>
</header>


