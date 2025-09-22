

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
                                <div class="panel-heading" id="formHeading">ADD PRODUCT</div>
                                <div class="panel-body bg-form">
                                    <form class="p-2" id="product_form_data" method="POST" action="<?php echo e(route('productCreate')); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" id="product_id">

                                        <div class="form-group">
                                            <label for="barcode">Retail Barcode <i class="fas fa-sync" onclick="generateBarcode('barcode')"></i></label>
                                            <input type="text" name="barcode" class="form-control form-control-sm" id="barcode"
                                            oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                                            onkeydown="if(event.key === 'Enter'){event.preventDefault();}"
                                            required>

                                            <label for="w_barcode">Wholesale Barcode 
                                                <i class="fas fa-sync" onclick="generateBarcode('w_barcode')"></i>
                                            </label>
                                            <input type="text" name="w_barcode" class="form-control form-control-sm" id="w_barcode"
                                                oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                                                onkeydown="if(event.key === 'Enter'){event.preventDefault();}">
                                            
                                            <label for="product_name">Product Name</label>
                                            <input type="text" name="product_name" class="form-control form-control-sm" id="product_name" required>

                                            <label for="model">Model</label>
                                            <input type="text" name="model" class="form-control form-control-sm" id="model" required>

                                            <label for="more_details">More Details</label>
                                            <textarea name="more_details" rows="2" class="form-control form-control-sm" id="more_details"></textarea>

                                            <label for="product_name">Product Type</label>
                                            <select name="product_type" id="product_type" class="form-control form-control-sm" required>
                                                <option value="">-- Select Type --</option>
                                                <option value="1">Standard</option>
                                                <option value="2">Made to Order</option>
                                            </select>

                                            <label for="category">Category</label>
                                            <select name="category" id="category" class="form-control form-control-sm" required>
                                                <option value="">-- Select Category --</option>
                                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>

                                            <label for="packaging">Packaging</label>
                                            <input type="number" name="packaging" class="form-control form-control-sm" id="packaging" required>

                                             <label for="w_capital">Wholesale Capital</label>
                                            <input type="number" step="0.01" name="w_capital" class="form-control form-control-sm" id="whole_capital" required>

                                            <label for="w_price">Wholesale Price</label>
                                            <input type="number" step="0.01" name="w_price" class="form-control form-control-sm" id="whole_price" required>

                                            <label for="w_unit">Wholesale Unit</label>
                                            <select name="w_unit" class="form-control form-control-sm" id="wholesale_unit">
                                                <option value="">-- Select Unit --</option>
                                                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>

                                            <label for="r_capital">Retail Capital</label>
                                            <input type="number" step="0.01" name="r_capital" class="form-control form-control-sm" id="retail_capital" required>

                                            <label for="r_price">Retail Price</label>
                                            <input type="number" step="0.01" name="r_price" class="form-control form-control-sm" id="retail_price" required>

                                            <label for="r_unit">Retail Unit</label>
                                            <select name="r_unit" class="form-control form-control-sm" id="retail_unit" required>
                                                <option value="">-- Select Unit --</option>
                                                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>

                                            <label for="r_stock_alert">Retail Stock Alert</label>
                                            <input type="number" step="0.01" name="r_stock_alert" class="form-control form-control-sm" id="r_stock_alert" required>

                                            <label for="w_stock_alert">Wholesale Stock Alert</label>
                                            <input type="number" step="0.01" name="w_stock_alert" class="form-control form-control-sm" id="w_stock_alert" required>

                                        </div>

                                        <label for="image">Image</label>
                                        <input type="file" name="image" class="form-control form-control-sm" id="image">
                                        

                                        <button type="submit" class="btn bg-main-7 text-light mt-2 w-100" id="saveBtn">
                                             <i class="fas fa-save"></i> Save Product
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
                                                <th>Sold</th>
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
                                                    <td class="align-middle text-main-8">
                                                        <?php echo e('R-' . $product->barcode); ?><br>
                                                        <?php if($product->w_barcode): ?>
                                                            <?php echo e('W-' . $product->w_barcode); ?>

                                                        <?php endif; ?>
                                                    </td>

                                                    <!-- Packaging -->
                                                    <td class="text-center align-middle text-main-8"><?php echo e($product->packaging); ?></td>

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

                                                    <td class="text-left align-middle text-main-1">
                                                        R-<?php echo e(number_format($product->total_sold_r) ?? 0); ?><br>
                                                        W-<?php echo e(number_format($product->total_sold_w) ?? 0); ?>

                                                    </td>

                                                    <!-- Actions -->
                                                    <td class="text-center align-middle">
                                                        <a href="javascript:void(0)" 
                                                        class="btn btn-warning btn-sm adjust-stock-btn" 
                                                        data-id="<?php echo e($product->id); ?>" 
                                                        data-name="<?php echo e($product->product_name); ?> <?php echo e($product->model); ?>"
                                                        title="Adjust Stock">
                                                        <i class="fas fa-cubes"></i>
                                                        </a>
                                                        <button class="btn btn-info btn-sm edit-btn" 
                                                            data-id="<?php echo e($product->id); ?>"
                                                            data-barcode="<?php echo e($product->barcode); ?>"
                                                            data-w_barcode="<?php echo e($product->w_barcode); ?>"
                                                            data-product_name="<?php echo e($product->product_name); ?>"
                                                            data-model="<?php echo e($product->model); ?>"
                                                            data-more_details="<?php echo e($product->more_details); ?>"
                                                            data-product_type="<?php echo e($product->product_type); ?>"
                                                            data-category="<?php echo e($product->category); ?>"
                                                            data-packaging="<?php echo e($product->packaging); ?>"
                                                            data-w_capital="<?php echo e($product->w_capital); ?>"
                                                            data-w_price="<?php echo e($product->w_price); ?>"
                                                            data-w_unit="<?php echo e($product->w_unit); ?>"
                                                            data-r_capital="<?php echo e($product->r_capital); ?>"
                                                            data-r_price="<?php echo e($product->r_price); ?>"
                                                            data-r_unit="<?php echo e($product->r_unit); ?>"
                                                            data-r_stock_alert="<?php echo e($product->r_stock_alert); ?>"
                                                            data-w_stock_alert="<?php echo e($product->w_stock_alert); ?>"
                                                            title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <a href="#" class="btn btn-danger btn-sm delete-row" data-model="Product" data-id="<?php echo e($product->id); ?>" title="Delete">
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

