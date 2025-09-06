

<?php $__env->startSection('body'); ?>
<style>
    .bg-form{
        background-color:  #e9ecef;
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
    .btn-sm{
        font-size: 10px !important;
        height: 25px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .bb{
        border-bottom: 1px solid rgb(145, 138, 138);
    }
</style>
<div class="container-fluid">
    <div class="row">
        <!-- User Form Column -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">ADD USER</div>
                <div class="card-body bg-form">
                    <form class="p-2" id="target_form_data" method="POST" enctype="multipart/form-data" 
                        action="<?php echo e(isset($useredit) ? route('userUpdate', $useredit->id) : route('userCreate')); ?>">
                        <?php echo csrf_field(); ?>
                        <?php if(isset($useredit)): ?>
                            <input type="hidden" name="id" value="<?php echo e($useredit->id); ?>">
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input type="text" name="lname" 
                                value="<?php echo e($useredit->lname ?? ''); ?>" 
                                class="form-control form-control-sm" id="lname" required>

                            <label for="fname">First Name</label>
                            <input type="text" name="fname" 
                                value="<?php echo e($useredit->fname ?? ''); ?>" 
                                class="form-control form-control-sm" id="fname" required>
                            
                            <label for="mname">Middle Name</label>
                            <input type="text" name="mname" 
                                value="<?php echo e($useredit->mname ?? ''); ?>" 
                                class="form-control form-control-sm" id="mname" required>

                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class="form-control form-control-sm" required>
                                <option value="">-- Select Gender --</option>
                                <option value="Male" <?php echo e(($useredit->gender ?? '') == 'Male' ? 'selected' : ''); ?>>Male</option>
                                <option value="Female" <?php echo e(($useredit->gender ?? '') == 'Female' ? 'selected' : ''); ?>>Female</option>
                            </select>

                            <label for="role">Role</label>
                            <select name="role" id="role" class="form-control form-control-sm">
                                <option value="2" <?php echo e(($useredit->role ?? '') == '2' ? 'selected' : ''); ?>>Cashier</option>
                                <option value="1" <?php echo e(($useredit->role ?? '') == '1' ? 'selected' : ''); ?>>Administrator</option>
                            </select>

                            <label for="username">Username</label>
                            <input type="text" name="username" 
                                value="<?php echo e($useredit->username ?? ''); ?>" 
                                class="form-control form-control-sm" id="username" required>

                            <label for="password">Password</label>
                            <input type="password" name="password" 
                                class="form-control form-control-sm" id="password" 
                                <?php echo e(isset($useredit) ? '' : 'required'); ?>> 
                            

                            <label for="profile">Profile Picture</label>
                            <input type="file" name="profile" class="form-control form-control-sm" id="profile" accept="image/*">
                        </div>

                        <button type="submit" class="btn bg-main-7 text-light w-100">
                            <i class="fas fa-save"></i> <?php echo e(isset($useredit) ? 'Update' : 'Save'); ?>

                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- User Table Column -->
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">USER LIST</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example3" class="table table-bordered table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th>Profile</th>
                                    <th>Full Name</th>
                                    <th>Gender</th>
                                    <th>Role</th>
                                    <th>Username</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr id="row-<?php echo e($user->id); ?>" class="odd gradeX">
                                    <td class="text-center">
                                        <img src="<?php echo e($user->profile && Storage::disk('public')->exists('uploads/profile/' . $user->profile) 
                                                    ? asset('storage/uploads/profile/' . $user->profile) 
                                                    : asset('storage/uploads/profile/admin-default.png')); ?>"
                                            alt="Profile"
                                            class="img-thumbnail"
                                            style="width:40px; height:40px; object-fit:cover; border-radius:50%;">
                                    </td>
                                    <td><?php echo e($user->fname); ?> <?php echo e($user->lname); ?></td>
                                    <td><?php echo e($user->gender); ?></td>    
                                    <td>
                                        <?php if($user->role == 1): ?>
                                            <span class="label label-danger">Administrator</span>
                                        <?php elseif($user->role == 2): ?>
                                            <span class="label label-primary">Cashier</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="center"><?php echo e($user->username); ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-info btn-sm edit-btn" data-id="<?php echo e($user->id); ?>">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                        <button value="<?php echo e($user->id); ?>" class="btn btn-danger btn-sm delete-row" data-model="User" data-id="<?php echo e($user->id); ?>">
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
<form id="post-form" action="<?php echo e(route('userEdit')); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" id="id">
</form>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".edit-btn").forEach(function (btn) {
            btn.addEventListener("click", function () {
                let userId = this.getAttribute("data-id");
                document.getElementById("id").value = userId;
                document.getElementById("post-form").submit();
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/admin/users/index.blade.php ENDPATH**/ ?>