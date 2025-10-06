

<?php $__env->startSection('body'); ?>
<?php echo $__env->make('layouts.formStyle', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>
<body>
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title text-gray mb-0">
                            <b>SALES</b>
                        </h2>
                    </div>
                    <div class="card-body bg-form">
                        <form id="target_form_data" action="<?php echo e(route('salesRead')); ?>" method="GET">
                            <div class="row">
                                <!-- Date Range -->
                                <div class="col-md-3 mb-3">
                                    <label>Date Range</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm float-right" name="date_range" id="salesDateRange" placeholder="Select date range">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Transaction Number -->
                                <div class="col-md-2 mb-3">
                                    <label for="transactionFilter" class="form-label">Transaction #</label>
                                    <input type="text" class="form-control form-control-sm" name="transaction" id="transactionFilter" placeholder="Search...">
                                </div>

                                <!-- Customer -->
                                <div class="col-md-2 mb-3">
                                    <label for="customerFilter" class="form-label">Customer</label>
                                    <input type="text" class="form-control form-control-sm" name="customer" id="customerFilter" placeholder="Search...">
                                </div>

                                <!-- Payment Method -->
                                <div class="col-md-2 mb-3">
                                    <label for="paymentFilter" class="form-label">Payment Method</label>
                                    <select class="form-control form-control-sm" name="payment_method" id="paymentFilter">
                                        <option value="">All Methods</option>
                                        <option value="Cash">Cash</option>
                                        <option value="GCash">GCash</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                    </select>
                                </div>

                                <!-- Status -->
                                <div class="col-md-2 mb-3">
                                    <label for="statusFilter" class="form-label">Status</label>
                                    <select class="form-control form-control-sm" name="status" id="statusFilter">
                                        <option value="">All Status</option>
                                        <option value="1">Completed</option>
                                        <option value="2">Pending</option>
                                        <option value="0">Cancelled</option>
                                    </select>
                                </div>

                                <!-- Apply Button -->
                                <div class="col-md-1 mb-3 d-flex align-items-end">
                                    <button class="btn btn-sm bg-main-7 text-light w-100" id="applyFilters">
                                        <i class="fas fa-search fa-sm"></i> 
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <!-- Sales Table -->
                        <div class="table-responsive">
                            <table id="salesTable" class="table table-bordered table-hover table-striped table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Transaction #</th>
                                        <th>Customer</th>
                                        <th>Total Amount</th>
                                        <th>VAT</th>
                                        <th>Discount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Cashier</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-main-8 font-weight-bold"><?php echo e(\Carbon\Carbon::parse($sale->date)->format('Y-m-d')); ?></td>
                                        <td class="text-main-8 font-weight-bold"><?php echo e($sale->transaction_number); ?></td>
                                        <td class="text-main-8 font-weight-bold"><?php echo e($sale->customer ?? 'N/A'); ?></td>
                                        <td class="text-main-1">₱<?php echo e(number_format($sale->total, 2)); ?></td>
                                        <td class="text-main-1">₱<?php echo e(number_format($sale->vat, 2)); ?></td>
                                        <td class="text-main-1">₱<?php echo e(number_format($sale->discount, 2)); ?></td>
                                        <td class="text-center">
                                            <?php if($sale->payment_method == 'Cash'): ?>
                                                <span class="badge bg-success">Cash</span>
                                            <?php elseif($sale->payment_method == 'GCash'): ?>
                                                <span class="badge bg-primary">GCash</span>
                                            <?php elseif($sale->payment_method == 'Bank Transfer'): ?>
                                                <span class="badge bg-info">Bank Transfer</span>
                                            <?php endif; ?>
                                        </td> 
                                        <td class="text-center">
                                            <?php if($sale->status == 1): ?>
                                                <span class="badge bg-success">Completed</span>
                                            <?php elseif($sale->status == 2): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Cancelled</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-main-8 font-weight-bold"><?php echo e($sale->full_name ?? ''); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-info btn-sm" data-id="<?php echo e($sale->id); ?>" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Section -->
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="total-display">
                                    <span class="text-main-8 font-weight-bold">Total Sales: </span>
                                    <span class="text-main-1 font-weight-bold" id="totalSales">₱<?php echo e(number_format($sales->sum('total'), 2)); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="total-display">
                                    <span class="text-main-8 font-weight-bold">Total VAT: </span>
                                    <span class="text-main-1 font-weight-bold" id="totalVat">₱<?php echo e(number_format($sales->sum('vat'), 2)); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="total-display">
                                    <span class="text-main-8 font-weight-bold">Total Discount: </span>
                                    <span class="text-main-1 font-weight-bold" id="totalDiscount">₱<?php echo e(number_format($sales->sum('discount'), 2)); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/admin/sales/index.blade.php ENDPATH**/ ?>