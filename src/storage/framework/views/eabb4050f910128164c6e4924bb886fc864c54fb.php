<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/items.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main'); ?>
<div class="container">

    <div class="row mb-4">
        <!-- タブ切り替え -->
        <div class="col text-center">
            <a href="<?php echo e(url('/?page=recommend')); ?>" class="btn btn-link active">おすすめ商品</a>
        </div>
        <div class="col text-center">
            <a href="<?php echo e(url('/?page=mylist')); ?>" class="btn btn-link">マイリスト</a>
        </div>

        <div class="items-container">
            <?php if($items->isNotEmpty()): ?>
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="item">
                <!-- 商品画像 -->
                <a href="<?php echo e(route('items.detail', $item->id)); ?>">

                    <img src="<?php echo e(asset($item->image_url ?? 'images/no-image.png')); ?>"
                        class="card-img-top"
                        alt="<?php echo e($item->name); ?>">
                    <!-- <img src="<?php echo e($item->image_url ?? asset('images/no-image.png')); ?>" class="card-img-top" alt="<?php echo e($item->name); ?>"> -->

                    <!-- <img src="<?php echo e(asset('storage/' . $item->image_url) ?? asset('images/no-image.png')); ?>"
                    class="card-img-top"
                    alt="<?php echo e($item->name); ?>"> -->
                </a>

                <div class="item-details">
                    <!-- 商品情報 -->
                    <h5 class="item-title"><?php echo e($item->name); ?></h5>

                    <p class="item-description"><?php echo e(Str::limit($item->description, 100)); ?></p>

                    <p class="item-price">価格: ¥<?php echo e(number_format($item->price)); ?></p>

                    <p class="item-status">
                        状態: <?php echo e($item->is_sold ? 'SOLD' : '出品中'); ?>

                    </p>
                    <a href="<?php echo e(route('items.detail', $item->id)); ?>" class="btn btn-primary">詳細を見る</a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
            <p class="text-center">現在、表示する商品がありません。</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/items/index.blade.php ENDPATH**/ ?>