

<?php $__env->startSection('body'); ?>
<div class="container-fluid">
    <div class="row">

        <!-- Cash Count Form -->
        <div class="col-lg-5 col-md-12">
            <div class="card card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Cash Count / Reconciliation</h3>
                </div>
                <div class="card-body">
                    <form id="cashCountForm" method="POST" action="">
                        <?php echo csrf_field(); ?>

                        <h5 class="mb-3 text-secondary"><i class="fas fa-coins mr-2"></i>Enter Cash Denominations</h5>
                        <div class="form-group">
                            <?php $__currentLoopData = [1,5,10,20,50,100,500,1000]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $denom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">₱<?php echo e($denom); ?></span>
                                    <input type="number" name="qty_<?php echo e($denom); ?>" id="qty_<?php echo e($denom); ?>" 
                                        value="0" min="0" class="form-control form-control-sm">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <h5 class="mb-3 text-secondary"><i class="fas fa-file-invoice-dollar mr-2"></i>Expenses & Petty Cash</h5>
                        <div class="form-group">
                            <label for="expenses_paid">Expenses Paid</label>
                            <input type="number" name="expenses_paid" id="expenses_paid" step="0.01" min="0" 
                                value="0" class="form-control form-control-sm">
                        </div>

                        <div class="form-group">
                            <label for="petty_cash_used">Petty Cash Used</label>
                            <input type="number" name="petty_cash_used" id="petty_cash_used" step="0.01" min="0" 
                                value="0" class="form-control form-control-sm">
                        </div>

                        <div class="form-group text-right">
                            <span class="h5 text-success">Total Cash Counted: ₱<span id="total_cash">0.00</span></span>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-save mr-2"></i>Save Cash Count
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cash Count History Table -->
        <div class="col-lg-7 col-md-12">
            <div class="card card-secondary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Cash Count History</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="thead-light">
                            <tr class="text-center">
                                <th>Date</th>
                                <th>Total Cash</th>
                                <th>Expenses Paid</th>
                                <th>Petty Cash</th>
                                <th>Discrepancy</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $cashCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cash): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $totalCash = ($cash->qty_1*1)+($cash->qty_5*5)+($cash->qty_10*10)+($cash->qty_20*20)+
                                                 ($cash->qty_50*50)+($cash->qty_100*100)+($cash->qty_500*500)+($cash->qty_1000*1000);
                                    $discrepancy = $totalCash - ($cash->closing_balance ?? $totalCash);
                                ?>
                                <tr>
                                    <td><?php echo e($cash->created_at->format('Y-m-d H:i')); ?></td>
                                    <td class="text-right">₱<?php echo e(number_format($totalCash,2)); ?></td>
                                    <td class="text-right">₱<?php echo e(number_format($cash->expenses_paid,2)); ?></td>
                                    <td class="text-right">₱<?php echo e(number_format($cash->petty_cash_used,2)); ?></td>
                                    <td class="text-right">₱<?php echo e(number_format($discrepancy,2)); ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo e(route('cashcount.edit', $cash->id)); ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('cashcount.destroy', $cash->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">
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

    </div>
</div>

<script>
    const denominations = [1,5,10,20,50,100,500,1000];

    function calculateTotalCash() {
        let total = 0;
        denominations.forEach(val => {
            let qty = parseInt(document.getElementById('qty_' + val).value) || 0;
            total += qty * val;
        });

        let expenses = parseFloat(document.getElementById('expenses_paid').value) || 0;
        let petty = parseFloat(document.getElementById('petty_cash_used').value) || 0;

        let totalCounted = total - expenses - petty;
        document.getElementById('total_cash').textContent = totalCounted.toFixed(2);
    }

    denominations.forEach(val => {
        let input = document.getElementById('qty_' + val);
        if(input) input.addEventListener('input', calculateTotalCash);
    });

    ['expenses_paid','petty_cash_used'].forEach(id => {
        let input = document.getElementById(id);
        if(input) input.addEventListener('input', calculateTotalCash);
    });

    calculateTotalCash();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/admin/cash-count/index.blade.php ENDPATH**/ ?>