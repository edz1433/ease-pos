

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
                        <b>CASH COUNT LIST</b>
                    </h2>
                </div>
                <div class="card-body"> 
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="table-responsive">
                                <table id="example3" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 20px;" class="text-center">ID</th>
                                            <th>Total Inflow</th>
                                            <th>Total Outflow</th>
                                            <th>Total Sales Today</th>
                                            <th>Variance</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $cashCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cash): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="text-center align-middle text-main-8 font-weight-bold"><?php echo e($cash->id); ?></td>
                                            <td class="align-middle text-main-1 font-weight-bold"><?php echo e(number_format($cash->total_inflow, 2)); ?></td>
                                            <td class="align-middle text-main-1 font-weight-bold"><?php echo e(number_format($cash->total_outflow, 2)); ?></td>
                                            <td class="align-middle text-main-1 font-weight-bold"><?php echo e(number_format($cash->total_sales_today, 2)); ?></td>
                                            <td class="align-middle text-main-1 font-weight-bold"><?php echo e($cash->variance); ?></td>
                                            <td class="align-middle text-main-8 font-weight-bold"><?php echo e($cash->created_at->format('Y-m-d H:i')); ?></td>
                                            <td class="text-center">
                                                <a href="<?php echo e(route('cashCountEntry', $cash->id)); ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-edit"></i>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\ease-pos\resources\views/admin/cash-count/index.blade.php ENDPATH**/ ?>