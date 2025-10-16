<nav class="custom-sidebar-menu">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

        <!-- Dashboard -->
        <li class="nav-item">
            <a href="<?php echo e(route('dashboard')); ?>" 
               class="nav-link <?php echo e(request()->is('dashboard') ? 'active' : ''); ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Purchases -->
        <li class="nav-item">
            <a href="<?php echo e(route('purchaseRead')); ?>" 
               class="nav-link <?php echo e(request()->is('purchases*') ? 'active' : ''); ?>">
                <i class="fas fa-shopping-cart"></i>
                <span>Purchases</span>
            </a>
        </li>

        <!-- Warehouse -->
        <li class="nav-item">
            <a href="<?php echo e(route('warehouseRead')); ?>" 
               class="nav-link <?php echo e(request()->is('warehouse*') ? 'active' : ''); ?>">
                <i class="fas fa-warehouse"></i>
                <span>Warehouse</span>
            </a>
        </li>

        <!-- Products (with submenu) -->
        <li class="nav-item has-submenu <?php echo e(request()->is('products*') || request()->is('categories*') || request()->is('bundles*') ? 'active-menu' : ''); ?>">
            <a href="#" class="nav-link">
                <i class="fas fa-boxes"></i>
                <span>Products</span>
                <i class="submenu-arrow fas fa-angle-down"></i>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php echo e(route('productRead')); ?>" 
                       class="nav-link <?php echo e(request()->is('products') ? 'active' : ''); ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <span>Product List</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('classificationRead')); ?>" 
                       class="nav-link <?php echo e(request()->is('products/classifications*') ? 'active' : ''); ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <span>Classifications</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('bundleRead')); ?>" 
                       class="nav-link <?php echo e(request()->is('products/bundles*') ? 'active' : ''); ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <span>Bundles</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Inventory -->
        <li class="nav-item">
            <a href="<?php echo e(route('inventoryRead')); ?>" 
               class="nav-link <?php echo e(request()->is('inventory*') ? 'active' : ''); ?>">
                <i class="fas fa-box"></i>
                <span>Inventory</span>
            </a>
        </li>

        <!-- POS -->
        <li class="nav-item">
            <a href="<?php echo e(config('app.react_url')); ?>" target="_blank" class="nav-link">
                <i class="fas fa-cash-register"></i>
                <span>POS</span>
            </a>
        </li>

        <!-- Sales -->
        <li class="nav-item">
            <a href="<?php echo e(route('salesRead')); ?>" 
               class="nav-link <?php echo e(request()->is('sales*') ? 'active' : ''); ?>">
                <i class="fas fa-receipt"></i>
                <span>Sales</span>
            </a>
        </li>

        <!-- Cash & Bank -->
        <li class="nav-item">
            <a href="<?php echo e(route('cashbankRead')); ?>" 
               class="nav-link <?php echo e(request()->is('cash-bank*') ? 'active' : ''); ?>">
                <i class="fas fa-university"></i>
                <span>Cash & Bank</span>
            </a>
        </li>

        <!-- Cash Count (with submenu) -->
        <li class="nav-item has-submenu <?php echo e(request()->is('cash-count*') ? 'active-menu' : ''); ?>">
            <a href="#" class="nav-link">
                <i class="fas fa-coins"></i>
                <span>Cash Count</span>
                <i class="submenu-arrow fas fa-angle-down"></i>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="<?php echo e(route('cashCountRead')); ?>" 
                       class="nav-link <?php echo e(request()->is('cash-count') ? 'active' : ''); ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <span>Cash Count List</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('cashCountEntry')); ?>" 
                       class="nav-link <?php echo e(request()->is('cash-count/cash-entry*') ? 'active' : ''); ?>">
                        <i class="far fa-circle nav-icon"></i>
                        <span>Cash Entry</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Suppliers -->
        <li class="nav-item">
            <a href="<?php echo e(route('supplierRead')); ?>" 
               class="nav-link <?php echo e(request()->is('suppliers*') ? 'active' : ''); ?>">
                <i class="fas fa-truck"></i>
                <span>Suppliers</span>
            </a>
        </li>

        <!-- Customer -->
        <li class="nav-item">
            <a href="<?php echo e(route('customerRead')); ?>" 
               class="nav-link <?php echo e(request()->is('customer*') ? 'active' : ''); ?>">
                <i class="fas fa-user"></i>
                <span>Customer</span>
            </a>
        </li>

        <!-- Users -->
        <li class="nav-item">
            <a href="<?php echo e(route('userRead')); ?>" 
               class="nav-link <?php echo e(request()->is('user*') ? 'active' : ''); ?>">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
        </li>

        <!-- Settings -->
        <li class="nav-item">
            <a href="#" class="nav-link <?php echo e(request()->is('settings*') ? 'active' : ''); ?>">
                <i class="fas fa-cogs"></i>
                <span>Settings</span>
            </a>
        </li>

    </ul>
</nav>
<?php /**PATH E:\xampp\htdocs\ease-pos\resources\views/partials/control.blade.php ENDPATH**/ ?>