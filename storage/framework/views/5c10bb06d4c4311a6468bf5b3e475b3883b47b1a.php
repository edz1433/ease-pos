

<?php $__env->startSection('body'); ?>
<style>
    .bg-form{
        background-color:  #e9ecef;
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
    .btn-sm{
        font-size: 10px !important;
        height: 25px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .bb{
        border-bottom: 1px solid rgb(145, 138, 138);
    }

    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-gray">
                        <b>PRODUCT LIST</b>
                    </h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Product Form Column -->
                        <div class="col-lg-3 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">ADD PRODUCT</div>
                                <div class="panel-body bg-form">
                                    <form class="p-2" id="product_form_data" method="POST" action="<?php echo e(isset($productedit) ? route('productUpdate', $productedit->id) : route('productCreate')); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <?php if(isset($productedit)): ?>
                                            <?php echo method_field('PUT'); ?>
                                            <input type="hidden" name="id" value="<?php echo e($productedit->id); ?>">
                                        <?php endif; ?>

                                        <div class="form-group">
                                            <label for="barcode">Barcode <i class="fas fa-sync" onclick="generateBarcode()"></i></label>
                                            <input type="text" name="barcode" value="<?php echo e($productedit->barcode ?? ''); ?>" class="form-control form-control-sm" id="barcode" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            
                                            <label for="product_name">Product Name</label>
                                            <input type="text" name="product_name" value="<?php echo e($productedit->product_name ?? ''); ?>" class="form-control form-control-sm" id="product_name" required>

                                            <label for="product_name">Product Type</label>
                                            <select name="product_type" id="product_type" class="form-control form-control-sm" required>
                                                <option value="">-- Select Type --</option>
                                                <option value="1" <?php echo e(($productedit->product_type ?? '') == '1' ? 'selected' : ''); ?>>Standard</option>
                                                <option value="2" <?php echo e(($productedit->product_type ?? '') == '2' ? 'selected' : ''); ?>>Made to Order</option>
                                            </select>

                                            <label for="category">Category</label>
                                            <select name="category" id="category" class="form-control form-control-sm" required>
                                                <option value="">-- Select Category --</option>
                                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category->id); ?>" 
                                                        <?php echo e(($productedit->category ?? '') == $category->id ? 'selected' : ''); ?>>
                                                        <?php echo e($category->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>


                                            <label for="packaging">Packaging</label>
                                            <input type="number" name="packaging" value="<?php echo e($productedit->packaging ?? ''); ?>" class="form-control form-control-sm" id="packaging" required>

                                             <label for="w_capital">Wholesale Capital</label>
                                            <input type="number" step="0.01" name="w_capital" value="<?php echo e($productedit->w_capital ?? ''); ?>" class="form-control form-control-sm" id="whole_capital" required>

                                            <label for="w_price">Wholesale Price</label>
                                            <input type="number" step="0.01" name="w_price" value="<?php echo e($productedit->w_price ?? ''); ?>" class="form-control form-control-sm" id="whole_price" required>

                                            <label for="w_unit">Wholesale Unit</label>
                                            <select name="w_unit" class="form-control form-control-sm" id="wholesale_unit">
                                                <option value="">-- Select Unit --</option>
                                                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($unit->id); ?>" <?php echo e(($productedit->w_unit ?? '') == $unit->id ? 'selected' : ''); ?>>
                                                        <?php echo e($unit->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>

                                            <label for="r_capital">Retail Capital</label>
                                            <input type="number" step="0.01" name="r_capital" value="<?php echo e($productedit->r_capital ?? ''); ?>" class="form-control form-control-sm" id="retail_capital" required>

                                            <label for="r_price">Retail Price</label>
                                            <input type="number" step="0.01" name="r_price" value="<?php echo e($productedit->r_price ?? ''); ?>" class="form-control form-control-sm" id="retail_price" required>

                                            <label for="r_unit">Retail Unit</label>
                                            <select name="r_unit" class="form-control form-control-sm" id="wholesale_unit" required>
                                                <option value="">-- Select Unit --</option>
                                                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($unit->id); ?>" <?php echo e(($productedit->r_unit ?? '') == $unit->id ? 'selected' : ''); ?>>
                                                        <?php echo e($unit->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        <label for="image">Image</label>
                                        <input type="file" name="image" class="form-control form-control-sm" id="image" <?php echo e(isset($productedit) ? '' : 'required'); ?>>
                                        

                                        <button type="submit" class="btn btn-success mt-2">
                                            <i class="glyphicon glyphicon-save"></i> Save
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Product Table Column -->
                        <div class="col-lg-9 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">PRODUCT LIST</div>
                                <div class="panel-body">
                                    <table id="example3" class="table table-bordered table-hover" width="100%">
                                        <thead class="bg-main-9 text-white">
                                            <tr>
                                                <th>Image</th>
                                                <th>Barcode</th>
                                                <th>Product Name</th>
                                                <th>Category</th>
                                                <th>Type</th>
                                                <th>Packaging</th>
                                                <th>W. Capital</th>
                                                <th>W. Price</th>
                                                <th>W. Unit</th>
                                                <th>R. Capital</th>
                                                <th>R. Price</th>
                                                <th>R. Unit</th>
                                                <th>Retail Qty</th>
                                                <th>Wholesale Qty</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <img src="<?php echo e(asset('storage/' . $product->image)); ?>" class="product-image" alt="Product Image">
                                                    </td>
                                                    <td class="text-main-10"><?php echo e($product->barcode); ?></td>
                                                    <td class="text-main-8"><?php echo e($product->product_name); ?></td>
                                                    <td class="text-main-1"><?php echo e($product->category); ?></td>
                                                    <td class="text-main-3">
                                                        <?php echo e($product->product_type == '1' ? 'Retail & Wholesale' : 'Retail Only'); ?>

                                                    </td>
                                                    <td><?php echo e($product->packaging); ?></td>
                                                    <td>₱<?php echo e(number_format($product->w_capital, 2)); ?></td>
                                                    <td>₱<?php echo e(number_format($product->w_price, 2)); ?></td>
                                                    <td><?php echo e($product->w_unit ?? '-'); ?></td>
                                                    <td>₱<?php echo e(number_format($product->r_capital, 2)); ?></td>
                                                    <td class="text-main-4">₱<?php echo e(number_format($product->r_price, 2)); ?></td>
                                                    <td><?php echo e($product->r_unit ?? '-'); ?></td>
                                                    <td class="text-main-7"><?php echo e(number_format($product->rqty)); ?></td>
                                                    <td class="text-main-6"><?php echo e(number_format($product->wqty)); ?></td>
                                                    <td class="text-center">
                                                        <a href="<?php echo e(route('productEdit', $product->id)); ?>" class="text-main-10" title="Edit">
                                                            <i class="fas fa-edit fa-lg"></i>
                                                        </a>
                                                        <a href="#" class="text-main" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
                                                            <i class="fas fa-trash-alt fa-lg"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="post-form" action="<?php echo e(route('userEdit')); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" id="user-id">
</form>
<script>
    function editPostForm(id) {
        document.getElementById('user-id').value = id;

        document.getElementById('post-form').submit();
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/admin/products/index.blade.php ENDPATH**/ ?>