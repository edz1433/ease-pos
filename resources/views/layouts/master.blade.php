<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EASE || POS</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fontawesome-free-v6/css/all.min.css') }}">
    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{ asset('template/plugins/fullcalendar/fullcalendar.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/adminlte.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Date Range Picker -->
    <link rel="stylesheet" href="{{ asset('template/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('template/plugins/toastr/toastr.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Custom style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/style.css') }}">
    <!-- Favicon -->
    <link rel="shortcut icon" type="" href="{{ asset('template/img/logo.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
    .profile-image {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        margin-top: -7px;
        margin-right: 10px;
    }
    .img-circle1 {
        width: 40px !important;
        height: 40px !important;
        border-radius: 50% !important;
        object-fit: cover !important;
        border: 2px solid #ddd !important;
        display: block !important;
    }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse layout-navbar-fixed text-sm">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars text-dark"></i></a>
                </li>
            </ul>
        
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- User Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-dark" href="#" role="button" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ (auth()->user()->gender == "Male") ? asset('template/img/default-male.png') : asset('template/img/default-female.png')}}" alt="User Image" class="profile-image">
                    </a>                    
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        {{-- <a class="dropdown-item" href="{{ route('myAccount') }}"><i class="fas fa-key fa-xs"></i> My Account</a> --}}
                        <a class="dropdown-item" href="{{ route('logout') }}"><i class="fas fa-power-off fa-xs"></i> Sign Out</a>
                    </div>
                </li>
            </ul>
        </nav>

         <aside class="main-sidebar sidebar-dim-green elevation-2">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <img src="{{ asset('template/img/logo.png') }}" alt="AdminLTE Logo" class="brand-image">
                <span class="brand-text font-weight-bold text-success1">EASE</span>
            </a>       
                <!-- /.navbar -->
                <div class="sidebar">
                    <hr class="sidebar-divider">
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-4 d-flex">
                        <div class="image">
                            <img src="{{ (auth()->user()->gender == 'Male') ? asset('template/img/default-male.png') : asset('template/img/default-female.png') }}" alt="AdminLTE Logo" class="brand-image">
                        </div>                    
                        <div class="info ml-2" style="margin-top: -7px;">
                            <span class="d-block">
                                {{ auth()->user()->fname }} {{ auth()->user()->lname }}
                            </span>
                            <small class="d-block text-sm text-muted">
                                @if (auth()->user()->role == 1)
                                    Administrator
                                @elseif (auth()->user()->role == 2)
                                    Cashier
                                @elseif (auth()->user()->role == 3)
                                    Manager
                                @endif
                            </small>
                        </div>
                    </div>                
                    <hr>
                    <!-- Sidebar Menu -->
                    @include('partials.control')
                    <!-- /.sidebar-menu -->
                </div>
         </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="padding-top: 20px;">
            <!-- Main content -->
            <div class="content">
                @yield('body')
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        {{-- <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                Maintained and Managed by <a href="#" target="_blank">EASE</a>.
            </div>
        </footer> --}}
    </div>
    
    @include('script.masterScript')
    @if(request()->is('dashboard*'))
        @include('script.dashboardScript')
    @endif
    @if(request()->is('products*'))
        @include('script.productScript')
    @endif
    @if(request()->is('products/classifications*'))
        @include('script.classificationScript')
    @endif
    @if(request()->is('sales*'))
        @include('script.salesScript')
    @endif
    @if(request()->is('purchases*'))
        @include('script.purchaseScript')
    @endif
    @if(request()->is('inventory*'))
        @include('script.inventoryScript')
    @endif
    @if(request()->is('cash-count*'))
        @include('script.cashCountScript')
    @endif
</body>
</html>
