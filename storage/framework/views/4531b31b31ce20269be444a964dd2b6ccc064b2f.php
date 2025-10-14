

<?php $__env->startSection('body'); ?>
<style>
    .bg-white {
        border-radius: 25px;
    }
    .icon {
        position: absolute;
        top: 37px !important;
        right: 5px;
    }
    .border-radius {
        border-radius: 8px !important;
        width: 40px !important;
        height: 40px !important;
    }
</style>

<div class="container-fluid">
    <div class="wrapper">
        <section class="content">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box d-flex justify-content-between">
                        <div class="info-box-content text-left">
                            <span class="info-box-text">Total Sales</span>
                            <span class="info-box-number">₱<?php echo e(number_format($totalSales, 2)); ?></span>
                        </div>
                        <span class="info-box-icon bg-main-4 elevation-1"><i class="fas fa-money-bill-wave text-light"></i></span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box d-flex justify-content-between">
                        <div class="info-box-content text-left">
                            <span class="info-box-text">Total Purchases</span>
                            <span class="info-box-number">₱<?php echo e(number_format($totalPurchases, 2)); ?></span>
                        </div>
                        <span class="info-box-icon bg-main-3 elevation-1"><i class="fas fa-cart-plus text-light"></i></span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box d-flex justify-content-between">
                        <div class="info-box-content text-left">
                            <span class="info-box-text">Total Expenses</span>
                            <span class="info-box-number">₱<?php echo e(number_format($totalExpenses, 2)); ?></span>
                        </div>
                        <span class="info-box-icon bg-main-2 elevation-1"><i class="fas fa-file-invoice-dollar text-light"></i></span>
                    </div>
                </div>


                <div class="col-6 col-sm-6 col-md-3">
                    <div class="info-box d-flex justify-content-between">
                        <div class="info-box-content text-left">
                            <span class="info-box-text">Total Profit</span>
                            <span class="info-box-number">0.00</span>
                        </div>
                        <span class="info-box-icon bg-main-1 elevation-1">
                            <i class="fas fa-coins text-light"></i>
                        </span>
                    </div>
                </div>
                
                <div class="col-12 mb-3">
                    <div class="float-right">
                        <form id="filterForm" class="d-inline-flex align-items-center">
                            <select name="filter" id="filterSelect" class="form-control form-control-sm mr-1">
                                <option value="all">All</option>
                                <option value="day">Today</option>
                                <option value="week">This Week</option>
                                <option value="month" selected>This Month</option>
                                <option value="year">This Year</option>
                                <option value="custom">Custom Range</option>
                            </select>

                            <select name="category" id="category" class="form-control form-control-sm mr-1">
                                <option value="All">All</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            <input type="date" name="start_date" id="startDate"
                                class="form-control form-control-sm mr-1" style="display:none;">

                            <input type="date" name="end_date" id="endDate"
                                class="form-control form-control-sm mr-1" style="display:none;">

                            <button type="submit" class="btn btn-sm bg-main text-light">
                                <i class="fas fa-filter"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0 d-flex justify-content-between">
                            <h3 class="card-title">Top Products</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <ul id="topProductsList" class="list-group list-group-flush"></ul>
                                </div>

                                <div class="col-9">
                                    <div class="chart">
                                        <canvas id="topProducts" height="250"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header border-0 d-flex justify-content-between">
                            <h3 class="card-title">Top Category</h3>
                        </div>
                        <div style="height: 380px;">
                            <canvas id="categoryPie"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0 d-flex justify-content-between">
                            <h3 class="card-title">Gross Profit</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="grossSalesAnalyticsChart" height="280"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0 d-flex justify-content-between">
                            <h3 class="card-title">Gross Profit</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="netSalesAnalyticsChart" height="280"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="text-center my-4">
    <h1 id="countdown" style="font-size: 48px; font-weight: bold;" class="text-danger"></h1>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\ease-pos\resources\views/admin/dashboard/index.blade.php ENDPATH**/ ?>