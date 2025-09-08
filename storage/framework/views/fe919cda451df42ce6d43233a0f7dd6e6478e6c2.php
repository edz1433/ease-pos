

<?php $__env->startSection('body'); ?>
<?php echo $__env->make('layouts.formStyle', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-gray">
                        <b>SUPPLIER LIST</b>
                    </h2>
                </div>
                <div class="card-body"> 
                    <div class="row">
                        <!-- Supplier Form Column -->
                        <div class="col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-header">ADD SUPPLIER</div>
                                <div class="card-body bg-form">
                                    <form class="p-2" id="supplier_form" method="POST" enctype="multipart/form-data" 
                                        action="<?php echo e(isset($supplierEdit) ? route('supplierUpdate', $supplierEdit->id) : route('supplierCreate')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php if(isset($supplierEdit)): ?>
                                            <input type="hidden" name="id" value="<?php echo e($supplierEdit->id); ?>">
                                        <?php endif; ?>

                                        <div class="form-group">
                                            <label for="supplier_name">Supplier Name</label>
                                            <input type="text" name="supplier_name" 
                                                value="<?php echo e($supplierEdit->supplier_name ?? ''); ?>" 
                                                class="form-control form-control-sm" id="supplier_name" required>

                                            <label for="contact_person">Contact Person</label>
                                            <input type="text" name="contact_person" 
                                                value="<?php echo e($supplierEdit->contact_person ?? ''); ?>" 
                                                class="form-control form-control-sm" id="contact_person" required>

                                            <label for="contact_number">Contact Number</label>
                                            <input type="text" name="contact_number" 
                                                value="<?php echo e($supplierEdit->contact_number ?? ''); ?>" 
                                                class="form-control form-control-sm" id="contact_number" required>

                                            <label for="email">Email</label>
                                            <input type="email" name="email" 
                                                value="<?php echo e($supplierEdit->email ?? ''); ?>" 
                                                class="form-control form-control-sm" id="email">

                                            <label for="address">Address</label>
                                            <textarea name="address" id="address" rows="2" 
                                                class="form-control form-control-sm"><?php echo e($supplierEdit->address ?? ''); ?></textarea>

                                            <label for="amount_payable">Amount Payable</label>
                                            <input type="number" step="0.01" name="amount_payable" 
                                                value="<?php echo e($supplierEdit->amount_payable ?? ''); ?>" 
                                                class="form-control form-control-sm" id="amount_payable" required>
                                        </div>

                                        <button type="submit" class="btn bg-main-7 text-light w-100">
                                            <i class="fas fa-save"></i> <?php echo e(isset($supplierEdit) ? 'Update' : 'Save'); ?>

                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Supplier Table Column -->
                        <div class="col-lg-8 col-md-12">
                            <div class="card">
                                <div class="card-header">SUPPLIER LIST</div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example3" class="table table-bordered table-hover" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Supplier</th>
                                                    <th>Contact Person</th>
                                                    <th>Number</th>
                                                    <th>Email</th>
                                                    <th>Amount Payable</th>
                                                    <th>Address</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr id="row-<?php echo e($supplier->id); ?>">
                                                    <td><?php echo e($supplier->supplier_name); ?></td>
                                                    <td><?php echo e($supplier->contact_person); ?></td>
                                                    <td><?php echo e($supplier->contact_number); ?></td>
                                                    <td><?php echo e($supplier->email); ?></td>
                                                    <td>₱<?php echo e(number_format($supplier->amount_payable, 2)); ?></td>
                                                    <td><?php echo e($supplier->address); ?></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-info btn-sm edit-btn" data-id="<?php echo e($supplier->id); ?>">
                                                            <i class="fas fa-info-circle"></i>
                                                        </button>
                                                        <button value="<?php echo e($supplier->id); ?>" class="btn btn-danger btn-sm delete-row" data-model="Supplier" data-id="<?php echo e($supplier->id); ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
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
            </div>
        </div>
    </div>
</div>

<form id="post-form" action="<?php echo e(route('supplierEdit')); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" id="id">
</form>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".edit-btn").forEach(function (btn) {
            btn.addEventListener("click", function () {
                let supplierId = this.getAttribute("data-id");
                document.getElementById("id").value = supplierId;
                document.getElementById("post-form").submit();
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/admin/suppliers/index.blade.php ENDPATH**/ ?>