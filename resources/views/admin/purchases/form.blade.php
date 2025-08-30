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
    .create-purchase-btn {
        font-size: 22px;
        color: #fc204f; /* Bootstrap success green */
        transition: all 0.3s ease-in-out;
        padding: 40px;
        border: 2px dashed #fc204f;
        border-radius: 20px;
        text-align: center;
    }

    .create-purchase-btn i {
        font-size: 80px;
        transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
    }

    .create-purchase-btn:hover {
        background: #fc204f;
        color: #fff;
        border-style: solid;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .create-purchase-btn:hover i {
        color: #fff;
        transform: scale(1.1) rotate(5deg);
    }

    .create-purchase-btn span {
        font-weight: 600;
    }

    .input-custom{
        height: 31px !important;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-gray mb-0">
                        <b>PURCHASES</b>
                    </h2>
                    <br>
                    <ol class="breadcrumb m-0 p-0" style="background: none; font-size: 11px;">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('purchaseRead') }}">Purchases</a>
                        </li>
                        <li class="breadcrumb-item active">Form</li>
                    </ol>
                </div>
            
                @if(empty($purchasecheck))
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center" style="height: 300px; align-items: center;">
                            <a class="d-flex flex-column justify-content-center align-items-center text-decoration-none create-purchase-btn" data-toggle="modal" data-target="#createPurchaseModal">
                                <i class="fas fa-plus-circle mb-3"></i>
                                <span>Create New Purchase</span>
                            </a>
                        </div>
                    </div>
                @else 
                    <div class="card-body">
                        <form method="POST" action="{{ route('purchaseStoreItem') }}">
                            @csrf
                            <div class="row">
                                {{-- Product --}}
                                <div class="col-4">
                                    <select class="form-control select2" name="product_id" id="product_id" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $product)
                                            {{-- Retail row --}}
                                            <option
                                                value="{{ $product->id }}"
                                                data-product-id="{{ $product->id }}"
                                                data-name="{{ $product->product_name }}"
                                                data-type="Retail"
                                                data-price="{{ $product->r_capital }}"
                                                data-selling-price="{{ $product->r_price }}"
                                            >
                                                {{ $product->barcode }} - {{ $product->product_name }} - 
                                                {{ number_format($product->r_capital, 2) }} (Retail)
                                            </option>

                                            {{-- Wholesale row --}}
                                            @if($product->packaging > 1)
                                                <option
                                                    value="{{ $product->id }}"
                                                    data-product-id="{{ $product->id }}"
                                                    data-name="{{ $product->product_name }}"
                                                    data-type="Wholesale"
                                                    data-price="{{ $product->w_capital }}"
                                                    data-selling-price="{{ $product->w_price }}"
                                                >
                                                    {{ $product->barcode }} - {{ $product->product_name }} - 
                                                    {{ number_format($product->w_capital, 2) }} (Wholesale)
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Price --}}
                                <div class="col-2">
                                    <input type="number" name="price" id="product_price" 
                                        class="form-control form-control-sm input-custom" 
                                        step="0.01" placeholder="Price" required>
                                </div>

                                {{-- Selling Price --}}
                                <div class="col-2">
                                    <input type="number" name="selling_price" id="selling_price" 
                                        class="form-control form-control-sm input-custom" 
                                        step="0.01" placeholder="Selling Price" required>
                                </div>

                                {{-- Quantity --}}
                                <div class="col-2">
                                    <input type="number" name="quantity" id="quantity"
                                        class="form-control form-control-sm input-custom"
                                        min="1" value="1" placeholder="Qty" required>

                                    <input type="hidden" name="price_type" id="price_type">
                                    <input type="hidden" name="purchase_id" value="{{ $purchasecheck->id }}">
                                    <input type="hidden" name="subtotal" id="subtotal">
                                </div>

                                {{-- Add button --}}
                                <div class="col-2">
                                    <button type="submit" class="btn bg-info btn-sm w-100 input-custom">
                                        <i class="fas fa-plus fa-xs"></i> Add
                                    </button>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="card-body table-responsive p-0" style="height: 400px;">
                            <div class="card-header">
                                <h3 class="card-title text-success">#: <b>{{ $purchasecheck->transaction_no }}</b></h3>
                                <div class="card-tools mb-1">
                                    <div class="input-group input-group-sm" style="width: 200px;">
                                        <input type="text" id="table-search" name="table_search" class="form-control float-right" placeholder="Search">

                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <table id="purchase-table" class="table table-head-fixed text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Selling price</th>
                                            <th>Qty</th>
                                            <th>Sub total</th>
                                            <th width="50">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $totalCost = 0; 
                                            $totalSelling = 0; 
                                        @endphp
                                        @foreach ($purchaseitems as $item)
                                            @php
                                                $subtotal = $item->price * $item->quantity;
                                                $subtotalSelling = ($item->selling_price ?? 0) * $item->quantity;
                                                $totalCost += $subtotal;
                                                $totalSelling += $subtotalSelling;
                                            @endphp
                                            <tr id="row-{{ $item->id }}">
                                                <td>{{ $item->product_name }}</td>
                                                <td>{{ number_format($item->price, 2) }}</td>
                                                <td>{{ number_format($item->selling_price, 2) }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->subtotal, 2) }}</td>
                                                <td>
                                                    <button value="{{ $item->id }}" class="btn btn-danger btn-sm delete-row" data-model="PurchaseItem" data-id="{{ $item->id }}">
                                                        <i class="fas fa-trash fa-xs"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="1" class="text-right">TOTAL:</th>
                                            <th id="totalCostDisplay" class="text-danger">{{ number_format($totalCost, 2) }}</th>
                                            <th class="text-success">{{ number_format($totalSelling, 2) }}</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-3 mb-2">
                            <button type="button" class="btn btn-outline-danger me-2 mr-1 cancel-btn" data-id="{{ $purchasecheck->id }}">
                                <i class="fas fa-times-circle me-1"></i> Cancel
                            </button>
                            <button type="button" class="btn bg-success" data-toggle="modal" data-target="#savePurchaseModal">
                                <i class="fas fa-save"></i> Save Purchase
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
     </div>
