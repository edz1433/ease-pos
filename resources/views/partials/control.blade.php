<nav class="custom-sidebar-menu">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

        <!-- Dashboard -->
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" 
               class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Purchases -->
        <li class="nav-item">
            <a href="{{ route('purchaseRead') }}" 
               class="nav-link {{ request()->is('purchases*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Purchases</span>
            </a>
        </li>

        <!-- Warehouse -->
        <li class="nav-item">
            <a href="{{ route('warehouseRead') }}" 
               class="nav-link {{ request()->is('warehouse*') ? 'active' : '' }}">
                <i class="fas fa-warehouse"></i>
                <span>Warehouse</span>
            </a>
        </li>

        <!-- Products (with submenu) -->
        <li class="nav-item has-submenu {{ request()->is('products*') || request()->is('categories*') || request()->is('bundles*') ? 'active-menu' : '' }}">
            <a href="#" class="nav-link">
                <i class="fas fa-boxes"></i>
                <span>Products</span>
                <i class="submenu-arrow fas fa-angle-down"></i>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('productRead') }}" 
                       class="nav-link {{ request()->is('products') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <span>Product List</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('classificationRead') }}" 
                       class="nav-link {{ request()->is('products/classifications*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <span>Classifications</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('bundleRead') }}" 
                       class="nav-link {{ request()->is('products/bundles*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <span>Bundles</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Inventory -->
        <li class="nav-item">
            <a href="{{ route('inventoryRead') }}" 
               class="nav-link {{ request()->is('inventory*') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                <span>Inventory</span>
            </a>
        </li>

        <!-- POS -->
        <li class="nav-item">
            <a href="{{ config('app.react_url') }}" target="_blank" class="nav-link">
                <i class="fas fa-cash-register"></i>
                <span>POS</span>
            </a>
        </li>

        <!-- Sales -->
        <li class="nav-item">
            <a href="{{ route('salesRead') }}" 
               class="nav-link {{ request()->is('sales*') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i>
                <span>Sales</span>
            </a>
        </li>

        <!-- Cash & Bank -->
        <li class="nav-item">
            <a href="{{ route('cashbankRead') }}" 
               class="nav-link {{ request()->is('cash-bank*') ? 'active' : '' }}">
                <i class="fas fa-university"></i>
                <span>Cash & Bank</span>
            </a>
        </li>

        <!-- Cash Count (with submenu) -->
        <li class="nav-item has-submenu {{ request()->is('cash-count*') ? 'active-menu' : '' }}">
            <a href="#" class="nav-link">
                <i class="fas fa-coins"></i>
                <span>Cash Count</span>
                <i class="submenu-arrow fas fa-angle-down"></i>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('cashCountRead') }}" 
                       class="nav-link {{ request()->is('cash-count') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <span>Cash Count List</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('cashCountEntry') }}" 
                       class="nav-link {{ request()->is('cash-count/cash-entry*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <span>Cash Entry</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Suppliers -->
        <li class="nav-item">
            <a href="{{ route('supplierRead') }}" 
               class="nav-link {{ request()->is('suppliers*') ? 'active' : '' }}">
                <i class="fas fa-truck"></i>
                <span>Suppliers</span>
            </a>
        </li>

        <!-- Customer -->
        <li class="nav-item">
            <a href="{{ route('customerRead') }}" 
               class="nav-link {{ request()->is('customer*') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span>Customer</span>
            </a>
        </li>

        <!-- Users -->
        <li class="nav-item">
            <a href="{{ route('userRead') }}" 
               class="nav-link {{ request()->is('user*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
        </li>

        <!-- Settings -->
        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
                <i class="fas fa-cogs"></i>
                <span>Settings</span>
            </a>
        </li>

    </ul>
</nav>
