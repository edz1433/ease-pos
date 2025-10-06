

<?php $__env->startSection('body'); ?>
<?php echo $__env->make('layouts.formStyle', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-gray mb-0">
                        <b>CUSTOMERS</b>
                    </h2>
                </div>
                <div class="card-body">
                    <div class="row transition-row">
                        <!-- Customer Form Column -->
                        <div class="col-lg-3">
                            <div class="panel panel-default">
                                <div class="panel-heading" id="formHeading">ADD CUSTOMER</div>
                                <div class="panel-body bg-form">
                                    <form class="p-2" id="customer_form_data" method="POST" action="<?php echo e(route('customerCreate')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" id="customer_id">

                                        <div class="form-group">
                                            <label for="name">Full Name</label>
                                            <input type="text" name="name" class="form-control form-control-sm <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" required>
                                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control form-control-sm <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email">
                                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" class="form-control form-control-sm <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="phone">
                                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                            <label for="address">Address</label>
                                            <textarea name="address" rows="2" class="form-control form-control-sm <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="address"></textarea>
                                            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                            <label for="amount_credited">Amount Credited</label>
                                            <input type="number" step="0.01" name="amount_credited" class="form-control form-control-sm <?php $__errorArgs = ['amount_credited'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="amount_credited" required>
                                            <?php $__errorArgs = ['amount_credited'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <button type="submit" class="btn bg-main-7 text-light mt-2 w-100" id="saveBtn">
                                            <i class="fas fa-save"></i> Save Customer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>  

                        <!-- Customer Table Column -->
                        <div class="col-lg-9">
                            <div class="card">
                                <div class="table-responsive">
                                    <table id="example2" class="table table-bordered table-hover" width="100%">
                                        <thead class="bg-main-9 text-dark">
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Amount Credited</th>
                                                <th>Total Paid</th>
                                                <th>Balance</th>
                                                <th class="text-center align-middle">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr id="row-<?php echo e($customer->id); ?>">
                                                    <td class="align-middle">
                                                        <strong class="text-main-8"><?php echo e($customer->name); ?></strong><br>
                                                        <small class="text-main-1"><?php echo e($customer->address); ?></small>
                                                    </td>
                                                    <td class="align-middle"><?php echo e($customer->email); ?></td>
                                                    <td class="align-middle"><?php echo e($customer->phone); ?></td>
                                                    <td class="text-right align-middle text-main-1 font-weight-bold">₱<?php echo e(number_format($customer->amount_credited, 2)); ?></td>
                                                    <td class="text-right align-middle text-main-1 font-weight-bold">₱<?php echo e(number_format($customer->total_payments, 2)); ?></td>
                                                    <td class="text-right align-middle text-main-1 font-weight-bold">
                                                        ₱<?php echo e(number_format($customer->balance, 2)); ?>

                                                    </td>
                                                    <!-- Actions -->
                                                    <td class="text-center align-middle">
                                                        <button class="btn btn-info btn-sm edit-btn" 
                                                            data-id="<?php echo e($customer->id); ?>"
                                                            data-name="<?php echo e($customer->name); ?>"
                                                            data-email="<?php echo e($customer->email); ?>"
                                                            data-phone="<?php echo e($customer->phone); ?>"
                                                            data-address="<?php echo e($customer->address); ?>"
                                                            data-amount_credited="<?php echo e($customer->amount_credited); ?>"
                                                            title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-success btn-sm pay-btn" 
                                                            data-id="<?php echo e($customer->id); ?>"
                                                            data-name="<?php echo e($customer->name); ?>"
                                                            data-balance="<?php echo e($customer->balance); ?>"
                                                            title="Pay Balance">
                                                            <i class="fas fa-money-bill"></i>
                                                        </button>
                                                        <button class="btn btn-primary btn-sm history-btn" 
                                                            data-id="<?php echo e($customer->id); ?>"
                                                            data-name="<?php echo e($customer->name); ?>"
                                                            title="View Payment History">
                                                            <i class="fas fa-history"></i>
                                                        </button>
                                                        <a href="#" class="btn btn-danger btn-sm delete-row" 
                                                           data-model="Customer" 
                                                           data-id="<?php echo e($customer->id); ?>" 
                                                           title="Delete">
                                                            <i class="fas fa-trash"></i>
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
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Record Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="payment_form" method="POST" action="<?php echo e(route('customerPayment')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="customer_id" id="payment_customer_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="customer_name">Customer Name</label>
                        <input type="text" class="form-control" id="customer_name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="current_balance">Current Balance</label>
                        <input type="text" class="form-control" id="current_balance" readonly>
                    </div>
                    <div class="form-group">
                        <label for="payment_amount">Payment Amount</label>
                        <input type="number" step="0.01" name="payment_amount" class="form-control <?php $__errorArgs = ['payment_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="payment_amount" required>
                        <?php $__errorArgs = ['payment_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn bg-main-7 text-light">Submit Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment History Modal -->
<div class="modal fade" id="paymentHistoryModal" tabindex="-1" role="dialog" aria-labelledby="paymentHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentHistoryModalLabel">Payment History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6 id="history_customer_name"></h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="example3">
                        <thead class="bg-main-9 text-dark">
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody id="payment_history_body">
                            <!-- Payment history will be populated via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize DataTable for the main customer table
    // Prefill form for editing
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = document.getElementById('customer_form_data');
            const customerId = this.dataset.id;

            // Fill form fields
            document.getElementById('customer_id').value = customerId;
            document.getElementById('name').value = this.dataset.name;
            document.getElementById('email').value = this.dataset.email;
            document.getElementById('phone').value = this.dataset.phone;
            document.getElementById('address').value = this.dataset.address;
            document.getElementById('amount_credited').value = this.dataset.amount_credited;

            // Update form action for update route
            form.action = "<?php echo e(route('customerUpdate', ':id')); ?>".replace(':id', customerId);
            form.querySelector('#saveBtn').innerHTML = '<i class="fas fa-save"></i> Update Customer';
            document.getElementById('formHeading').textContent = 'EDIT CUSTOMER';
            
            // Scroll to form
            document.getElementById('customer_form_data').scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Handle payment modal
    document.querySelectorAll('.pay-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const customerId = this.dataset.id;
            const customerName = this.dataset.name;
            const balance = this.dataset.balance;

            // Fill modal fields
            document.getElementById('payment_customer_id').value = customerId;
            document.getElementById('customer_name').value = customerName;
            document.getElementById('current_balance').value = '₱' + parseFloat(balance).toFixed(2);
            document.getElementById('payment_amount').value = '';

            // Show modal
            $('#paymentModal').modal('show');
        });
    });

    // Handle payment history modal
    document.querySelectorAll('.history-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const customerId = this.dataset.id;
            const customerName = this.dataset.name;

            // Set customer name in modal
            document.getElementById('history_customer_name').textContent = 'Payment History for ' + customerName;

            // Fetch payment history via AJAX
            fetch('<?php echo e(route("customerPaymentsHistory", ":customerId")); ?>'.replace(':customerId', customerId))
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('payment_history_body');
                    tbody.innerHTML = ''; // Clear previous content

                    if (data.payments.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="2" class="text-center">No payment history available</td></tr>';
                    } else {
                        data.payments.forEach(payment => {
                            const row = `
                                <tr>
                                    <td>${new Date(payment.created_at).toLocaleDateString()}</td>
                                    <td>₱${parseFloat(payment.amount).toFixed(2)}</td>
                                </tr>
                            `;
                            tbody.innerHTML += row;
                        });
                    }

                    // Show modal
                    $('#paymentHistoryModal').modal('show');
                })
                .catch(error => {
                    console.error('Error fetching payment history:', error);
                    alert('Failed to load payment history. Please try again.');
                });
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\ease-pos\resources\views/admin/customers/index.blade.php ENDPATH**/ ?>