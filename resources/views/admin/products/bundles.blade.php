@extends('layouts.master')

@section('body')
@include('layouts.formStyle')
<style>
        .product-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }
    .product-row input[type="text"] {
        flex: 1; /* product name takes full available width */
    }
    .product-row input[type="number"] {
        width: 80px; /* quantity fixed width */
    }

</style>
<div class="container-fluid">
    <div class="row">
        <!-- Bundle Info + Add Item -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">Add Product to Bundle</div>
                <div class="card-body bg-form">
                    <form method="POST" action="{{ route('bundleCreate') }}">
                        @csrf

                        <!-- Bundle Name -->
                        <div class="form-group mb-2">
                            <label for="bundle_name">Bundle Name</label>
                            <input type="text" name="bundle_name" id="bundle_name" class="form-control form-control-sm" required>
                        </div>

                        <div class="form-group mb-2">
                            <label for="product_select">Select Products</label>
                            <select id="product_select" class="form-control form-control-sm select2">
                                <option value="">-- Select Product --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-name="{{ $product->product_name }}" 
                                            data-capital="{{ $product->r_capital }}"
                                            data-price="{{ $product->r_price }}">
                                        {{ $product->product_name }} {{ $product->r_price }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="selected_products_inputs" class="mb-2"></div>

                        <div class="form-group mb-2">
                            <label for="more_details">More Details</label>
                            <textarea name="more_details" id="more_details" class="form-control form-control-sm" rows="2" cols="" required> 

                            </textarea>
                        </div>

                        <div class="form-group mb-2">
                            <label for="bundle_capital">Bundle Capital</label>
                            <input type="number" name="r_capital" id="r_capital" class="form-control form-control-sm" required readonly>
                        </div>

                        <div class="form-group mb-2">
                            <label for="bundle_capital">Quantity</label>
                            <input type="number" name="quantity" id="quantity" min="1" class="form-control form-control-sm" required>
                        </div>

                        <div class="form-group mb-2">
                            <label for="bundle_price">Bundle Price</label>
                            <input type="number" name="r_price" id="bundle_price" class="form-control form-control-sm" required readonly>
                        </div>

                        <div class="form-group mb-2">
                            <label for="bundle_alert">Stock Alert</label>
                            <input type="number" step="0.01" name="r_stock_alert" class="form-control form-control-sm" id="r_stock_alert" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-plus"></i> Create Bundle
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bundle Items Table -->
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">Bundle Items</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" width="100%">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bundleitems as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->rqty }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('bundleRemoveItem', $item->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#product_select').select2();

    const container = document.getElementById('selected_products_inputs');
    const bundlePriceInput = document.getElementById('bundle_price');
    const bundleCapitalInput = document.getElementById('r_capital');
    const moreDetailsTextarea = document.getElementById('more_details');

    function recalcTotals() {
        let totalPrice = 0;
        let totalCapital = 0;
        let productNames = [];

        container.querySelectorAll('.product-row').forEach(row => {
            const qty = parseFloat(row.querySelector('input[name="rqty[]"]').value) || 0;
            const price = parseFloat(row.querySelector('input[name="product_price[]"]').value) || 0;
            const capital = parseFloat(row.querySelector('input[name="product_capital[]"]').value) || 0;
            const name = row.querySelector('input[name="product_name[]"]').value;

            totalPrice += qty * price;
            totalCapital += qty * capital;
            productNames.push(name);
        });

        bundlePriceInput.value = totalPrice.toFixed(2);
        bundleCapitalInput.value = totalCapital.toFixed(2);
        moreDetailsTextarea.value = productNames.join("\n");
    }

    $('#product_select').on('select2:select', function (e) {
        const selectedOption = e.params.data.element;
        const productId = selectedOption.value;
        const productName = selectedOption.dataset.name;
        const productPrice = selectedOption.dataset.price;
        const productCapital = selectedOption.dataset.capital;

        if (!productId) return;

        // Prevent duplicates
        if (container.querySelector(`.product-row[data-id="${productId}"]`)) {
            alert('Product already added!');
            $(this).val(null).trigger('change'); // reset select2
            return;
        }

        // Create row
        const row = document.createElement('div');
        row.classList.add('product-row', 'd-flex', 'gap-2', 'mb-2');
        row.dataset.id = productId;

        row.innerHTML = `
            <input type="hidden" name="product_id[]" value="${productId}">
            <input type="hidden" name="product_price[]" value="${productPrice}">
            <input type="hidden" name="product_capital[]" value="${productCapital}">
            <input type="text" name="product_name[]" value="${productName}" class="form-control form-control-sm" readonly>
            <input type="number" name="rqty[]" value="1" min="1" class="form-control form-control-sm qty-input" style="width: 80px;">
            <button type="button" class="btn btn-danger btn-sm remove-btn">&times;</button>
        `;

        container.appendChild(row);

        recalcTotals();

        // Reset select2
        $(this).val(null).trigger('change');
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-btn')) {
            e.target.closest('.product-row').remove();
            recalcTotals();
        }
    });

    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('qty-input')) {
            recalcTotals();
        }
    });
});
</script>

@endsection
