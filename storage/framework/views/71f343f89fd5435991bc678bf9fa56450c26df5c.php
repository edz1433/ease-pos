

<?php $__env->startSection('body'); ?>
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #eaeaea;
        padding: 1rem 1.5rem;
    }
    .table th {
        font-weight: 600;
    }
    .form-control {
        border-radius: 6px;
        padding: 0.375rem 0.75rem;
    }
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    .btn-primary:hover {
        background-color: #3a56c4;
        border-color: #3a56c4;
    }
    .btn-success {
        background-color: #1cc88a;
        border-color: #1cc88a;
    }
    .btn-success:hover {
        background-color: #17a673;
        border-color: #17a673;
    }
    .btn-danger {
        background-color: #e74a3b;
        border-color: #e74a3b;
    }
    .btn-danger:hover {
        background-color: #d52a1a;
        border-color: #d52a1a;
    }
    .btn-info {
        background-color: #36b9cc;
        border-color: #36b9cc;
    }
    .btn-info:hover {
        background-color: #2a96a5;
        border-color: #2a96a5;
    }
    .badge {
        font-weight: 500;
        padding: 0.5em 0.8em;
    }
    @media print {
        body * {
            visibility: hidden;
        }
        #printSection, #printSection * {
            visibility: visible;
        }
        #printSection {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }
    .summary-card {
        background-color: #f8f9fc;
        border-left: 4px solid #fc204f;
    }
    .denom-input {
        max-width: 100px;
        margin: 0 auto;
    }
</style>

<div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center float-right">
                    <div>
                        <button class="btn bg-main-7 text-light btn-sm no-print" id="saveBtn">
                            <i class="fas fa-save me-1"></i> Save Cash Count
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Cash Count Form -->
            <div class="col-lg-6 col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h2 class="card-title text-gray">
                            <i class="fas fa-coins me-2"></i> <b>CASH COUNT ENTRY</b>
                        </h2>
                    </div>
                    <div class="card-body">
                        <form id="cashCountForm" method="POST" action="<?php echo e(route('cashCountCreate')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="cashcount_id" id="cashcount-id" value="<?php echo e($cashcounts->id ?? 0); ?>">

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-hover align-middle text-center mb-3">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Denomination</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = [0.25,0.50,1,5,10,20,50,100,500,1000]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $denom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="fw-bold align-middle">₱<?php echo e(number_format($denom, 2)); ?></td>
                                            <td class="align-middle">
                                                <input type="number"
                                                    name="qty_<?php echo e(str_replace('.','_',$denom)); ?>"
                                                    id="qty_<?php echo e(str_replace('.','_',$denom)); ?>"
                                                    min="0"
                                                    step="1"   
                                                    value="<?php echo e((int)($cashcounts->{'qty_'.str_replace('.','_',$denom)} ?? 0)); ?>"
                                                    class="form-control form-control-sm text-center">
                                            </td>
                                            <td class="text-end fw-bold align-middle">
                                                ₱<span id="subtotal_<?php echo e(str_replace('.','_',$denom)); ?>">
                                                    <?php echo e(number_format((($cashcounts->{'qty_'.str_replace('.','_',$denom)} ?? 0) * $denom), 2)); ?>

                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <tr>
                                            <td class="fw-bold align-middle" colspan="2"><b>GCASH</b></td>
                                            <td class="fw-bold align-middle">
                                                <input type="number" 
                                                    name="gcash"
                                                    id="gcash"
                                                    step="0.01"   
                                                    min="0"
                                                    value="<?php echo e($cashcounts->gcash ?? 0); ?>"
                                                    class="form-control form-control-sm text-center">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="fw-bold align-middle" colspan="2"><b>BANK</b></td>
                                            <td class="fw-bold align-middle">
                                                <input type="number" 
                                                    name="bank"
                                                    id="bank"
                                                    step="0.01"
                                                    min="0"
                                                    value="<?php echo e($cashcounts->bank ?? 0); ?>"
                                                    class="form-control form-control-sm text-center">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Hidden system values -->
                            <input type="hidden" name="total_inflow" value="<?php echo e($totalCashInflow->sum('amount') ?? 0); ?>">
                            <input type="hidden" name="total_outflow" value="<?php echo e($totalCashOutflow->sum('amount') ?? 0); ?>">
                            <input type="hidden" name="total_purchases" value="<?php echo e($totalPurchases ?? 0); ?>">
                            <input type="hidden" name="total_sales_today" value="<?php echo e($totalSalesToday + $totalCreditPayment->sum('amount') ?? 0); ?>">

                            <!-- Will be set dynamically by JS -->
                            <input type="hidden" name="variance" id="variance_field">
                        </form>
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="col-lg-6 col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h2 class="card-title text-gray">
                            <i class="fas fa-file-invoice-dollar me-2"></i> <b>CASH COUNT ENTRY</b>
                        </h2>
                    </div>
                    <div class="card-body">
                        
                        <!-- Sales Today -->
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Total Sales <?php echo e(($cashcounts == NULL) ? 'Today' : ''); ?>:</span>
                            <span class="text-primary">₱<?php echo e(number_format($totalSalesToday + $totalCreditPayment->sum('amount'), 2)); ?></span>
                        </div>

                        <!-- INFLOW Section -->
                        <h6 class="fw-bold text-success mb-2"><i class="fas fa-arrow-down me-2"></i> INFLOW</h6>
                        <ul class="list-group list-group-flush mb-3">
                            <?php $__currentLoopData = $totalCashInflow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span><?php echo e($data->transaction_type); ?><?php echo e(isset($data->description) ? ' ('.$data->description.')' : ''); ?></span>
                                    <span class="fw-semibold">₱<?php echo e(number_format($data->amount, 2)); ?></span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item d-flex justify-content-between fw-bold text-success px-0">
                                <span>Total Inflow</span>
                                <span>₱<span id="total_inflow"><?php echo e(number_format($totalCashInflow->sum('amount') ?? 0, 2)); ?></span></span>
                            </li>
                        </ul>

                        <!-- OUTFLOW Section -->
                        <h6 class="fw-bold text-danger mb-2"><i class="fas fa-arrow-up me-2"></i> OUTFLOW</h6>
                        <ul class="list-group list-group-flush mb-3">
                            <?php $__currentLoopData = $totalCashOutflow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span><?php echo e($data->transaction_type); ?><?php echo e(isset($data->description) ? ' ('.$data->description.')' : ''); ?></span>
                                    <span class="fw-semibold">₱<?php echo e(number_format($data->amount, 2)); ?></span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Total Purchases</span>
                                <span class="fw-semibold">₱<?php echo e(number_format($totalPurchases ?? 0, 2)); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between fw-bold text-danger px-0">
                                <span>Total Outflow + Purchases</span>
                                <span>₱<span id="total_outflow"><?php echo e(number_format(($totalCashOutflow->sum('amount') ?? 0) + ($totalPurchases ?? 0), 2)); ?></span></span>
                            </li>
                        </ul>

                        <hr>

                        <!-- Totals & Variance -->
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold text-success">Total Cash Counted:</span>
                            <span class="fw-bold text-success">₱<span id="total_cash">0.00</span></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Variance:</span>
                            <span class="fw-bold">₱<span id="variance">0.00</span></span>
                        </div>

                        <!-- Status Badge -->
                        <div class="mt-3 text-center">
                            <span id="statusBadge" class="badge bg-secondary fs-6 px-3 py-2">Waiting...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\ease-pos\resources\views/admin/cash-count/cash-entry.blade.php ENDPATH**/ ?>