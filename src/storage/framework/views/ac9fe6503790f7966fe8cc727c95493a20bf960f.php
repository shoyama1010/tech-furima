<?php $__env->startSection('title', '会員登録'); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main'); ?>
<div class="auth-container">

    <h2 class="text-center mb-4">会員登録</h2>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('register')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group mb-3">    
            <label for="name">ユーザー名</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label for="password_confirmation">確認用パスワード</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">登録する</button>
        <a href="<?php echo e(route('login')); ?>" class="d-block text-center mt-3">ログインはこちら</a>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/auth/register.blade.php ENDPATH**/ ?>