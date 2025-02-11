<?php $__env->startSection('main'); ?>
<div class="container">
    <div class="product-detail">

        <!-- 商品画像部分 -->
        <div class="image-container">
            <img src="<?php echo e($item->image_url); ?>" alt="<?php echo e($item->name); ?>">
        </div>
        <!-- 商品情報 -->
        <div class="details-container">
            <h2><?php echo e($item->name); ?></h2>
            <p><strong>ブランド名:</strong> <?php echo e($item->brand); ?></p>
            <p><strong>価格(税込):</strong> ¥<?php echo e(number_format($item->price)); ?></p>

            <!-- いいね機能 -->
            <div class="like-section">
                <strong>いいね数:</strong>
                <span id="like-count-<?php echo e($item->id); ?>"><?php echo e($item->likes->count()); ?></span>
                <button class="like-btn" data-id="<?php echo e($item->id); ?>">
                    <!-- <?php echo e($item->likes->contains('user_id', auth()->id()) ? '★' : '☆'); ?> -->
                    <?php echo e($item->likes()->where('user_id', auth()->id())->exists() ? '★' : '☆'); ?>

                </button>
            </div>

            <!-- コメント表示 -->
            <p><strong>コメント💭:</strong> <?php echo e($item->comments->count() ?? 0); ?></p>
            <div class="purchase-btn">
                <a href="<?php echo e(route('purchase.show', $item->id)); ?>" class="btn btn-danger">購入手続きへ</a>
            </div>

            <div class="product-info">
                <h3>商品説明</h3>
                <p><?php echo e($item->description); ?></p>

                <!-- カテゴリ表示 -->
                <h4>カテゴリ:</h4>
                <?php if($item->categories->isNotEmpty()): ?>
                <?php $__currentLoopData = $item->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="badge bg-primary"><?php echo e($category->name); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                <span class="badge bg-secondary">カテゴリ未設定</span>
                <?php endif; ?>

                <!-- コンディション状態 -->
                <h4>商品の状態:</h4>
                <p><?php echo e($item->condition); ?></p>
            </div>

            <!-- コメント処理 -->
            <div class="comments-section">
                <!-- コメント履歴 -->
                <h3>コメント (<?php echo e($item->comments->count()); ?>)</h3>
                <?php $__currentLoopData = $item->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="comment">
                    <span class="user-icon"><?php echo e(substr($comment->user->name, 0, 1)); ?></span>
                    <p><strong><?php echo e($comment->user->name); ?></strong>: <?php echo e($comment->content); ?></p>
                    <p class="text-muted"><small><?php echo e($comment->created_at->format('Y-m-d H:i')); ?></small></p>

                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <h3>商品へのコメント</h3>
                <form action="<?php echo e(route('comments.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="content">
                        <input type="hidden" name="item_id" value="<?php echo e($item->id); ?>">
                        <textarea name="content" class="comment-input" placeholder="コメントを入力"></textarea>
                        <button type="submit" class="btn btn-primary mt-2">コメントを送信する</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".like-btn").forEach(button => {
            button.addEventListener("click", function() {
                let itemId = this.dataset.itemId;
                console.log("クリックした商品ID:", itemId); // ★デバッグ用

                fetch(`/items/${itemId}/toggle-like`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        credentials: "same-origin",
                        body: JSON.stringify({})
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(text);
                            });
                        }
                        return response.json();
                    })

                    .then(data => {
                        console.log("レスポンス:", data); // ★デバッグ用

                        let likeCountElement = document.getElementById(`like-count-${itemId}`);

                        if (data.liked) { // いいねが追加された
                            button.classList.add("liked");
                            button.innerHTML = `いいね: ${data.like_count} ★`;
                        } else { // いいねが解除された
                            button.classList.remove("liked");
                            button.innerHTML = `いいね: ${data.like_count} ☆`;
                        }

                        if (likeCountElement) {
                            likeCountElement.innerText = data.like_count;
                        }
                    })
                    .catch(error => console.error("エラー:", error));
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/items/detail.blade.php ENDPATH**/ ?>