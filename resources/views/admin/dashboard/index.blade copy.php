@extends('layouts.master')

@section('body')
@php $role = auth()->user()->isAdmin; @endphp

<div class="container-fluid">
    <div class="row">

        {{-- Info Boxes --}}
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Sales</span>
                    <span class="info-box-number">10 <small>%</small></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Expenses</span>
                    <span class="info-box-number">₱41,410</span>
                </div>
            </div>
        </div>

        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Sales</span>
                    <span class="info-box-number">760</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Staff</span>
                    <span class="info-box-number">10</span>
                </div>
            </div>
        </div>

        {{-- Monthly Recap Card --}}
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-uppercase text-dark">Monthly Sales Recap</h5>
                    <div class="card-tools d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-tool dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-wrench"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item">Download PDF</a>
                                <a href="#" class="dropdown-item">Export Excel</a>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="text-center font-weight-bold text-secondary">
                                <strong>Sales Report: {{ now()->startOfMonth()->format('F d, Y') }} - {{ now()->format('F d, Y') }}</strong>
                            </p>
                            <div class="chart">
                                <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <p class="text-center font-weight-bold text-secondary">Goal Completion</p>
                            @php
                                $goals = [
                                    ['label' => 'Total Orders', 'value' => 320, 'total' => 500, 'color' => '#fc204f'],
                                    ['label' => 'Completed Payments', 'value' => 400, 'total' => 500, 'color' => '#c3183e'],
                                    ['label' => 'Delivery Success', 'value' => 300, 'total' => 400, 'color' => '#fd6d8a'],
                                    ['label' => 'Customer Inquiries Resolved', 'value' => 150, 'total' => 200, 'color' => '#20fcc2'],
                                ];
                            @endphp

                            @foreach($goals as $goal)
                                @php $percentage = ($goal['value'] / $goal['total']) * 100; @endphp
                                <div class="progress-group">
                                    {{ $goal['label'] }}
                                    <span class="float-right"><b>{{ $goal['value'] }}</b>/{{ $goal['total'] }}</span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar" style="width: {{ $percentage }}%; background-color: {{ $goal['color'] }};"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="row text-center">
                        <div class="col-sm-3 col-6 border-right">
                            <div class="description-block">
                                <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>
                                <h5 class="description-header text-success">₱35,210.43</h5>
                                <span class="description-text">TOTAL REVENUE</span>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6 border-right">
                            <div class="description-block">
                                <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
                                <h5 class="description-header text-warning">₱10,390.90</h5>
                                <span class="description-text">TOTAL COST</span>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6 border-right">
                            <div class="description-block">
                                <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                                <h5 class="description-header text-success">₱24,813.53</h5>
                                <span class="description-text">TOTAL PROFIT</span>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6">
                            <div class="description-block">
                                <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
                                <h5 class="description-header text-danger">1200</h5>
                                <span class="description-text">GOAL COMPLETIONS</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales Overview --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0 d-flex justify-content-between">
                    <h3 class="card-title">Restaurant Sales Overview</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">₱120,760.00</span>
                            <span>Total Sales ({{ now()->format('F Y') }})</span>
                        </p>
                        <p class="text-right">
                            <span class="text-success">
                                <i class="fas fa-arrow-up"></i> 6.2%
                            </span><br>
                            <span class="text-muted">Compared to last month</span>
                        </p>
                    </div>
                    <div class="chart">
                        <canvas id="salesSummaryChart" height="250"></canvas>
                    </div>
                    <div class="d-flex flex-row justify-content-end mt-2">
                        <span class="mr-3"><i class="fas fa-square text-primary"></i> Today</span>
                        <span class="mr-3"><i class="fas fa-square text-success"></i> This Week</span>
                        <span><i class="fas fa-square text-warning"></i> This Month</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top 5 Sales Items --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0 d-flex justify-content-between">
                    <h3 class="card-title">Top 5 Sales Items</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item</th>
                                <th>Qty Sold</th>
                                <th>Total Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>1.</td><td>Grilled Chicken</td><td>150</td><td>₱15,000.00</td></tr>
                            <tr><td>2.</td><td>Pasta Alfredo</td><td>120</td><td>₱12,000.00</td></tr>
                            <tr><td>3.</td><td>Cheeseburger</td><td>100</td><td>₱10,000.00</td></tr>
                            <tr><td>4.</td><td>Fried Rice</td><td>95</td><td>₱9,500.00</td></tr>
                            <tr><td>5.</td><td>Fruit Shake</td><td>90</td><td>₱6,300.00</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
