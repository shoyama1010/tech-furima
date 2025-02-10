<!-- <header> -->
<header class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm"> -->

    <div class="header-container">
        <!-- ロゴ -->
        <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
            <img src="/images/logo.svg" style="height: 39px;" alt="coachtech">
        </a>

        <a href="<?php echo e(route('register')); ?>" class="d-block text-center mt-3">会員登録</a>

        <!-- 登録ユーザーのみ認証チェック -->
        <?php if(Auth::check()): ?>
        <!-- 検索バー -->
        <div class="search-container">
            <form action="<?php echo e(route('items.search')); ?>" method="GET">
                <?php echo csrf_field(); ?>
                <input type="text" class="form-control" placeholder="なにをお探しですか？" value="<?php echo $keyword ?? ''; ?>" name="keyword" />
            </form>
        </div>

        <!-- ボタン -->
        <div class="logout">
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="d-inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-primary">ログアウト</button>
            </form>
            <a href="<?php echo e(route('mypage')); ?>" class="btn btn-secondary">マイページ</a>
            <a href="<?php echo e(url('/items/create')); ?>" class="btn btn-sell">出品</a>
        </div>
        <?php endif; ?>
    </div>
    </nav>
</header><?php /**PATH /var/www/resources/views/components/header.blade.php ENDPATH**/ ?>