@extends('layouts.master')

@section('body')
<style>
    .bg-form{
        background-color:  #e9ecef;
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
    .btn-sm{
        font-size: 10px !important;
        height: 25px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .bb{
        border-bottom: 1px solid rgb(145, 138, 138);
    }
</style>
<div class="container-fluid">
    <div class="row">
        <!-- User Table Column -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">USER LIST</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example3" class="table table-bordered table-hover" width="100%">
                            <thead>
 <tr>
                                        <th>Date</th>
                                        <th>Transaction #</th>
                                        <th>Customer</th>
                                        <th>Table No</th>
                                        <th>Total Amount</th>
                                        <th>VAT</th>
                                        <th>Discount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Cashier</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $sale)
                         
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection