

<?php $__env->startSection('body'); ?>
<style>
    .bg-form {
        background-color: #e9ecef;
    }
    .form-control:disabled, .form-control[readonly] {
        background-color: #ffffff;
        opacity: 1;
    }
    .form-control-sm {
        height: calc(1.5125rem + 2px);
        padding: .15rem .5rem;
        font-size: .750rem;
        line-height: 1.5;
        border-radius: .2rem;
        background-color: #ffffff !important;
    }
    .btn-sm {
        font-size: 10px !important;
        height: 25px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .bb {
        border-bottom: 1px solid rgb(145, 138, 138);
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Categories Column -->
        <div class="col-md-6">
            <h2 class="card-title text-gray mb-0">
                <b>CATEGORIES</b>
            </h2>
            <br>
            <!-- Add / Edit Category Form -->
            <form id="categoryForm" method="POST" action="<?php echo e(route('categoriesSave')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" id="categoryId">
                <input type="hidden" name="icon" id="selectedIcon" value="<?php echo e(old('icon', '')); ?>">

                <div class="row g-2 align-items-center">
                    <!-- Category Name -->
                    <div class="col-md-9">
                        <input type="text" name="name" id="categoryName"
                            class="form-control form-control-sm"
                            placeholder="Enter category name" required>
                    </div>

                    <!-- Icon Picker -->
                    <div class="col-md-1">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i id="selectedIconPreview" class="<?php echo e(old('icon', 'fas fa-question')); ?>"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right p-2" style="max-height:220px; overflow:auto;">
                                    <!-- Food & Drinks -->
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-utensils" title="Cooked Dish / Meal"><i class="fas fa-utensils fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-hamburger" title="Burgers"><i class="fas fa-hamburger fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-pizza-slice" title="Pizza"><i class="fas fa-pizza-slice fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-drumstick-bite" title="Chicken / Meat"><i class="fas fa-drumstick-bite fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-ice-cream" title="Ice Cream / Dessert"><i class="fas fa-ice-cream fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-egg" title="Breakfast / Eggs"><i class="fas fa-egg fa-lg"></i></a>

                                    <!-- Beverages -->
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-glass-martini-alt" title="Beverages / Alcohol"><i class="fas fa-glass-martini-alt fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-mug-hot" title="Coffee / Tea"><i class="fas fa-mug-hot fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-wine-bottle" title="Wine"><i class="fas fa-wine-bottle fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-beer" title="Beer"><i class="fas fa-beer fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-blender" title="Juice / Smoothies"><i class="fas fa-blender fa-lg"></i></a>

                                    <!-- Grocery & Snacks -->
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-shopping-basket" title="Grocery"><i class="fas fa-shopping-basket fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-box" title="Snacks / Packaged Food"><i class="fas fa-box fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-apple-alt" title="Fruits"><i class="fas fa-apple-alt fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-carrot" title="Vegetables"><i class="fas fa-carrot fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-fish" title="Seafood"><i class="fas fa-fish fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-bread-slice" title="Bread / Bakery"><i class="fas fa-bread-slice fa-lg"></i></a>
                                    
                                    <!-- Appliances & Electronics -->
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-tv" title="Television"><i class="fas fa-tv fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-laptop" title="Laptop"><i class="fas fa-laptop fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-mobile-alt" title="Mobile / Phone"><i class="fas fa-mobile-alt fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-plug" title="Electronics"><i class="fas fa-plug fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-blender-phone" title="Kitchen Appliances"><i class="fas fa-blender-phone fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-microchip" title="IT / Computer Parts"><i class="fas fa-microchip fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-desktop" title="IT Supplies"><i class="fas fa-desktop fa-lg"></i></a>
                                    
                                    <!-- Household & General -->
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-couch" title="Furniture"><i class="fas fa-couch fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-broom" title="Cleaning Supplies"><i class="fas fa-broom fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-toilet-paper" title="Toiletries"><i class="fas fa-toilet-paper fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-tshirt" title="Clothing"><i class="fas fa-tshirt fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-shoe-prints" title="Footwear"><i class="fas fa-shoe-prints fa-lg"></i></a>
                                    
                                    <!-- Others -->
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-gift" title="Gift / Special Item"><i class="fas fa-gift fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-tags" title="Discount / Offers"><i class="fas fa-tags fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-cash-register" title="Cash Register / POS"><i class="fas fa-cash-register fa-lg"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Submit + Cancel -->
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" id="formSubmitBtn" class="btn btn-success btn-sm w-100 mr-1">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="reset" id="categoryCancelBtn" class="btn btn-secondary btn-sm d-none w-100">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </form>

            <br>

            <!-- Categories Table -->
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Icon</th>
                        <th>Category</th>
                        <th style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center text-main-8 font-weight-bold"><?php echo e($index + 1); ?></td>
                            <td class="text-center text-main-8 font-weight-bold"><i class="<?php echo e($category->icon); ?>"></i></td>
                            <td class="text-main-8 bold font-weight-bold"><?php echo e($category->name); ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-info edit-category"
                                    data-id="<?php echo e($category->id); ?>"
                                    data-icon="<?php echo e($category->icon); ?>"
                                    data-name="<?php echo e($category->name); ?>">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Units Column -->
        <div class="col-md-6">
            <h2 class="card-title text-gray mb-0">
                <b>UNITS</b>
            </h2>
            <br>

            <!-- Add / Edit Unit Form -->
            <form id="addUnitForm" method="POST" action="<?php echo e(route('unitSave')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" id="unitId">

                <div class="row g-2 align-items-center">
                    <!-- Unit Name -->
                    <div class="col-md-10">
                        <input type="text" name="name" id="unitName"
                            class="form-control form-control-sm"
                            placeholder="Enter new unit" required>
                    </div>

                    <!-- Submit + Cancel Buttons -->
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" id="unitSubmitBtn" class="btn btn-success btn-sm w-100 mr-1">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="reset" id="unitCancelBtn" class="btn btn-secondary btn-sm d-none w-100">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </form>
            <br>
            <!-- Units Table -->
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th class="text-center">Unit</th>
                        <th style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                     <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center text-main-8 font-weight-bold"><?php echo e($index + 1); ?></td>
                            <td class="text-center text-main-8 font-weight-bold"><?php echo e($unit->name); ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-info edit-unit"
                                    data-id="<?php echo e($unit->id); ?>"
                                    data-name="<?php echo e($unit->name); ?>">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/admin/products/cassification.blade.php ENDPATH**/ ?>