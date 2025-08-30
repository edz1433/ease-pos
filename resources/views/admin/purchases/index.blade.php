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
                        <b>PURCHASES</b>
                    </h2>
                    <a href="{{ route('purchaseForm') }}" class="btn bg-main text-light btn-sm" style="float: right;"><i class="fas fa-plus fa-xs"></i> PURCHASE</a>
                </div>
                <div class="card-body"> 
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="table-responsive">
                                <table id="example3" class="table table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="30" class="text-center">#</th>
                                            <th class="text-center">Purchase No.</th>
                                            <th class="text-center">Supplier</th>
                                            <th class="text-center">PO Number</th>
                                            <th class="text-center">Purchase Date</th>
                                            <th class="text-center">Payment Info</th>
                                            <th class="text-center">Total Amount</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purchases as $index => $purchase)
                                            <tr>
                                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                                <td class="text-center align-middle">{{ $purchase->transaction_no }}</td>
                                                <td class="text-center align-middle">{{ $purchase->supplier_name ?? 'N/A' }}</td>
                                                <td class="text-center align-middle">{{ $purchase->po_number ?? '-' }}</td>
                                                <td class="text-center align-middle">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('M d, Y') ?? '-' }}</td>
                                                <td class="text-left align-middle">
                                                    <b>Mode:</b> {{ $purchase->payment_mode ?? '-' }}<br>
                                                    @if($purchase->payment_mode === 'Credit')
                                                        <b>Due:</b> {{ \Carbon\Carbon::parse($purchase->due_date)->format('M d, Y') ?? '-' }}<br>
                                                    @elseif($purchase->payment_mode === 'Postdated Check')
                                                        <b>Check Date:</b> {{ \Carbon\Carbon::parse($purchase->check_date)->format('M d, Y') ?? '-' }}<br>
                                                        <b>Bank:</b> {{ $purchase->bank_name ?? '-' }}<br>
                                                        <b>Account:</b> {{ $purchase->account_name ?? '-' }}<br>
                                                        <b>Check No:</b> {{ $purchase->check_number ?? '-' }}
                                                    @endif
                                                </td>
                                                <td class="text-danger align-middle"><b>{{ number_format($purchase->total_amount, 2) }}</b></td>
                                                <td class="text-center align-middle">
                                                    @if($purchase->payment_status == "paid")
                                                        <span class="badge badge-success align-middle">paid</span>
                                                    @else
                                                        <span class="badge badge-warning align-middle">unpaid</span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button type="button" class="btn btn-info btn-sm edit-btn" data-id="{{ $purchase->id }}">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                    <button value="{{ $purchase->id }}" class="btn btn-danger btn-sm delete-row" data-model="PurchaseItem" data-id="{{ $purchase->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
