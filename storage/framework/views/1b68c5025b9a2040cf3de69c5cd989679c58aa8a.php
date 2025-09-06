<?php $role = auth()->user()->isAdmin; ?>

<aside class="custom-sidebar">
    <!-- User Info -->
    <div class="sidebar-user text-center p-3">
        <img src="<?php echo e((auth()->user()->gender == 'Male') ? asset('template/img/default-male.png') : asset('template/img/default-female.png')); ?>" 
            alt="User Avatar" 
            class="rounded-circle mb-2" 
            style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #ddd;">
            
        <h6 class="mb-0 font-weight-bold">
            <?php echo e(auth()->user()->fname); ?> <?php echo e(auth()->user()->lname); ?>

        </h6>
        <small class="text-muted">Administrator</small>
    </div>

    <!-- Sidebar Menu -->
    <ul class="custom-sidebar-menu">
        <li>
            <a href="<?php echo e(route('dashboard')); ?>" class="<?php echo e(request()->is('dashboard') ? 'active' : ''); ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('purchaseRead')); ?>" class="<?php echo e(request()->is('purchases*') ? 'active' : ''); ?>">
                <i class="fas fa-shopping-cart"></i>
                <span>Purchases</span>
            </a>
        </li>
        <li>
            <a href="#" class="<?php echo e(request()->is('sales*') ? 'active' : ''); ?>">
                <i class="fas fa-chart-bar"></i>
                <span>Sales Report</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('productRead')); ?>" class="<?php echo e(request()->is('products*') ? 'active' : ''); ?>">
                <i class="fas fa-boxes"></i>
                <span>Products</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('inventoryRead')); ?>" class="<?php echo e(request()->is('inventory*') ? 'active' : ''); ?>">
                <i class="fas fa-warehouse"></i>
                <span>Inventory</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(config('app.react_url')); ?>" target="_blank"
                class="<?php echo e(request()->is('pos*') ? 'active' : ''); ?>">
                <i class="fas fa-cash-register"></i>
                <span>POS</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('cashbankRead')); ?>" class="<?php echo e(request()->is('cash-bank*') ? 'active' : ''); ?>">
                <i class="fas fa-university"></i>
                <span>Cash & Bank</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('cashCountRead')); ?>" class="<?php echo e(request()->is('cash-count*') ? 'active' : ''); ?>">
                <i class="fas fa-coins"></i>
                <span>Cash Count</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('supplierRead')); ?>" class="<?php echo e(request()->is('suppliers*') ? 'active' : ''); ?>">
                <i class="fas fa-users"></i>
                <span>Suppliers</span>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('userRead')); ?>" class="<?php echo e(request()->is('user*') ? 'active' : ''); ?>">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
        </li>
        <li>
            <a href="#" class="<?php echo e(request()->is('settings*') ? 'active' : ''); ?>">
                <i class="fas fa-cogs"></i>
                <span>Settings</span>
            </a>
        </li>
    </ul>
</aside>
<?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/partials/control.blade.php ENDPATH**/ ?>