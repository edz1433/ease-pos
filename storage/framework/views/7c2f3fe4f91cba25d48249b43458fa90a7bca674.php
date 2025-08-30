

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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-gray">
                        <b>PURCHASES</b>
                    </h2>
                    <a href="<?php echo e(route('purchaseForm')); ?>" class="btn bg-main text-light btn-sm" style="float: right;"><i class="fas fa-plus fa-xs"></i> PURCHASE</a>
                </div>
                <div class="card-body"> 
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="table-responsive">
                                <table id="example3" class="table table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="30" class="text-center">#</th>
                                            <th class="text-center">Purchase No.</th>
                                            <th class="text-center">Supplier</th>
                                            <th class="text-center">PO Number</th>
                                            <th class="text-center">Purchase Date</th>
                                            <th class="text-center">Payment Info</th>
                                            <th class="text-center">Total Amount</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="text-center align-middle"><?php echo e($index + 1); ?></td>
                                                <td class="text-center align-middle"><?php echo e($purchase->transaction_no); ?></td>
                                                <td class="text-center align-middle"><?php echo e($purchase->supplier_name ?? 'N/A'); ?></td>
                                                <td class="text-center align-middle"><?php echo e($purchase->po_number ?? '-'); ?></td>
                                                <td class="text-center align-middle"><?php echo e(\Carbon\Carbon::parse($purchase->purchase_date)->format('M d, Y') ?? '-'); ?></td>
                                                <td class="text-left align-middle">
                                                    <b>Mode:</b> <?php echo e($purchase->payment_mode ?? '-'); ?><br>
                                                    <?php if($purchase->payment_mode === 'Credit'): ?>
                                                        <b>Due:</b> <?php echo e(\Carbon\Carbon::parse($purchase->due_date)->format('M d, Y') ?? '-'); ?><br>
                                                    <?php elseif($purchase->payment_mode === 'Postdated Check'): ?>
                                                        <b>Check Date:</b> <?php echo e(\Carbon\Carbon::parse($purchase->check_date)->format('M d, Y') ?? '-'); ?><br>
                                                        <b>Bank:</b> <?php echo e($purchase->bank_name ?? '-'); ?><br>
                                                        <b>Account:</b> <?php echo e($purchase->account_name ?? '-'); ?><br>
                                                        <b>Check No:</b> <?php echo e($purchase->check_number ?? '-'); ?>

                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-danger align-middle"><b><?php echo e(number_format($purchase->total_amount, 2)); ?></b></td>
                                                <td class="text-center align-middle">
                                                    <?php if($purchase->payment_status == "paid"): ?>
                                                        <span class="badge badge-success align-middle">paid</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning align-middle">unpaid</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button type="button" class="btn btn-info btn-sm edit-btn" data-id="<?php echo e($purchase->id); ?>">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                    <button value="<?php echo e($purchase->id); ?>" class="btn btn-danger btn-sm delete-row" data-model="PurchaseItem" data-id="<?php echo e($purchase->id); ?>">
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
                </div>
            </div>
        </div>
     </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/admin/purchases/index.blade.php ENDPATH**/ ?>