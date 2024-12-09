<header>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">

        <div class="container">
            <!-- ロゴ -->
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="/images/logo.svg" style="height: 39px;" alt="coachtech">
            </a>
            <!-- カテゴリーセレクト -->
            <div class="ml-3">
                <select class="form-select" style="width: auto;">
                    <option>カテゴリー</option>
                    <option value="1">カテゴリー1</option>
                    <option value="2">カテゴリー2</option>
                </select>
            </div>
            <!-- 検索バー -->
            <div class="mx-3 flex-grow-1">
                <input type="text" class="form-control" placeholder="検索">
            </div>
            <!-- ボタン -->
            <div class="login">
                <a href="#" class="btn btn-primary">会員登録</a>
                <a href="#" class="btn btn-secondary">ログイン</a>
            </div>
            <div>
                <a href="#" class="btn btn-mypage">マイページ</a>
                <a href="#" class="btn btn-sell">出品</a>
            </div>
        </div>
    </nav>
</header>