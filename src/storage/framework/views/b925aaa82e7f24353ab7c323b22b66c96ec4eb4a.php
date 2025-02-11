<!-- <?php $__env->startSection('content'); ?> -->
<?php $__env->startSection('main'); ?>
<div class="container">
    <div class="email">
        <h1>メールアドレス認証</h1>
        <p>確認メールが送信されました。メールを確認して認証リンクをクリックしてください。</p>

        <a href="http://localhost:8025" target="_blank" class="d-block text-center mt-3">認証リンクへ</a>
    </div>

    <?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('verification.send')); ?>">
        <?php echo csrf_field(); ?>
        <p>メールが届かない場合は、以下のボタンをクリックして再送してください。</p>
        <button type="submit" class="btn btn-primary">認証メールを再送する</button>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/auth/verify-email.blade.php ENDPATH**/ ?>