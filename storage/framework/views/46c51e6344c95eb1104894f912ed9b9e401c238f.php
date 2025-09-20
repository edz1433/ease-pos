

<?php $__env->startSection('body'); ?>
<?php echo $__env->make('layouts.formStyle', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<style>
        .product-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }
    .product-row input[type="text"] {
        flex: 1; /* product name takes full available width */
    }
    .product-row input[type="number"] {
        width: 80px; /* quantity fixed width */
    }

</style>
<div class="container-fluid">
    <div class="row">
        <!-- Bundle Info + Add Item -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">Add Product to Bundle</div>
                <div class="card-body bg-form">
                    <form method="POST" action="<?php echo e(route('bundleCreate')); ?>">
                        <?php echo csrf_field(); ?>
                        <label for="barcode">Retail Barcode <i class="fas fa-sync" onclick="generateBarcode('barcode')"></i></label>
                        <input type="text" name="barcode" value="<?php echo e($productsedit->barcode ?? ''); ?>" 
                        class="form-control form-control-sm" id="barcode"
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                        onkeydown="if(event.key === 'Enter'){event.preventDefault();}"
                        required>
                        
                        <!-- Bundle Name -->
                        <div class="form-group mb-2">
                            <label for="bundle_name">Bundle Name</label>
                            <input type="text" name="bundle_name" id="bundle_name" class="form-control form-control-sm" required>
                        </div>

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

                        <!-- Product Selection -->
                        <div class="form-group mb-2">
                            <label for="product_select">Select Products</label>
                            <select id="product_select" class="form-control form-control-sm select2">
                                <option value="">-- Select Product --</option>
                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($product->id); ?>" 
                                            data-name="<?php echo e($product->product_name); ?>" 
                                            data-capital="<?php echo e($product->r_capital); ?>"
                                            data-price="<?php echo e($product->r_price); ?>">
                                        <?php echo e($product->product_name); ?> <?php echo e($product->model); ?> <?php echo e($product->model); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Selected Products (hidden inputs will go here) -->
                        <div id="selected_products_inputs" class="mb-2"></div>

                        <!-- More Details -->
                        <div class="form-group mb-2">
                            <label for="more_details">More Details</label>
                            <textarea name="more_details" id="more_details" class="form-control form-control-sm" rows="2"></textarea>
                        </div>

                        <!-- Capital -->
                        <div class="form-group mb-2">
                            <label for="r_capital">Bundle Capital</label>
                            <input type="number" name="r_capital" id="r_capital" class="form-control form-control-sm" required readonly>
                        </div>

                        <!-- Quantity -->
                        <div class="form-group mb-2">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" id="quantity" min="1" class="form-control form-control-sm" required>
                        </div>

                        <!-- Price -->
                        <div class="form-group mb-2">
                            <label for="bundle_price">Bundle Price</label>
                            <input type="number" name="r_price" id="bundle_price" class="form-control form-control-sm" required readonly>
                        </div>

                        <!-- Stock Alert -->
                        <div class="form-group mb-2">
                            <label for="r_stock_alert">Stock Alert</label>
                            <input type="number" step="0.01" name="r_stock_alert" id="r_stock_alert" class="form-control form-control-sm" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-plus"></i> Create Bundle
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bundle Items Table -->
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">Bundle Items</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" width="100%">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $bundleitems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($item->product_name); ?></td>
                                    <td><?php echo e($item->rqty); ?></td>
                                    <td class="text-center">
                                        <form action="<?php echo e(route('bundleRemoveItem', $item->id)); ?>" method="POST" style="display:inline-block;">
                                            <?php echo csrf_field(); ?>
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#product_select').select2();

    const container = document.getElementById('selected_products_inputs');
    const bundlePriceInput = document.getElementById('bundle_price');
    const bundleCapitalInput = document.getElementById('r_capital');
    const moreDetailsTextarea = document.getElementById('more_details');

    function recalcTotals() {
        let totalPrice = 0;
        let totalCapital = 0;
        let productNames = [];

        container.querySelectorAll('.product-row').forEach(row => {
            const qty = parseFloat(row.querySelector('input[name="rqty[]"]').value) || 0;
            const price = parseFloat(row.querySelector('input[name="product_price[]"]').value) || 0;
            const capital = parseFloat(row.querySelector('input[name="product_capital[]"]').value) || 0;
            const name = row.querySelector('input[name="product_name[]"]').value;

            totalPrice += qty * price;
            totalCapital += qty * capital;
            productNames.push(name);
        });

        bundlePriceInput.value = totalPrice.toFixed(2);
        bundleCapitalInput.value = totalCapital.toFixed(2);
        moreDetailsTextarea.value = productNames.join("\n");
    }

    $('#product_select').on('select2:select', function (e) {
        const selectedOption = e.params.data.element;
        const productId = selectedOption.value;
        const productName = selectedOption.dataset.name;
        const productPrice = selectedOption.dataset.price;
        const productCapital = selectedOption.dataset.capital;

        if (!productId) return;

        // Prevent duplicates
        if (container.querySelector(`.product-row[data-id="${productId}"]`)) {
            alert('Product already added!');
            $(this).val(null).trigger('change'); // reset select2
            return;
        }

        // Create row
        const row = document.createElement('div');
        row.classList.add('product-row', 'd-flex', 'gap-2', 'mb-2');
        row.dataset.id = productId;

        row.innerHTML = `
            <input type="hidden" name="product_id[]" value="${productId}">
            <input type="hidden" name="product_price[]" value="${productPrice}">
            <input type="hidden" name="product_capital[]" value="${productCapital}">
            <input type="text" name="product_name[]" value="${productName}" class="form-control form-control-sm" readonly>
            <input type="number" name="rqty[]" value="1" min="1" class="form-control form-control-sm qty-input" style="width: 80px;">
            <button type="button" class="btn btn-danger btn-sm remove-btn">&times;</button>
        `;

        container.appendChild(row);

        recalcTotals();

        // Reset select2
        $(this).val(null).trigger('change');
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-btn')) {
            e.target.closest('.product-row').remove();
            recalcTotals();
        }
    });

    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('qty-input')) {
            recalcTotals();
        }
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/admin/products/bundles.blade.php ENDPATH**/ ?>