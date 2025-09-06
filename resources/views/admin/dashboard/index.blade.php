@extends('layouts.master')

@section('body')
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
                            <span class="info-box-number">₱{{ number_format($totalSales, 2) }}</span>
                        </div>
                        <span class="info-box-icon bg-main-4 elevation-1"><i class="fas fa-money-bill-wave text-light"></i></span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box d-flex justify-content-between">
                        <div class="info-box-content text-left">
                            <span class="info-box-text">Total Purchases</span>
                            <span class="info-box-number">₱{{ number_format($totalPurchases, 2) }}</span>
                        </div>
                        <span class="info-box-icon bg-main-3 elevation-1"><i class="fas fa-cart-plus text-light"></i></span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box d-flex justify-content-between">
                        <div class="info-box-content text-left">
                            <span class="info-box-text">Total Expenses</span>
                            <span class="info-box-number">₱{{ number_format($totalExpenses, 2) }}</span>
                        </div>
                        <span class="info-box-icon bg-main-2 elevation-1"><i class="fas fa-file-invoice-dollar text-light"></i></span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
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

                {{-- Sales Overview --}}
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header border-0 d-flex justify-content-between">
                            <h3 class="card-title">Top Products</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3"> <!-- smaller width -->
                                    <ul class="list-group list-group-flush"> <!-- removes card borders/padding -->
                                        @foreach($topProducts as $key => $product)
                                            <li class="list-group-item py-1 px-2" style="font-size: 0.7rem;">
                                                {{ $key + 1 }}. {{ $product->product_name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="col-9"> <!-- chart takes more space -->
                                    <div class="chart">
                                        <canvas id="topProducts" height="250"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
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
@endsection
