

<?php $__env->startSection('body'); ?>
<?php echo $__env->make('layouts.formStyle', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="container-fluid">
    <div class="row">
        <!-- Transaction Form Column -->
        <div class="col-lg-4 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h2 class="card-title text-gray mb-0">
                        <b>TRANSACTION FORM</b>
                    </h2>
                </div>
                <div class="card-body bg-form">
                    <form action="<?php echo e(route('cashbankCreate')); ?>" method="POST" id="cashBankForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" id="transaction_id">

                        <div class="mb-2">
                            <label>Transaction Date</label>
                            <input type="datetime-local" name="transaction_date" id="transaction_date" 
                                class="form-control form-control-sm" value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>" required>
                        </div>

                        <div class="mb-2">
                            <label>Transaction Type</label>

                            <select name="transaction_type" id="transaction_type" class="form-control form-control-sm" required>
                                <option value="">-- Select Transaction Type --</option>

                                <!-- Inflow: money entering the cash drawer -->
                                <optgroup label="Inflow">
                                    <option value="Sales Deposit">Sales Deposit (POS)</option>
                                    <option value="Petty Cash">Petty Cash (Starting Money)</option>
                                    <option value="Transfer In">Transfer In</option>
                                </optgroup>

                                <!-- Outflow: money leaving the cash drawer -->
                                <optgroup label="Outflow">
                                    <option value="Cash Withdrawal">Cash Withdrawal</option>
                                    <option value="Operating Expense">Operating Expense</option>
                                    <option value="Salary & Wages">Salary & Wages</option>
                                    <option value="Petty Cash Expense">Petty Cash Expense</option>
                                    <option value="Transfer Out">Transfer Out</option>
                                </optgroup>
                            </select>
                        </div>
                        <input type="hidden" name="category" id="category">

                        <div class="mb-2">
                            <label>Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control form-control-sm" step="0.01" min="0" required>
                        </div>

                        <div class="mb-2">
                            <label>Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control form-control-sm"></textarea>
                        </div>

                        <button type="submit" class="btn bg-main-7 text-light w-100 btn-sm" id="saveBtn">
                            <i class="fas fa-save"></i> Save Transaction
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Transaction Table Column -->
        <div class="col-lg-8 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header text-dark">
                    <h2 class="card-title text-gray mb-0">
                        <b>TRANSACTION LIST</b>
                    </h2>
                    <div class="float-right">
                        <form method="GET" id="dateForm">
                            <input type="date" id="date" class="form-control form-control-sm w-auto d-inline-block"
                                value="<?php echo e($date); ?>"
                                onchange="window.location='<?php echo e(url('cash-bank')); ?>/' + this.value">
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example3" class="table table-bordered table-hover" id="transactionsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Transaction</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trans): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr id="row-<?php echo e($trans->id); ?>">
                                        <td class="align-middle text-main-8 font-weight-bold"><?php echo e($trans->transaction_date->format('M d, Y - h:i A')); ?></td>
                                        <td class="align-middle text-main-8 font-weight-bold">
                                            <?php echo ($trans->category == 1) 
                                            ? '<span class="badge badge-success">Inflow</span>' 
                                            : '<span class="badge badge-danger">Outflow</span>'; ?>

                                        </td>
                                        <td class="align-middle text-main-8 font-weight-bold"><?php echo e($trans->transaction_type); ?></td>
                                        <td class="text-center align-middle text-main-1 font-weight-bold">₱<?php echo e(number_format($trans->amount, 2)); ?></td>
                                        <td class="align-middle text-main-8 font-weight-bold"><?php echo e($trans->description); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-info btn-sm editBtn" data-id="<?php echo e($trans->id); ?>"
                                                data-date="<?php echo e($trans->transaction_date->format('Y-m-d\TH:i')); ?>"
                                                data-category="<?php echo e($trans->category); ?>"
                                                data-trans="<?php echo e($trans->transaction_type); ?>"
                                                data-amount="<?php echo e($trans->amount); ?>"
                                                data-desc="<?php echo e($trans->description); ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button value="<?php echo e($trans->id); ?>" class="btn btn-danger btn-sm delete-row" data-model="CashBankTransaction" data-id="<?php echo e($trans->id); ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="4" class="text-end">Total Inflow</th>
                                    <th colspan="2" class="text-end text-success">
                                        ₱<?php echo e(number_format($transactions->where('category', 1)->sum('amount'), 2)); ?>

                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-end">Total Outflow</th>
                                    <th colspan="2" class="text-end text-danger">
                                        ₱<?php echo e(number_format($transactions->where('category', 0)->sum('amount'), 2)); ?>

                                    </th>
                                </tr>
                                <tr class="fw-bold">
                                    <th colspan="4" class="text-end">Net Total</th>
                                    <th colspan="2" class="text-end">
                                        ₱<?php echo e(number_format($transactions->sum('amount'), 2)); ?>

                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Define transaction type categories
const inflowTypes = ['Sales Deposit', 'Petty Cash', 'Transfer In'];
const outflowTypes = ['Cash Withdrawal', 'Operating Expense', 'Salary & Wages', 'Petty Cash Expense', 'Transfer Out'];

// Function to get category from transaction type
function setCategory(transactionType) {
    if (inflowTypes.includes(transactionType)) return 1;
    if (outflowTypes.includes(transactionType)) return 2;
    return '';
}

// Prefill form for editing
document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', function() {
        const form = document.getElementById('cashBankForm');
        const txId = this.dataset.id;
        const transactionType = this.dataset.trans;

        // Fill form fields
        document.getElementById('transaction_id').value = txId;
        document.getElementById('transaction_date').value = this.dataset.date;
        document.getElementById('transaction_type').value = transactionType;
        document.getElementById('amount').value = this.dataset.amount;
        document.getElementById('description').value = this.dataset.desc;

        // Set category based on transaction type
        document.getElementById('category').value = this.dataset.category;

        // Update form action for update route
        form.action = "<?php echo e(route('cashbankUpdate', ':id')); ?>".replace(':id', txId);
        form.querySelector('#saveBtn').innerHTML = '<i class="fas fa-save"></i> Update Transaction';
    });
});

// Optional: automatically set category when transaction type changes
document.getElementById('transaction_type').addEventListener('change', function() {
    document.getElementById('category').value = setCategory(this.value);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\ease-pos\resources\views/admin/cash-bank/index.blade.php ENDPATH**/ ?>