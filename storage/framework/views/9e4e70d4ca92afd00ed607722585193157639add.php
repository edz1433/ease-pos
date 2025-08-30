

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
                        <b>INVENTORY</b>
                    </h2>
                    <?php if(!$checkinv): ?>
                        <a href="#" id="startInventoryBtn" class="btn bg-main text-light btn-sm" style="float: right;">
                            <i class="fas fa-plus fa-xs"></i> START INVENTORY
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body"> 
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="table-responsive" style="height: 300px !important;">
                                <table id="inventory-table" class="table table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="30" class="text-center">#</th>
                                            <th class="text-center">Start Date</th>
                                            <th class="text-center">End Date</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $inventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr id="row-<?php echo e($inv->id); ?>">
                                                <td class="text-center align-middle"><?php echo e($index + 1); ?></td>
                                                <td class="text-center align-middle"><?php echo e(\Carbon\Carbon::parse($inv->start_date)->format('M d, Y')); ?></td>
                                                <td class="text-center align-middle"><?php echo e(\Carbon\Carbon::parse($inv->end_date)->format('M d, Y')); ?></td>
                                                <td class="text-center align-middle">
                                                    <?php if($inv->status == 1): ?>
                                                        <span class="badge badge-success">Ongoing</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-success">Done</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <a class="btn btn-info btn-sm" href="<?php echo e(route('inventoryForm', $inv->id)); ?>">
                                                        <i class="fas fa-info-circle"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-sm delete-row" data-model="Inventory" data-id="<?php echo e($inv->id); ?>">
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/admin/inventory/index.blade.php ENDPATH**/ ?>