@extends('layouts.master')

@section('body')
<style>
    .bg-form {
        background-color: #e9ecef;
    }
    .form-control:disabled, .form-control[readonly] {
        background-color: #ffffff;
        opacity: 1;
    }
    .form-control-sm {
        height: calc(1.5125rem + 2px);
        padding: .15rem .5rem;
        font-size: .750rem;
        line-height: 1.5;
        border-radius: .2rem;
        background-color: #ffffff !important;
    }
    .btn-sm {
        font-size: 10px !important;
        height: 25px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .bb {
        border-bottom: 1px solid rgb(145, 138, 138);
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-gray">
                        <b>CASH COUNT LIST</b>
                    </h2>
                </div>
                <div class="card-body"> 
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="table-responsive">
                                <table id="example3" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 20px;" class="text-center">ID</th>
                                            <th>Total Inflow</th>
                                            <th>Total Outflow</th>
                                            <th>Total Sales Today</th>
                                            <th>Variance</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cashCounts as $cash)
                                        <tr>
                                            <td class="text-center align-middle text-main-8 font-weight-bold">{{ $cash->id }}</td>
                                            <td class="align-middle text-main-1 font-weight-bold">{{ number_format($cash->total_inflow, 2) }}</td>
                                            <td class="align-middle text-main-1 font-weight-bold">{{ number_format($cash->total_outflow, 2) }}</td>
                                            <td class="align-middle text-main-1 font-weight-bold">{{ number_format($cash->total_sales_today, 2) }}</td>
                                            <td class="align-middle text-main-1 font-weight-bold">{{ $cash->variance }}</td>
                                            <td class="align-middle text-main-8 font-weight-bold">{{ $cash->created_at->format('Y-m-d H:i') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('cashCountEntry', $cash->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</div>
@endsection
