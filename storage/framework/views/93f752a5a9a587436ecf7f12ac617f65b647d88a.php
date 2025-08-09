

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
                <div class="col-12">
                    <div class="form-group text-right"> <!-- Float right -->
                        <div class="input-group justify-content-end" style="max-width: 300px; float: right; margin-bottom: 10px;"> <!-- Limit width -->
                            <input type="text" class="form-control form-control-sm" id="reservation">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box d-flex justify-content-between">
                        <div class="info-box-content text-left">
                            <span class="info-box-text">Total Sales</span>
                            <span class="info-box-number">200,000</span>
                        </div>
                        <span class="info-box-icon bg-main-4 elevation-1"><i class="fas fa-cog text-light"></i></span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box d-flex justify-content-between">
                        <div class="info-box-content text-left">
                            <span class="info-box-text">Total Purchases</span>
                            <span class="info-box-number">₱41,410</span>
                        </div>
                        <span class="info-box-icon bg-main-3 elevation-1"><i class="fas fa-thumbs-up text-light"></i></span>
                    </div>
                </div>

                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box d-flex justify-content-between">
                        <div class="info-box-content text-left">
                            <span class="info-box-text">Total Expenses</span>
                            <span class="info-box-number">760</span>
                        </div>
                        <span class="info-box-icon bg-main-2 elevation-1"><i class="fas fa-shopping-cart text-light"></i></span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box d-flex justify-content-between">
                        <div class="info-box-content text-left">
                            <span class="info-box-text">Staff</span>
                            <span class="info-box-number">10</span>
                        </div>
                        <span class="info-box-icon bg-main-1 elevation-1"><i class="fas fa-users text-light"></i></span>
                    </div>
                </div>

                
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0 d-flex justify-content-between">
                            <h3 class="card-title">Top Products</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="topProducts" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0 d-flex justify-content-between">
                            <h3 class="card-title">Sales by Category</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="salesCategoryChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0 d-flex justify-content-between">
                            <h3 class="card-title">Monthly Sales</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="monthlySalesChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0 d-flex justify-content-between">
                            <h3 class="card-title">Profit</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="profitChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                        <div class="card-header border-0 d-flex justify-content-between">
                            <h3 class="card-title">Low Stock</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="lowStockChart" height="350"></canvas>
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ease-pos\resources\views/admin/dashboard/index.blade.php ENDPATH**/ ?>