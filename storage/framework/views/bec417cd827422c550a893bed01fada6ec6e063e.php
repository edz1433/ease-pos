<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>EASE || POS</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo e(asset('template/plugins/fontawesome-free-v6/css/all.min.css')); ?>">
    <!-- fullCalendar -->
    <link rel="stylesheet" href="<?php echo e(asset('template/plugins/fullcalendar/fullcalendar.css')); ?>">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?php echo e(asset('template/plugins/icheck-bootstrap/icheck-bootstrap.min.css')); ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo e(asset('template/dist/css/adminlte.min.css')); ?>">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo e(asset('template/plugins/select2/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('template/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')); ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo e(asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')); ?>">
    <!-- Toastr -->
    <link rel="stylesheet" href="<?php echo e(asset('template/plugins/toastr/toastr.min.css')); ?>">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="<?php echo e(asset('template/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')); ?>">
    <!-- Custom style -->
    <link rel="stylesheet" href="<?php echo e(asset('template/dist/css/cashier-style.css')); ?>">
    <!-- Favicon -->
    <link rel="shortcut icon" type="" href="<?php echo e(asset('template/img/logo.png')); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed text-sm">
    <div class="wrapper d-flex">
        <!-- Left Sidebar -->
        <aside class="main-sidebar sidebar-no-expand">
            <div class="sidebar">
                <?php echo $__env->make('partials.control-cashier', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="content-wrapper-cashier flex-grow-1">
            <div class="content-header bg-white rounded shadow-sm d-flex justify-content-between align-items-center px-4 py-2 mb-3" style="min-height:60px; margin-left: -17px;">
                <div class="d-flex align-items-center">
                    <span class="rounded-circle overflow-hidden d-flex align-items-center justify-content-center me-2"
                          style="width:40px;height:40px;background:#e9ecef;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                        <i class="fas fa-user fa-lg text-secondary"></i>
                    </span>
                    <div class="text-start">
                        <span class="fw-semibold text-dark pl-1"> Welcome, Jochel!</span><br>
                        <?php
                            $inspirationalQuotes = [
                                "Serve with a smile every plate is a chance to brighten someone's day.",
                                "Teamwork in the kitchen makes the dream work on the floor.",
                                "A happy guest starts with a happy server.",
                                "Every meal you serve is a memory for someone make it special.",
                                "Your attention to detail turns a meal into an experience.",
                                "Kindness is the secret ingredient in every dish.",
                                "Stay sharp, stay positive, and let your passion show.",
                                "Behind every great restaurant is a team that cares.",
                                "Your hard work brings people together one table at a time.",
                                "Patience and a positive attitude are always on the menu.",
                                "Every shift is a new opportunity to make someone’s day.",
                                "Great service is remembered long after the meal is over.",
                                "You are the heart of the restaurant keep beating strong.",
                                "A clean table and a warm greeting go a long way.",
                                "Your energy sets the tone for the whole dining room.",
                                "Even the busiest nights end with satisfied smiles.",
                                "Take pride in your craft every detail matters.",
                                "Your dedication keeps the kitchen running and the guests returning.",
                                "A little extra effort makes a big difference.",
                                "Celebrate the small wins every happy guest counts.",
                                "Stay humble, work hard, be kind.",
                                "You’ve got this!",
                                "Be the reason someone smiles today.",
                                "Push yourself, because no one else is going to do it for you.",
                                "Great things never come from comfort zones.",
                                "Don’t watch the clock; do what it does. Keep going.",
                                "Focus on the good.",
                                "Start where you are. Use what you have. Do what you can.",
                                "The secret of getting ahead is getting started.",
                            ];
                            $day = date('j');
                            $month = date('n');
                            $index = (($day * $month) + $day + $month) % count($inspirationalQuotes);
                        ?>
                        <span class="badge bg-light text-primary mt-1"
                              style="font-size: 0.85em; white-space: normal;">
                            <?php echo e($inspirationalQuotes[$index]); ?>

                        </span>
                    </div>
                </div>
                <div class="d-flex align-items-center ms-auto">
                    <form class="form-inline me-3" role="search" style="min-width:220px;">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-0 pe-2">
                                <i class="fas fa-search text-secondary"></i>
                            </span>
                            <input class="form-control border-0 bg-light" type="search" placeholder="Search..." aria-label="Search">
                        </div>
                    </form>
                    <button class="btn btn-link text-secondary me-2" style="font-size: 1.2rem;">
                        <i class="fas fa-filter"></i>
                    </button>
                    <button class="btn btn-link text-secondary position-relative" style="font-size: 1.2rem;">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.65em;">
                            3
                        </span>
                    </button>
                </div>
            </div>

            <div class="content">
                <?php echo $__env->yieldContent('body'); ?>
            </div>
        </div>

        <!-- Right Sidebar -->
        <aside class="order-sidebar">
            <div class="order-box">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 1.1rem; font-weight: 600; color: #333; margin-top: -25px;">
                    <span>Order #000</span>
                    <span style="font-size: 0.95rem; font-weight: 400; color: #666;">Sunday, 01 Jan, 2025</span>
                </div>

                <!-- Customer & Table Info -->
                <div class="mb-3">
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text bg-light border-0 text-primary">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" class="form-control border-0" placeholder="Customer Name" style="background: #f4f6f9;">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-0 text-primary">
                            <i class="fas fa-chair"></i>
                        </span>
                        <input type="text" class="form-control border-0" placeholder="Table #" style="background: #f4f6f9; max-width: 120px;">
                    </div>
                </div>

                <!-- Scrollable order list -->
                <div class="order-scrollable flex-grow-1 overflow-auto">
                    <?php for($i = 0; $i < 15; $i++): ?>
                        <div class="order-item d-flex align-items-center p-2 mb-2 rounded" style="background-color: #ffffff;">
                            <img src="<?php echo e(asset('template/img/burger.png')); ?>" alt="Item" class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                            <div class="flex-grow-1">
                                <div class="fw-bold">Burger</div>
                                <small class="text-muted">₱300.00 x 1</small>
                            </div>
                            <div class="d-flex align-items-center ms-2">
                                <button class="btn btn-sm btn-light px-2"><i class="fas fa-minus"></i></button>
                                <span class="mx-2">1</span>
                                <button class="btn btn-sm btn-light px-2"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <!-- Static footer -->
                <div class="pt-3 border-top mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total:</span>
                        <strong class="text-dark">₱1,800.00</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 align-items-center">
                        <span>Discount:</span>
                        <input type="text" class="form-control form-control-sm w-50 text-end" placeholder="₱0.00">
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Amount Tendered:</span>
                        <input type="text" class="form-control form-control-sm w-50 text-end" placeholder="₱0.00">
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Change:</span>
                        <strong class="text-success">₱0.00</strong>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary w-100 primary-radius"><i class="fas fa-shopping-cart"></i> Checkout</button>
                        <button class="btn btn-outline-primary mt-2 w-100">Next</button>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <?php echo $__env->make('script.masterScriptCashier', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\ease-pos\resources\views/layouts/master-cashier.blade.php ENDPATH**/ ?>