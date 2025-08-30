

<?php $__env->startSection('body'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<style>
    .btn-sm { font-size: 10px !important; height: 25px !important; padding: 0 .5rem !important; }
    .table th, .table td { font-size: 12px; vertical-align: middle; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body table-responsive p-0" style="height: 500px;">
                    <div class="table-responsive">
                        <div class="card-tools mb-1">
                            <div class="input-group input-group-sm">
                                <input type="text" id="table-search" name="table_search" class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <table id="inventory-table" class="table table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Barcode</th>
                                    <th>Product Name</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Capital</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $inventoryItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($item->barcode); ?></td>
                                    <td><?php echo e($item->product_name); ?></td>
                                    <td><?php echo e(ucfirst($item->price_type)); ?></td>
                                    <td>
                                        <input type="number" min="0" class="form-control form-control-sm qty-input" 
                                            data-id="<?php echo e($item->id); ?>" data-type="<?php echo e($item->price_type); ?>" 
                                            value="<?php echo e($item->price_type == 'retail' ? $item->r_qty : $item->w_qty); ?>" <?php echo e(($inventory->status != 1) ? 'readonly' : ''); ?>>
                                    </td>
                                    <td>
                                        <input type="number" min="0" step="0.01" class="form-control form-control-sm capital-input" 
                                            data-id="<?php echo e($item->id); ?>" data-type="<?php echo e($item->price_type); ?>" 
                                            value="<?php echo e($item->price_type == 'retail' ? $item->r_capital : $item->w_capital); ?>" <?php echo e(($inventory->status != 1) ? 'readonly' : ''); ?>>
                                    </td>
                                    <td class="subtotal">
                                        <?php echo e(number_format($item->price_type == 'retail' ? $item->r_subtotal : $item->w_subtotal, 2)); ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-right">Total:</th>
                                    <th id="grandTotal"><?php echo e(number_format($inventoryItems->sum(fn($i) => $i->r_subtotal + $i->w_subtotal), 2)); ?></th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
                <?php if($inventory->status == 1): ?>
                <!-- Action Buttons -->
                <div class="d-flex justify-content-end mt-3 mb-2">
                    <button type="button" class="btn btn-outline-danger me-2 mr-1 cancel-btn">
                        <i class="fas fa-times-circle me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn bg-success" id="saveInventoryBtn">
                        <i class="fas fa-save"></i> Save Inventory
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
     </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/admin/inventory/form.blade.php ENDPATH**/ ?>