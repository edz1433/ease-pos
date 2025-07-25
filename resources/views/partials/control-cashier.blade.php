@php $role = auth()->user()->isAdmin; @endphp
<!-- Sidebar -->
<aside class="custom-sidebar">
    <div class="custom-sidebar-logo" style="margin-bottom: 10px;">
        <img src="{{ asset('template/img/logo.png') }}" alt="Logo" style="width:40px; height:40px;">
    </div>
    <ul class="custom-sidebar-menu">
        <li>
            <a href="{{ route('dashboard') }}" class="custom-sidebar-link {{ request()->is('pos') ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-th-large"></i></span>
                Menu
            </a>
        </li>
        <li>
            <a href="#" class="custom-sidebar-link">
                <span class="icon"><i class="fa fa-list-alt"></i></span>
                Order List
            </a>
        </li>
        <li>
            <a href="#" class="custom-sidebar-link">
                <span class="icon"><i class="fas fa-history"></i></span>
                History
            </a>
        </li>
    </ul>

    <div class="mt-auto" style="width: 88%; padding: 0px;">
        <a href=""
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="custom-sidebar-link" style="width: 100%;">
            <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
            Log Out
        </a>
        <form id="logout-form" action="" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</aside>

