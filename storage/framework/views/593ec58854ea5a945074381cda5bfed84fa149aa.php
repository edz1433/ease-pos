

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
                                            <input type="text" name="name" class="form-control form-control-sm" id="name" required>

                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control form-control-sm" id="email">

                                            <label for="phone">Phone</label>
                                            <input type="text" name="phone" class="form-control form-control-sm" id="phone">

                                            <label for="address">Address</label>
                                            <textarea name="address" rows="2" class="form-control form-control-sm" id="address"></textarea>

                                            <label for="amount_credited">Amount Credited</label>
                                            <input type="number" step="0.01" name="amount_credited" class="form-control form-control-sm" id="amount_credited" required>
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
                                    <table id="example3" class="table table-bordered table-hover" width="100%">
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

<script>
document.addEventListener("DOMContentLoaded", function() {
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
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/admin/customers/index.blade.php ENDPATH**/ ?>