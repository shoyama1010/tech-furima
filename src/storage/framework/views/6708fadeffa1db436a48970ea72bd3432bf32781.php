<?php $__env->startSection('main'); ?>
<div class="container">
    <!-- メール認証 -->
    <?php if(Route::has('verification.notice') && !auth()->user()->hasVerifiedEmail()): ?>
    <div class="alert alert-warning text-center">
        メール認証が必要です。確認メールをご確認ください。
        <!-- <a href="<?php echo e(route('verification.notice')); ?>" class="btn btn-primary btn-sm">確認ページへ</a> -->
        <a href="<?php echo e(url('/email/verify')); ?>" class="btn btn-primary btn-sm">確認ページへ</a>
    </div>
    <?php endif; ?> 

     <!-- 確認用のメッセージ  -->
    <?php if(session('message')): ?>
    <div class="alert alert-success">
        <?php echo e(session('message')); ?>

    </div>
    <?php endif; ?>

    <!-- ユーザー情報セクション -->
    <div class="user-info text-center mb-4">
        <img src="<?php echo e($user->profile_image ? asset('storage/' . $user->profile_image) : '/images/default-profile.png'); ?>" alt="プロフィール画像" class="rounded-circle" width="100" height="100">
        <h1>ユーザー名</h1>
        <p><?php echo e($user->name); ?></p>
        <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-primary mt-2">プロフィールを編集</a>
    </div>

    <!-- タブメニュー -->
    <ul class="nav nav-tabs" id="mypageTabs">
        <li class="nav-item">
            <a class="nav-link active" id="sold-tab" data-toggle="tab" href="#soldItems">出品した商品</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="purchased-tab" data-toggle="tab" href="#purchasedItems">購入した商品</a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- 出品した商品 -->
        <div class="tab-pane fade show active" id="soldItems">
            <h3>出品した商品</h3>
            <div class="items-container">
                <?php $__empty_1 = true; $__currentLoopData = $itemsSold; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                <div class="item">
                    <img src="<?php echo e($item->image_url); ?>" alt="<?php echo e($item->name); ?>" class="img-fluid">
                    <h4 class="mt-2"><?php echo e($item->name); ?></h4>
                    <p><strong>価格:</strong> ¥<?php echo e(number_format($item->price)); ?></p>
                </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center">出品した商品はありません。</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="purchasedItems">
            <h3>購入した商品</h3>
            <div class="items-container">
                <?php $__empty_1 = true; $__currentLoopData = $itemsPurchased; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="item">
                    <img src="<?php echo e($item->image_url); ?>" alt="<?php echo e($item->name); ?>" class="img-fluid">
                    <h4 class="mt-2"><?php echo e($item->name); ?></h4>
                    <p><strong>価格:</strong> ¥<?php echo e(number_format($item->price)); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center">購入した商品はありません。</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Bootstrapのタブ機能を有効にする
    $(document).ready(function() {
        $('#mypageTabs a').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/mypage/mypage.blade.php ENDPATH**/ ?>