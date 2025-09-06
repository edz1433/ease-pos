

<?php $__env->startSection('body'); ?>
<?php echo $__env->make('layouts.formStyle', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-gray mb-0">
                        <b>PRODUCTS</b>
                    </h2>
                </div>
                <div class="card-body">
                    <div class="row transition-row">
                        <!-- Product Form Column -->
                        <div class="col-lg-3">
                            <div class="panel panel-default">
                                <div class="panel-heading">ADD PRODUCT</div>
                                <div class="panel-body bg-form">
                                    <form class="p-2" id="product_form_data" method="POST" action="<?php echo e(isset($productsedit) ? route('productUpdate', $productsedit->id) : route('productCreate')); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <?php if(isset($productsedit)): ?>
                                            <input type="hidden" name="id" value="<?php echo e($productsedit->id); ?>">
                                        <?php endif; ?>

                                        <div class="form-group">
                                            <label for="barcode">Retail Barcode <i class="fas fa-sync" onclick="generateBarcode('barcode')"></i></label>
                                            <input type="text" name="barcode" value="<?php echo e($productsedit->barcode ?? ''); ?>" class="form-control form-control-sm" id="barcode" 
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            onkeydown="if(event.key === 'Enter'){event.preventDefault();}"
                                            required oninput="this.value = this.value.replace(/[^0-9]/g, '')">

                                            <label for="w_barcode">Wholesale Barcode <i class="fas fa-sync" onclick="generateBarcode('w_barcode')"></i></label>
                                            <input type="text" name="w_barcode" value="<?php echo e($productsedit->w_barcode ?? ''); ?>" class="form-control form-control-sm" id="w_barcode" 
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            onkeydown="if(event.key === 'Enter'){event.preventDefault();}"
                                            required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            
                                            <label for="product_name">Product Name</label>
                                            <input type="text" name="product_name" value="<?php echo e($productsedit->product_name ?? ''); ?>" class="form-control form-control-sm" id="product_name" required>

                                            <label for="product_name">Product Type</label>
                                            <select name="product_type" id="product_type" class="form-control form-control-sm" required>
                                                <option value="">-- Select Type --</option>
                                                <option value="1" <?php echo e(($productsedit->product_type ?? '') == '1' ? 'selected' : ''); ?>>Standard</option>
                                                <option value="2" <?php echo e(($productsedit->product_type ?? '') == '2' ? 'selected' : ''); ?>>Made to Order</option>
                                            </select>

                                            <label for="category">Category</label>
                                            <select name="category" id="category" class="form-control form-control-sm" required>
                                                <option value="">-- Select Category --</option>
                                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category->id); ?>" 
                                                        <?php echo e(($productsedit->category ?? '') == $category->id ? 'selected' : ''); ?>>
                                                        <?php echo e($category->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>


                                            <label for="packaging">Packaging</label>
                                            <input type="number" name="packaging" value="<?php echo e($productsedit->packaging ?? ''); ?>" class="form-control form-control-sm" id="packaging" required>

                                             <label for="w_capital">Wholesale Capital</label>
                                            <input type="number" step="0.01" name="w_capital" value="<?php echo e($productsedit->w_capital ?? ''); ?>" class="form-control form-control-sm" id="whole_capital" required>

                                            <label for="w_price">Wholesale Price</label>
                                            <input type="number" step="0.01" name="w_price" value="<?php echo e($productsedit->w_price ?? ''); ?>" class="form-control form-control-sm" id="whole_price" required>

                                            <label for="w_unit">Wholesale Unit</label>
                                            <select name="w_unit" class="form-control form-control-sm" id="wholesale_unit">
                                                <option value="">-- Select Unit --</option>
                                                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($unit->id); ?>" <?php echo e(($productsedit->w_unit ?? '') == $unit->id ? 'selected' : ''); ?>>
                                                        <?php echo e($unit->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>

                                            <label for="r_capital">Retail Capital</label>
                                            <input type="number" step="0.01" name="r_capital" value="<?php echo e($productsedit->r_capital ?? ''); ?>" class="form-control form-control-sm" id="retail_capital" required>

                                            <label for="r_price">Retail Price</label>
                                            <input type="number" step="0.01" name="r_price" value="<?php echo e($productsedit->r_price ?? ''); ?>" class="form-control form-control-sm" id="retail_price" required>

                                            <label for="r_unit">Retail Unit</label>
                                            <select name="r_unit" class="form-control form-control-sm" id="wholesale_unit" required>
                                                <option value="">-- Select Unit --</option>
                                                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($unit->id); ?>" <?php echo e(($productsedit->r_unit ?? '') == $unit->id ? 'selected' : ''); ?>>
                                                        <?php echo e($unit->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>

                                            <label for="r_stock_alert">Retail Stock Alert</label>
                                            <input type="number" step="0.01" name="r_stock_alert" value="<?php echo e($productsedit->r_stock_alert ?? ''); ?>" class="form-control form-control-sm" id="r_stock_alert" required>

                                            <label for="w_stock_alert">Wholesale Stock Alert</label>
                                            <input type="number" step="0.01" name="w_stock_alert" value="<?php echo e($productsedit->w_stock_alert ?? ''); ?>" class="form-control form-control-sm" id="w_stock_alert" required>

                                        </div>

                                        <label for="image">Image</label>
                                        <input type="file" name="image" class="form-control form-control-sm" id="image">
                                        

                                        <button type="submit" class="btn bg-main-7 text-light mt-2 w-100">
                                             <i class="fas fa-save"></i> Save
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Product Table Column -->
                        <div class="col-lg-9">
                            <div class="card">
                                <div class="table-responsive">
                                    <table id="example3" class="table table-bordered table-hover" width="100%">
                                        <thead class="bg-main-9 text-dark">
                                            <tr>
                                                <th>Image</th>
                                                <th>Product</th>
                                                <th>Barcodes</th>
                                                <th>Packaging</th>
                                                <th>Wholesale</th>
                                                <th>Retail</th>
                                                <th class="text-center align-middle">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr id="row-<?php echo e($product->id); ?>">
                                                    <!-- Image -->
                                                    <td class="text-center align-middle">
                                                        <?php if(!empty($product->image) && Storage::disk('public')->exists('uploads/products/' . $product->image)): ?>
                                                            <img src="<?php echo e(asset('storage/uploads/products/' . $product->image)); ?>" 
                                                                width="40" height="40" 
                                                                style="border-radius: 5%;"  
                                                                class="product-image" 
                                                                alt="Product Image">
                                                        <?php endif; ?>
                                                    </td>

                                                    <!-- Product Info -->
                                                    <td class="align-middle">
                                                        <strong class="text-main-8"><?php echo e($product->product_name); ?></strong><br>
                                                        <span class="text-main-1"><?php echo e($product->category_name); ?></span><br>
                                                        <small class="text-main-3">
                                                            <?php echo e($product->product_type == '1' ? 'Standard' : 'Made to Order'); ?>

                                                        </small>
                                                    </td>

                                                    <!-- Barcodes -->
                                                    <td class="align-middle">
                                                        <?php echo e('R-' . $product->barcode); ?><br>
                                                        <?php if($product->w_barcode): ?>
                                                            <?php echo e('W-' . $product->w_barcode); ?>

                                                        <?php endif; ?>
                                                    </td>

                                                    <!-- Packaging -->
                                                    <td class="text-center align-middle"><?php echo e($product->packaging); ?></td>

                                                    <!-- Wholesale -->
                                                    <td class="text-center align-middle">
                                                        <small>Cap: ₱<?php echo e(number_format($product->w_capital, 2)); ?></small><br>
                                                        <small>Price: ₱<?php echo e(number_format($product->w_price, 2)); ?></small><br>
                                                        <small>
                                                            Qty: <?php echo e(number_format($product->wqty)); ?> <?php echo e($product->w_unit_name ?? ''); ?>

                                                            <?php if($product->wqty == 0): ?>
                                                                <span class="badge bg-danger">Out</span>
                                                            <?php elseif($product->wqty < 10): ?>
                                                                <span class="badge bg-warning text-dark">Low</span>
                                                            <?php endif; ?>
                                                        </small>
                                                    </td>

                                                    <!-- Retail -->
                                                    <td class="text-center align-middle">
                                                        <small>Cap: ₱<?php echo e(number_format($product->r_capital, 2)); ?></small><br>
                                                        <small>Price: ₱<?php echo e(number_format($product->r_price, 2)); ?></small><br>
                                                        <small>
                                                            Qty: <?php echo e(number_format($product->rqty)); ?> <?php echo e($product->r_unit_name ?? ''); ?>

                                                            <?php if($product->rqty == 0): ?>
                                                                <span class="badge bg-danger">Out</span>
                                                            <?php elseif($product->rqty < 10): ?>
                                                                <span class="badge bg-warning text-dark">Low</span>
                                                            <?php endif; ?>
                                                        </small>
                                                    </td>

                                                    <!-- Actions -->
                                                    <td class="text-center align-middle">
                                                        <a href="#" class="btn btn-info btn-sm edit-btn" data-id="<?php echo e($product->id); ?>" title="Edit">
                                                            <i class="fas fa-info-circle"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-danger btn-sm delete-row" data-model="Product" data-id="<?php echo e($product->id); ?>" title="Delete" 
                                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                                            <i class="fas fa-trash"></i>
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
<form id="post-form" action="<?php echo e(route('productEdit')); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" id="id">
</form>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".edit-btn").forEach(function (btn) {
            btn.addEventListener("click", function () {
                let userId = this.getAttribute("data-id");
                document.getElementById("id").value = userId;
                document.getElementById("post-form").submit();
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/admin/products/index.blade.php ENDPATH**/ ?>