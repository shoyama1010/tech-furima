<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?></title>
    <!-- <title>商品一覧</title> -->
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <!--カスタムCSS -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/item.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/mypage.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/items_create.css')); ?>">
    <!-- !-- プロフィール関連CSS -->
    <link href="<?php echo e(asset('css/profile.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/detail.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/address.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/purchase.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">
    <!-- 共通CSSを読み込み -->
    <link rel="stylesheet" href="<?php echo e(asset('css/common.css')); ?>">
</head>

<body>
    <div id="app">
        <!-- ヘッダーコンポーネント -->
        <?php $__env->startComponent('components.header'); ?>
        <?php echo $__env->renderComponent(); ?>
        <!-- メインコンテンツ -->
        <main class="py-4">
            <?php echo $__env->yieldContent('main'); ?>
        </main>
        <!-- jQuery（BootstrapのJSより先に） -->
        <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- BootstrapのJS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>

</html><?php /**PATH /var/www/resources/views/layouts/app.blade.php ENDPATH**/ ?>