@extends('layouts.master')

@section('body')
@include('layouts.formStyle')
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
                        <!-- Product Table Column -->
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table id="example3" class="table table-bordered table-hover" width="100%">
                                        <thead class="bg-main-9 text-dark">
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
                                            @foreach($products as $product)
                                                <tr id="row-{{ $product->id }}">
                                                    <!-- Image -->
                                                    <td class="text-center align-middle">
                                                        @if(!empty($product->image) && Storage::disk('public')->exists('uploads/products/' . $product->image))
                                                            <img src="{{ asset('storage/uploads/products/' . $product->image) }}" 
                                                                width="40" height="40" 
                                                                style="border-radius: 5%;"  
                                                                class="product-image" 
                                                                alt="Product Image">
                                                        @endif
                                                    </td>

                                                    <!-- Product Info -->
                                                    <td class="align-middle">
                                                        <strong class="text-main-8">{{ $product->product_name }}</strong><br>
                                                        <span class="text-main-1">{{ $product->category_name }}</span><br>
                                                        <small class="text-main-1">
                                                            {{-- {{ $product->product_type == '1' ? 'Standard' : 'Made to Order' }} --}}
                                                            Warranty: {{ $product->warranty }}
                                                        </small><br>
                                                        <small class="text-main-1">
                                                            Replacement duration: {{ $product->warranty }}
                                                        </small>
                                                    </td>

                                                    <!-- Barcodes -->
                                                    <td class="align-middle text-main-8">
                                                        {{ 'R-' . $product->barcode }}<br>
                                                        @if($product->w_barcode)
                                                            {{ 'W-' . $product->w_barcode }}
                                                        @endif
                                                    </td>

                                                    <!-- Packaging -->
                                                    <td class="text-center align-middle text-main-8">{{ $product->packaging }}</td>

                                                    <!-- Wholesale -->
                                                    <td class="text-center align-middle">
                                                        <small>Cap: ₱{{ number_format($product->w_capital, 2) }}</small><br>
                                                        <small>Price: ₱{{ number_format($product->w_price, 2) }}</small><br>
                                                        <small>
                                                            Qty: {{ number_format($product->wqty) }} {{ $product->w_unit_name ?? '' }}
                                                            @if($product->wqty == 0)
                                                                <span class="badge bg-danger">Out</span>
                                                            @elseif($product->wqty < 10)
                                                                <span class="badge bg-warning text-dark">Low</span>
                                                            @endif
                                                        </small>
                                                    </td>

                                                    <!-- Retail -->
                                                    <td class="text-center align-middle">
                                                        <small>Cap: ₱{{ number_format($product->r_capital, 2) }}</small><br>
                                                        <small>Price: ₱{{ number_format($product->r_price, 2) }}</small><br>
                                                        <small>
                                                            Qty: {{ number_format($product->rqty) }} {{ $product->r_unit_name ?? '' }}
                                                            @if($product->rqty == 0)
                                                                <span class="badge bg-danger">Out</span>
                                                            @elseif($product->rqty < 10)
                                                                <span class="badge bg-warning text-dark">Low</span>
                                                            @endif
                                                        </small>
                                                    </td>

                                                    <td class="text-left align-middle text-main-1">
                                                        R-{{ number_format($product->total_sold_r) ?? 0}}<br>
                                                        W-{{ number_format($product->total_sold_w) ?? 0}}
                                                    </td>

                                                    <!-- Actions -->
                                                    <td class="text-center align-middle">
                                                        <a href="javascript:void(0)" 
                                                        class="btn btn-warning btn-sm adjust-stock-btn" 
                                                        data-id="{{ $product->id }}" 
                                                        data-name="{{ $product->product_name }} {{ $product->model }}"
                                                        title="Stock Management">
                                                        <i class="fas fa-cubes"></i>
                                                        </a>
                                                        <button class="btn btn-info btn-sm edit-btn" 
                                                            data-id="{{ $product->id }}"
                                                            data-barcode="{{ $product->barcode }}"
                                                            data-w_barcode="{{ $product->w_barcode }}"
                                                            data-product_name="{{ $product->product_name }}"
                                                            data-model="{{ $product->model }}"
                                                            data-more_details="{{ $product->more_details }}"
                                                            data-product_type="{{ $product->product_type }}"
                                                            data-category="{{ $product->category }}"
                                                            data-warranty="{{ $product->warranty }}"
                                                            data-packaging="{{ $product->packaging }}"
                                                            data-rep_duration="{{ $product->rep_duration }}"
                                                            data-w_capital="{{ $product->w_capital }}"
                                                            data-w_price="{{ $product->w_price }}"
                                                            data-w_unit="{{ $product->w_unit }}"
                                                            data-r_capital="{{ $product->r_capital }}"
                                                            data-r_price="{{ $product->r_price }}"
                                                            data-r_unit="{{ $product->r_unit }}"
                                                            data-r_stock_alert="{{ $product->r_stock_alert }}"
                                                            data-w_stock_alert="{{ $product->w_stock_alert }}"
                                                            title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <a href="#" class="btn btn-danger btn-sm delete-row" data-model="Product" data-id="{{ $product->id }}" title="Delete">
                                                            <i class="fas fa-trash"></i>
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const reasonSelect = document.getElementById('reason');
    const adjustmentTypeSelect = document.getElementById('adjustment_type');
    const saleIdGroup = document.getElementById('saleIdGroup');
    const branchTransferGroup = document.getElementById('branchTransferGroup');

    // Function to toggle fields
    function toggleFields() {
        // Sale ID only for Customer Return
        if (reasonSelect.value === 'Customer Return') {
            saleIdGroup.style.display = 'block';
        } else {
            saleIdGroup.style.display = 'none';
            document.getElementById('sale_id').value = ''; // reset
        }

        // Branch Transfer if reason = Transfer OR adjustment_type = transfer
        if (reasonSelect.value === 'Transfer' || adjustmentTypeSelect.value === 'transfer') {
            branchTransferGroup.style.display = 'block';
        } else {
            branchTransferGroup.style.display = 'none';
            // document.getElementById('branch_id').value = ''; // reset
        }
    }

    // Attach listeners
    reasonSelect.addEventListener('change', toggleFields);
    adjustmentTypeSelect.addEventListener('change', toggleFields);

    // Run once on load (in case modal pre-filled)
    toggleFields();

    // Open modal with product_id
    document.querySelectorAll(".adjust-stock-btn").forEach(function (button) {
        button.addEventListener("click", function () {
            let productId = this.getAttribute("data-id");
            let productName = this.getAttribute("data-name");
            document.getElementById("adjustment_product_id").value = productId;
            document.getElementById("adjustment_product_name").textContent = productName;
            $('#stockAdjustmentModal').modal('show');
        });
    });

    // Prefill form for editing
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = document.getElementById('product_form_data');
            const productId = this.dataset.id;

            // Fill form fields
            document.getElementById('product_id').value = productId;
            document.getElementById('barcode').value = this.dataset.barcode;
            document.getElementById('w_barcode').value = this.dataset.w_barcode;
            document.getElementById('product_name').value = this.dataset.product_name;
            document.getElementById('model').value = this.dataset.model;
            document.getElementById('more_details').value = this.dataset.more_details;
            document.getElementById('product_type').value = this.dataset.product_type;
            document.getElementById('category').value = this.dataset.category;
            document.getElementById('packaging').value = this.dataset.packaging;
            document.getElementById('warranty').value = this.dataset.warranty;
            document.getElementById('rep_duration').value = this.dataset.rep_duration;
            document.getElementById('whole_capital').value = this.dataset.w_capital;
            document.getElementById('whole_price').value = this.dataset.w_price;
            document.getElementById('wholesale_unit').value = this.dataset.w_unit;
            document.getElementById('retail_capital').value = this.dataset.r_capital;
            document.getElementById('retail_price').value = this.dataset.r_price;
            document.getElementById('retail_unit').value = this.dataset.r_unit;
            document.getElementById('r_stock_alert').value = this.dataset.r_stock_alert;
            document.getElementById('w_stock_alert').value = this.dataset.w_stock_alert;

            // Update form action for update route
            form.action = "{{ route('productUpdate', ':id') }}".replace(':id', productId);
            form.querySelector('#saveBtn').innerHTML = '<i class="fas fa-save"></i> Update Product';
            document.getElementById('formHeading').textContent = 'EDIT PRODUCT';
            
            // Scroll to form
            document.getElementById('product_form_data').scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Reset form when clicking on "Add Product" (if you add such a button)
    // You might want to add a "Add New Product" button to clear the form
});
</script>
@endsection