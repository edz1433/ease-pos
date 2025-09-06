@php $role = auth()->user()->isAdmin; @endphp

<aside class="custom-sidebar">
    <!-- User Info -->
    <div class="sidebar-user text-center p-3">
        <img src="{{ (auth()->user()->gender == 'Male') ? asset('template/img/default-male.png') : asset('template/img/default-female.png') }}" 
            alt="User Avatar" 
            class="rounded-circle mb-2" 
            style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #ddd;">
            
        <h6 class="mb-0 font-weight-bold">
            {{ auth()->user()->fname }} {{ auth()->user()->lname }}
        </h6>
        <small class="text-muted">Administrator</small>
    </div>

    <!-- Sidebar Menu -->
    <ul class="custom-sidebar-menu">
        <li>
            <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('purchaseRead') }}" class="{{ request()->is('purchases*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Purchases</span>
            </a>
        </li>
        <li>
            <a href="#" class="{{ request()->is('sales*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span>Sales Report</span>
            </a>
        </li>
        <li>
            <a href="{{ route('productRead') }}" class="{{ request()->is('products*') ? 'active' : '' }}">
                <i class="fas fa-boxes"></i>
                <span>Products</span>
            </a>
        </li>
        <li>
            <a href="{{ route('inventoryRead') }}" class="{{ request()->is('inventory*') ? 'active' : '' }}">
                <i class="fas fa-warehouse"></i>
                <span>Inventory</span>
            </a>
        </li>
        <li>
            <a href="{{ config('app.react_url') }}" target="_blank"
                class="{{ request()->is('pos*') ? 'active' : '' }}">
                <i class="fas fa-cash-register"></i>
                <span>POS</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cashbankRead') }}" class="{{ request()->is('cash-bank*') ? 'active' : '' }}">
                <i class="fas fa-university"></i>
                <span>Cash & Bank</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cashCountRead') }}" class="{{ request()->is('cash-count*') ? 'active' : '' }}">
                <i class="fas fa-coins"></i>
                <span>Cash Count</span>
            </a>
        </li>
        <li>
            <a href="{{ route('supplierRead') }}" class="{{ request()->is('suppliers*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Suppliers</span>
            </a>
        </li>
        <li>
            <a href="{{ route('userRead') }}" class="{{ request()->is('user*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
        </li>
        <li>
            <a href="#" class="{{ request()->is('settings*') ? 'active' : '' }}">
                <i class="fas fa-cogs"></i>
                <span>Settings</span>
            </a>
        </li>
    </ul>
</aside>