<!-- Stock Adjustment Modal -->
<div class="modal fade" id="stockAdjustmentModal" tabindex="-1" role="dialog" aria-labelledby="stockAdjustmentLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <form id="stockAdjustmentForm" method="POST" action="<?php echo e(route('stockAdjustmentCreate')); ?>">
        <?php echo csrf_field(); ?>
        <div class="modal-header">
          <h5 class="modal-title" id="stockAdjustmentLabel">Stock Adjustment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="product_id" id="adjustment_product_id">
        <div class="form-group">
            <p id="adjustment_product_name" class="form-control-plaintext font-weight-bold"></p>
        </div>
          <div class="form-group">
            <label for="adjustment_type">Adjustment Type</label>
            <select name="adjustment_type" id="adjustment_type" class="form-control" required>
              <option value="">-- Select --</option>
              <option value="restock">Restock</option>
              <option value="sale">Sale</option>
              <option value="return">Return</option>
              <option value="damage">Damaged</option>
              <option value="expired">Expired</option>
              <option value="lost">Lost</option>
              <option value="adjustment">Manual Adjustment</option>
              <option value="inventory">Inventory Count</option>
            </select>
          </div>

          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" step="0.01" name="quantity" id="quantity" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="price_type">Price Type</label>
            <select name="price_type" id="price_type" class="form-control">
              <option value="retail">Retail</option>
              <option value="wholesale">Wholesale</option>
              <option value="special">Special</option>
            </select>
          </div>

          <div class="form-group">
            <label for="reason">Reason</label>
            <select name="reason" id="reason" class="form-control">
              <option value="">-- Select Reason --</option>
              <option value="Customer Return">Customer Return</option>
              <option value="Damaged">Damaged</option>
              <option value="Expired">Expired</option>
              <option value="Lost">Lost</option>
              <option value="Stock Count">Stock Count</option>
              <option value="Manual Adjustment">Manual Adjustment</option>
              <option value="Others">Others</option>
            </select>
          </div>

          <div class="form-group" id="saleIdGroup" style="display: none;">
            <label for="sale_id">Sale ID (if return)</label>
            <input type="number" name="sale_id" id="sale_id" class="form-control">
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn bg-main-7 text-light">Save Adjustment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Show Sale ID only when reason is Customer Return
    document.getElementById('reason').addEventListener('change', function () {
        if (this.value === 'Customer Return') {
            document.getElementById('saleIdGroup').style.display = 'block';
        } else {
            document.getElementById('saleIdGroup').style.display = 'none';
        }
    });

    // Open modal with product_id
    document.querySelectorAll(".adjust-stock-btn").forEach(function(button) {
        button.addEventListener("click", function() {
            let productId = this.getAttribute("data-id");
            let productName = this.getAttribute("data-name");
            document.getElementById("adjustment_product_id").value = productId;
            document.getElementById("adjustment_product_name").textContent = productName;
            $('#stockAdjustmentModal').modal('show');
        });
    });

    // Prefill form for editing
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = document.getElementById('product_form_data');
            const productId = this.dataset.id;

            // Fill form fields
            document.getElementById('product_id').value = productId;
            document.getElementById('barcode').value = this.dataset.barcode;
            document.getElementById('w_barcode').value = this.dataset.w_barcode;
            document.getElementById('product_name').value = this.dataset.product_name;
            document.getElementById('model').value = this.dataset.model;
            document.getElementById('more_details').value = this.dataset.more_details;
            document.getElementById('product_type').value = this.dataset.product_type;
            document.getElementById('category').value = this.dataset.category;
            document.getElementById('packaging').value = this.dataset.packaging;
            document.getElementById('whole_capital').value = this.dataset.w_capital;
            document.getElementById('whole_price').value = this.dataset.w_price;
            document.getElementById('wholesale_unit').value = this.dataset.w_unit;
            document.getElementById('retail_capital').value = this.dataset.r_capital;
            document.getElementById('retail_price').value = this.dataset.r_price;
            document.getElementById('retail_unit').value = this.dataset.r_unit;
            document.getElementById('r_stock_alert').value = this.dataset.r_stock_alert;
            document.getElementById('w_stock_alert').value = this.dataset.w_stock_alert;

            // Update form action for update route
            form.action = "<?php echo e(route('productUpdate', ':id')); ?>".replace(':id', productId);
            form.querySelector('#saveBtn').innerHTML = '<i class="fas fa-save"></i> Update Product';
            document.getElementById('formHeading').textContent = 'EDIT PRODUCT';
            
            // Scroll to form
            document.getElementById('product_form_data').scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Reset form when clicking on "Add Product" (if you add such a button)
    // You might want to add a "Add New Product" button to clear the form
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/admin/products/index.blade.php ENDPATH**/ ?>