<nav style="margin-right: -30px; !important">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('purchaseRead') }}" class="nav-link {{ request()->is('purchases*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <p>Purchases</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('warehouseRead') }}" class="nav-link {{ request()->is('warehouse*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <p>Warehouse</p>
            </a>
        </li>
        <li class="nav-item {{ request()->is('products*') || request()->is('categories*') || request()->is('bundles*') ? 'active-menu' : '' }}">
            <a href="{{ route('productRead') }}" class="nav-link {{ request()->is('products*') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i>
                <p>Products</p>
                <i class="right fas fa-angle-left"></i>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
               <li class="nav-item">
                    <a href="{{ route('productRead') }}" class="nav-link {{ request()->is('products*') && !request()->is('products/classifications*') && !request()->is('products/bundles*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Product list</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('classificationRead') }}" class="nav-link {{ request()->is('products/classifications*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Classifications</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('bundleRead') }}" class="nav-link {{ request()->is('products/bundles*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Bundles</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="{{ route('inventoryRead') }}" class="nav-link {{ request()->is('inventory*') ? 'active' : '' }}">
                <i class="fas fa-warehouse"></i>
                <p>Inventory</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ config('app.react_url') }}" target="_blank"
                class="nav-link {{ request()->is('pos*') ? 'active' : '' }}">
                <i class="fas fa-cash-register"></i>
                <p>POS</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('salesRead') }}" class="nav-link {{ request()->is('sales*') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i>
                <p>Sales</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('cashbankRead') }}" class="nav-link {{ request()->is('cash-bank*') ? 'active' : '' }}">
                <i class="fas fa-university"></i>
                <p>Cash & Bank</p>
            </a>
        </li>
        <li class="nav-item {{ request()->is('cash-count*') ? 'active-menu' : '' }}">
            <a href="{{ route('cashCountRead') }}" class="nav-link {{ request()->is('cash-count*') ? 'active' : '' }}">
                <i class="fas fa-coins"></i>
                <p>Cash Count</p>
                <i class="right fas fa-angle-left"></i>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
                <li class="nav-item">
                    <a href="{{ route('cashCountRead') }}" 
                    class="nav-link {{ request()->is('cash-count') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Cash Count List</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('cashCountEntry') }}" 
                    class="nav-link {{ request()->is('cash-count/cash-entry*') ? 'active' : '' }}">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Cash Entry</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="{{ route('supplierRead') }}" class="nav-link {{ request()->is('suppliers*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <p>Suppliers</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <p>Reports</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('customerRead') }}" class="nav-link {{ request()->is('customer*') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <p>Customer</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('userRead') }}" class="nav-link {{ request()->is('user*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <p>Users</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
                <i class="fas fa-cogs"></i>
                <p>Settings</p>
            </a>
        </li>
    </ul>
</nav>
