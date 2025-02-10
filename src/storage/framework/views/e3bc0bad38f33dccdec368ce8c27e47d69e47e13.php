<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/items_create.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main'); ?>
<div class="container">
    <h2 class="text-center mb-4">商品を出品する</h2>
    <!-- エラーメッセージ -->
    <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('items.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <!-- 出品画像 -->
        <div class="form-group mb-3">
            <label for="image">商品画像</label>
            <img src="<?php echo e(Storage::url($item->image_url)); ?>" class="card-img-top" alt="<?php echo e($item->name); ?>">
            <!-- <img src="<?php echo e($item->image_url ? asset($item->image_url) : asset('storage/item_images/no-image.png')); ?>"
                class="card-img-top" alt="<?php echo e($item->name); ?>"> -->
            <!-- <img src="<?php echo e($item->image_url); ?>" class="card-img-top" alt="<?php echo e($item->name); ?>"> -->
            <!-- <img src="<?php echo e(asset($item->image_url)); ?>" class="img-fluid" alt="<?php echo e($item->name); ?>"> -->

            <input type="file" name="image" id="image" class="form-control" accept="image/*" multiple onchange="previewImages(event)">
            <div id="image-preview-container" class="mt-3"></div>
        </div>
        <div class="form-group mb-3">
            <label for="name">商品名</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label for="description">商品の説明</label>
            <textarea id="description" name="description" class="form-control" rows="5" required><?php echo e(old('description')); ?></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="category_id">カテゴリー</label>
            <div id="categories-container">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="category_<?php echo e($category->id); ?>" name="categories[]" value="<?php echo e($category->id); ?>">
                    <label class="form-check-label btn btn-outline-primary" for="category_<?php echo e($category->id); ?>"> <?php echo e($category->name); ?>

                    </label>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="condition">商品の状態</label>
            <select id="condition" name="condition" class="form-control" required>
                <option value="good">良好</option>
                <option value="used_good">目立ったキズなし</option>
                <option value="used_fair">ややキズあり</option>
                <option value="used_bad">状態が悪い</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="price">商品の価格</label>
            <input type="number" name="price" id="price" class="form-control" value="<?php echo e(old('price')); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">出品する</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    function previewImages(event) {
        const previewContainer = document.getElementById('image-preview-container');
        previewContainer.innerHTML = '';

        const files = event.target.files;
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Preview';
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.marginRight = '10px';
                img.style.marginBottom = '10px';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/items/create.blade.php ENDPATH**/ ?>