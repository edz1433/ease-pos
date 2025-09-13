

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
                                    <button class="btn btn-sm btn-primary w-100" id="applyFilters">
                                        <i class="fas fa-search"></i> Apply
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
                                        <th>Table No</th>
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
                                        <td><?php echo e(\Carbon\Carbon::parse($sale->date)->format('Y-m-d')); ?></td>
                                        <td><?php echo e($sale->transaction_number); ?></td>
                                        <td><?php echo e($sale->customer ?? 'N/A'); ?></td>
                                        <td><?php echo e($sale->table_no ?? 'N/A'); ?></td>
                                        <td>₱<?php echo e(number_format($sale->total, 2)); ?></td>
                                        <td>₱<?php echo e(number_format($sale->vat, 2)); ?></td>
                                        <td>₱<?php echo e(number_format($sale->discount, 2)); ?></td>
                                        <td>
                                            <?php if($sale->payment_method == 'Cash'): ?>
                                                <span class="badge bg-success">Cash</span>
                                            <?php elseif($sale->payment_method == 'GCash'): ?>
                                                <span class="badge bg-primary">GCash</span>
                                            <?php elseif($sale->payment_method == 'Bank Transfer'): ?>
                                                <span class="badge bg-info">Bank Transfer</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($sale->status == 1): ?>
                                                <span class="badge bg-success">Completed</span>
                                            <?php elseif($sale->status == 2): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Cancelled</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($sale->user->name ?? 'N/A'); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-info btn-sm" data-id="<?php echo e($sale->id); ?>" data-bs-toggle="tooltip" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-secondary btn-sm" data-id="<?php echo e($sale->id); ?>" data-bs-toggle="tooltip" title="Print Receipt">
                                                <i class="fas fa-receipt"></i>
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
                                    <span>Total Sales: </span>
                                    <span id="totalSales">₱<?php echo e(number_format($sales->sum('total'), 2)); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="total-display">
                                    <span>Total VAT: </span>
                                    <span id="totalVat">₱<?php echo e(number_format($sales->sum('vat'), 2)); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="total-display">
                                    <span>Total Discount: </span>
                                    <span id="totalDiscount">₱<?php echo e(number_format($sales->sum('discount'), 2)); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-main-7 text-white">
                    <h5 class="modal-title" id="detailsModalLabel">Sale Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Transaction #:</strong> <span id="detailTransaction"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Date:</strong> <span id="detailDate"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Customer:</strong> <span id="detailCustomer"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Table No:</strong> <span id="detailTable"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Payment Method:</strong> <span id="detailPayment"></span>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong> <span id="detailStatus"></span>
                        </div>
                    </div>
                    
                    <h6 class="border-bottom pb-2">Items</h6>
                    <div class="table-responsive">
                        <table class="table table-sm" id="itemsTable">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Items will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td class="text-end" id="detailSubtotal">₱0.00</td>
                                </tr>
                                <tr>
                                    <td><strong>VAT:</strong></td>
                                    <td class="text-end" id="detailVat">₱0.00</td>
                                </tr>
                                <tr>
                                    <td><strong>Discount:</strong></td>
                                    <td class="text-end" id="detailDiscount">₱0.00</td>
                                </tr>
                                <tr>
                                    <td><strong>Total:</strong></td>
                                    <td class="text-end" id="detailTotal">₱0.00</td>
                                </tr>
                                <tr>
                                    <td><strong>Amount Tendered:</strong></td>
                                    <td class="text-end" id="detailTendered">₱0.00</td>
                                </tr>
                                <tr>
                                    <td><strong>Change:</strong></td>
                                    <td class="text-end" id="detailChange">₱0.00</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="printReceiptBtn">Print Receipt</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ease-pos\resources\views/admin/sales/index.blade.php ENDPATH**/ ?>