</div>
<!-- Create Purchase Modal -->
<div class="modal fade" id="createPurchaseModal" role="dialog" aria-labelledby="createPurchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="createPurchaseModalLabel">
                    <i class="fas fa-plus-circle text-success"></i> Create New Purchase
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('purchaseCreate') }}">
                @csrf
                <div class="modal-body">
                    <!-- Purchase Date -->
                    <div class="form-group">
                        <label for="transaction_no">Transaction Number</label>
                        <input type="text" class="form-control" id="transaction_no" name="transaction_no" value="{{ $transactionNo }}" readonly>
                    </div>
                    <!-- Supplier -->
                    <div class="form-group">
                        <label for="supplier">Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="form-control select2" required>
                            <option value="">-- Select Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @php
                        date_default_timezone_set('Asia/Manila');
                        $today = date('Y-m-d');
                    @endphp
                    <!-- Purchase Date -->
                    <div class="form-group">
                        <label for="purchase_date">Purchase Date</label>
                        <input type="date" class="form-control" id="purchase_date" name="purchase_date" 
                            value="{{ $today }}" required>
                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn bg-success">
                        <i class="fas fa-save"></i> Save Purchase
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@if(!empty($purchasecheck))
    <!-- Save Purchase Modal -->
    <div class="modal fade" id="savePurchaseModal" tabindex="-1" role="dialog" aria-labelledby="savePurchaseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="purchaseForm" action="{{ route('purchasesSave', $purchasecheck->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Save Purchase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>

                <div class="modal-body">
                <div class="row">
                    <!-- Purchase Date -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="purchase_date">Purchase Date</label>
                            <input type="date" name="purchase_date" id="purchase_date" class="form-control" value="{{ $purchasecheck->purchase_date ?? '' }}" required>
                        </div>
                    </div>

                    <!-- Transaction No. -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="po_number">Transaction No.</label>
                            <input type="text" name="transaction_no" id="transaction_no" class="form-control" value="{{ $purchasecheck->transaction_no ?? '' }}" readonly>
                        </div>
                    </div>

                    <!-- Supplier -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_id">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-control" required readonly>
                                @foreach($suppliers as $supp)
                                    @if(($purchasecheck->supplier_id == $supp->id))
                                        <option value="{{ $supp->id }}" selected>{{ $supp->supplier_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- PO Number -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="po_number">PO Number</label>
                            <input type="text" name="po_number" id="po_number" class="form-control" value="{{ $purchasecheck->po_number ?? '' }}" required>
                        </div>
                    </div>

                    <!-- Payment Mode -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_mode">Payment Mode</label>
                            <select name="payment_mode" id="payment_mode" class="form-control" required>
                                <option value="Cash">Cash</option>
                                <option value="Credit">Credit</option>
                                <option value="Postdated Check">Postdated Check</option>
                            </select>
                        </div>
                    </div>

                    <!-- Total Amount -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="total_amount">Total Amount</label>
                            <input type="text" name="total_amount" id="total_amount" class="form-control" readonly>
                        </div>
                    </div>
                </div>


                <!-- Credit Fields -->
                <div id="creditFields" style="display: none;">
                    <hr>
                    <h6>Credit Details</h6>
                    <div class="form-group">
                    <label for="due_date">Payment Due Date</label>
                    <input type="date" name="due_date" id="due_date" class="form-control">
                    </div>
                </div>

                <!-- Check Fields -->
                <div id="checkFields" style="display: none;">
                    <hr>
                    <h6>Postdated Check Details</h6>
                    <div class="form-group">
                    <label for="check_number">Check Number</label>
                    <input type="text" name="check_number" id="check_number" class="form-control">
                    </div>
                    <div class="form-group">
                    <label for="bank_name">Bank Name</label>
                    <input type="text" name="bank_name" id="bank_name" class="form-control">
                    </div>
                    <div class="form-group">
                    <label for="check_date">Check Date</label>
                    <input type="date" name="check_date" id="check_date" class="form-control">
                    </div>
                    <div class="form-group">
                    <label for="account_name">Account Name</label>
                    <input type="text" name="account_name" id="account_name" class="form-control">
                    </div>
                </div>
                </div>

                <div class="modal-footer">
                <button type="submit" class="btn bg-success">Save Purchase</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@endif
@endsection
