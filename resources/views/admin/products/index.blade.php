@extends('layouts.master')

@section('body')
@include('layouts.formStyle')
<style>
.product-img {
    width: 60px !important;
    height: 60px !important;
    border-radius: 5% !important;
}
.action-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    margin: 2px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.action-btn i {
    font-size: 14px;
    line-height: 1;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-gray mb-0">
                        <b>PRODUCTS</b>
                    </h2>
                </div>
                <div class="card-body">
                    <div class="row transition-row">
                        <!-- Product Form Column -->
                        <div class="col-lg-3">
                            <div class="panel panel-default">
                                <div class="panel-heading" id="formHeading">ADD PRODUCT</div>
                                <div class="panel-body bg-form">
                                    <form class="p-2" id="product_form_data" method="POST" enctype="multipart/form-data" action="{{ route('storeOrUpdate') }}">
                                        @csrf
                                        <input type="hidden" name="_method" id="form_method" value="POST">
                                        <input type="hidden" name="id" id="product_id">
                                        <input type="hidden" name="remove_image" id="remove_image" value="0">

                                        <div class="row g-3">
                                            <!-- Retail & Wholesale Barcode -->
                                            <div class="col-md-6">
                                                <label for="barcode">Retail Barcode 
                                                    <i class="fas fa-sync" onclick="generateBarcode('barcode')"></i>
                                                </label>
                                                <input type="text" name="barcode" class="form-control form-control-sm" id="barcode"
                                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                                                    onkeydown="if(event.key === 'Enter'){event.preventDefault();}" required>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="w_barcode">Wholesale Barcode 
                                                    <i class="fas fa-sync" onclick="generateBarcode('w_barcode')"></i>
                                                </label>
                                                <input type="text" name="w_barcode" class="form-control form-control-sm" id="w_barcode"
                                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                                                    onkeydown="if(event.key === 'Enter'){event.preventDefault();}">
                                                <div class="invalid-feedback"></div>
                                            </div>

                                            <!-- Product Name & Model -->
                                            <div class="col-md-12">
                                                <label for="product_name">Product Name</label>
                                                <input type="text" name="product_name" class="form-control form-control-sm" id="product_name" required>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="model">Model</label>
                                                <input type="text" name="model" class="form-control form-control-sm" id="model">
                                                <div class="invalid-feedback"></div>
                                            </div>

                                            <!-- More Details & Product Type -->
                                            <div class="col-md-12">
                                                <label for="more_details">More Details</label>
                                                <textarea name="more_details" rows="2" class="form-control form-control-sm" id="more_details"></textarea>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="product_type">Product Type</label>
                                                <select name="product_type" id="product_type" class="form-control form-control-sm" required>
                                                    <option value="" disabled selected>-- Select Type --</option>
                                                    <option value="1">Standard</option>
                                                    <option value="2">Made to Order</option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>

                                            <!-- Category & Packaging -->
                                            <div class="col-md-6">
                                                <label for="category">Category</label>
                                                <select name="category" id="category" class="form-control form-control-sm" required>
                                                    <option value="" disabled selected>-- Select Category --</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="packaging">Packaging</label>
                                                <input type="number" name="packaging" class="form-control form-control-sm" id="packaging" min="1" step="1" required>
                                                <div class="invalid-feedback"></div>
                                            </div>

                                            <!-- Wholesale Capital & Price -->
                                            <div class="col-md-6">
                                                <label for="whole_capital">Wholesale Capital</label>
                                                <input type="number" step="0.01" name="w_capital" class="form-control form-control-sm" id="whole_capital" min="0" required>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="whole_price">Wholesale Price</label>
                                                <input type="number" step="0.01" name="w_price" class="form-control form-control-sm" id="whole_price" min="0" required>
                                                <div class="invalid-feedback"></div>
                                            </div>

                                            <!-- Wholesale & Retail Unit -->
                                            <div class="col-md-6">
                                                <label for="wholesale_unit">Wholesale Unit</label>
                                                <select name="w_unit" class="form-control form-control-sm" id="wholesale_unit">
                                                    <option value="" disabled selected>-- Select Unit --</option>
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="retail_unit">Retail Unit</label>
                                                <select name="r_unit" class="form-control form-control-sm" id="retail_unit" required>
                                                    <option value="" disabled selected>-- Select Unit --</option>
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>

                                            <!-- Retail Capital & Price -->
                                            <div class="col-md-6">
                                                <label for="retail_capital">Retail Capital</label>
                                                <input type="number" step="0.01" name="r_capital" class="form-control form-control-sm" id="retail_capital" min="0" required>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="retail_price">Retail Price</label>
                                                <input type="number" step="0.01" name="r_price" class="form-control form-control-sm" id="retail_price" min="0" required>
                                                <div class="invalid-feedback"></div>
                                            </div>

                                            <!-- Stock Alerts -->
                                            <div class="col-md-6">
                                                <label for="r_stock_alert">Retail Stock Alert</label>
                                                <input type="number" name="r_stock_alert" class="form-control form-control-sm" id="r_stock_alert" min="0" step="1" required>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="w_stock_alert">Wholesale Stock Alert</label>
                                                <input type="number" name="w_stock_alert" class="form-control form-control-sm" id="w_stock_alert" min="0" step="1">
                                                <div class="invalid-feedback"></div>
                                            </div>

                                            <!-- Warranty & Replacement Duration -->
                                            <div class="col-md-12">
                                                <label for="warranty">Warranty</label>
                                                <input type="text" name="warranty" class="form-control form-control-sm" id="warranty">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="rep_duration">Replacement Duration</label>
                                                <input type="text" name="rep_duration" class="form-control form-control-sm" id="rep_duration">
                                                <div class="invalid-feedback"></div>
                                            </div>

                                            <!-- Product Image -->
                                            <div class="col-md-12">
                                                <label for="image">Image</label>
                                                <input type="file" name="image" class="form-control form-control-sm" id="image" accept="image/jpeg,image/png,image/jpg,image/gif">
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" class="btn bg-main-7 text-light mt-3 w-100" id="saveBtn">
                                            <i class="fas fa-save"></i> Save Product
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>  

                        <!-- Product Table Column -->
                        <div class="col-lg-9">
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover product-table" width="100%">
                                        <thead class="text-dark">
                                            <tr>
                                                <th>Image</th>
                                                <th>Product</th>
                                                <th>Barcodes</th>
                                                <th>Packaging</th>
                                                <th>Wholesale</th>
                                                <th>Retail</th>
                                                <th>Sold</th>
                                                <th class="text-center align-middle">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if(auth()->user()->role == 1)
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="info-box bg-light shadow-sm">
                                            <span class="info-box-icon bg-success elevation-1">
                                                <i class="fas fa-coins"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text text-muted">Total Capital</span>
                                                <span class="info-box-number text-dark">
                                                    ₱{{ number_format($totals->total_capital ?? 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <div class="info-box bg-light shadow-sm">
                                            <span class="info-box-icon bg-info elevation-1">
                                                <i class="fas fa-tags"></i>
                                            </span>
                                            <div class="info-box-content">
                                                <span class="info-box-text text-muted">Total Price</span>
                                                <span class="info-box-number text-dark">
                                                    ₱{{ number_format($totals->total_price ?? 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Adjustment Modal -->
<div class="modal fade" id="stockAdjustmentModal" tabindex="-1" role="dialog" aria-labelledby="stockAdjustmentLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <form id="stockAdjustmentForm" method="POST" action="{{ route('stockAdjustmentCreate') }}">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="stockAdjustmentLabel">Stock Management</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <input type="hidden" name="product_id" id="adjustment_product_id">
            <div class="form-group">
                <p id="adjustment_product_name" class="form-control-plaintext font-weight-bold"></p>
            </div>

            <div class="form-group">
                <label for="type">Price Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="retail">Retail</option>
                    <option value="wholesale">Wholesale</option>
                </select>
            </div>

            <div class="form-group">
                <label for="adjustment_type">Adjustment Type</label>
                <select name="adjustment_type" id="adjustment_type" class="form-control" required>
                    <option value="">-- Select --</option>

                    <!-- Group: Add Stock -->
                    <optgroup label="Add Stock">
                        <option value="restock">Restock</option>
                        <option value="return">Return</option>
                        <option value="inventory">Inventory Count</option>
                        <option value="adjustment">Manual Adjustment</option>
                    </optgroup>

                    <!-- Group: Deduct Stock -->
                    <optgroup label="Deduct Stock">
                        <option value="sale">Sale</option>
                        <option value="damage">Damaged</option>
                        <option value="expired">Expired</option>
                        <option value="lost">Lost</option>
                        <option value="transfer">Transfer (Branch/Warehouse)</option> <!-- NEW -->
                    </optgroup>
                </select>
            </div>

            <div class="form-group">
            <label for="price_type">Price Type</label>
            <select name="price_type" id="price_type" class="form-control">
                <option value="retail">Retail</option>
                <option value="wholesale">Wholesale</option>
                <option value="special">Special</option>
            </select>
            </div>

            <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" step="0.01" name="quantity" id="quantity" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="reason">Reason</label>
                <select name="reason" id="reason" class="form-control">
                    <option value="">-- Select Reason --</option>

                    <!-- Group: Add Stock -->
                    <optgroup label="Add Stock">
                        <option value="Customer Return">Customer Return</option>
                        <option value="Stock Count">Stock Count</option>
                        <option value="Manual Adjustment">Manual Adjustment</option>
                        <option value="Others">Others</option>
                    </optgroup>

                    <!-- Group: Deduct Stock -->
                    <optgroup label="Deduct Stock">
                        <option value="Damaged">Damaged</option>
                        <option value="Expired">Expired</option>
                        <option value="Lost">Lost</option>
                        <option value="Transfer">Transfer</option> <!-- NEW -->
                    </optgroup>
                </select>
            </div>

            <!-- Branch Transfer Selection -->
            <div class="form-group" id="branchTransferGroup" style="display: none;">
            <label for="branch_id">Transfer To</label>
            <select name="branch_id" id="branch_id" class="form-control">
                @foreach($branches as $index => $branch)
                    <option value="{{ $branch->id }}" {{ $index == 0 ? 'selected' : '' }}>
                        {{ $branch->branch_name }} ({{ $branch->type }})
                    </option>
                @endforeach
            </select>
            </div>

            <div class="form-group" id="transNumberGroup" style="display: none;">
            <label for="trans_number">Transaction Number</label>
            <input type="number" name="trans_number" id="trans_number" class="form-control">
            </div>

            <div class="form-group" id="saleIdGroup" style="display: none;">
            <label for="sale_id">Sale ID (if return or refund)</label>
            <input type="number" name="sale_id" id="sale_id" class="form-control">
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn bg-main-7 text-light">Save Adjustment</button>
        </div>
        </form>
    </div>
  </div>
</div>

@endsection