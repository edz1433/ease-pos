

<?php $__env->startSection('body'); ?>
<div class="container-fluid cashier-body">
    <div class="row">
        <!-- Left: Menu -->
        <div class="col-md-12">
            <!-- Category Buttons -->
            <?php
                $categories = [
                    ['label' => 'Hot', 'icon' => 'fas fa-fire'],
                    ['label' => 'Burger', 'icon' => 'fas fa-hamburger'],
                    ['label' => 'Pizza', 'icon' => 'fas fa-pizza-slice'],
                    ['label' => 'Snack', 'icon' => 'fas fa-box'],
                    ['label' => 'Soft Drink', 'icon' => 'fas fa-cocktail'],
                    ['label' => 'Coffee', 'icon' => 'fas fa-mug-hot'],
                    ['label' => 'Ice Cream', 'icon' => 'fas fa-ice-cream'],
                ];
            ?>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button class="category-btn <?php echo e($index === 0 ? 'active' : ''); ?>">
                        <i class="<?php echo e($category['icon']); ?>"></i>
                        <div class="label"><?php echo e($category['label']); ?></div>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>


        <!-- Scrollable Menu Items -->
        <div class="menu-scroll">
            <div class="row"> 
                <?php for($i = 0; $i < 30; $i++): ?>
                <div class="col-12 col-md-4 col-lg-3 mb-4">
                    <div class="menu-item">
                        <img src="<?php echo e(asset('template/img/burger.png')); ?>" alt="Food">
                        <div class="mt-2 font-weight-bold">
                             <?php echo e(['Pizza', 'Chicken', 'Burger', 'Chips'][$i % 4]); ?>

                        </div>
                        <button class="btn btn-sm btn-primary primary-radius w-100 mt-2 button-price d-flex justify-content-between align-items-center">
                            <span>₱300.00</span>
                            <span class="circle-icon bg-white text-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 24px; height: 24px;">
                                <i class="fas fa-plus"></i>
                            </span>
                        </button>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>


        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master-cashier', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ease-pos\resources\views/cashier/index.blade.php ENDPATH**/ ?>