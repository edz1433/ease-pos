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
                    <button id="toggleForm" style="float: right;" class="btn btn-outline-primary btn-sm" title="Show/Hide Form">
                        <i class="fas fa-eye fa-xs"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row transition-row">
                        <!-- Product Form Column -->
                        <div class="col-lg-3 col-md-12 product-form" style="display: none;">
                            <div class="panel panel-default">
                                <div class="panel-heading">ADD PRODUCT</div>
                                <div class="panel-body bg-form">
                                    <form class="p-2" id="product_form_data" method="POST" action="{{ isset($productsedit) ? route('productUpdate', $productsedit->id) : route('productCreate') }}" enctype="multipart/form-data">
                                        @csrf
                                        @if(isset($productsedit))
                                            <input type="hidden" name="id" value="{{ $productsedit->id }}">
                                        @endif

                                        <div class="form-group">
                                            <label for="barcode">Barcode <i class="fas fa-sync" onclick="generateBarcode()"></i></label>
                                            <input type="text" name="barcode" value="{{ $productsedit->barcode ?? '' }}" class="form-control form-control-sm" id="barcode" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            
                                            <label for="product_name">Product Name</label>
                                            <input type="text" name="product_name" value="{{ $productsedit->product_name ?? '' }}" class="form-control form-control-sm" id="product_name" required>

                                            <label for="product_name">Product Type</label>
                                            <select name="product_type" id="product_type" class="form-control form-control-sm" required>
                                                <option value="">-- Select Type --</option>
                                                <option value="1" {{ ($productsedit->product_type ?? '') == '1' ? 'selected' : '' }}>Standard</option>
                                                <option value="2" {{ ($productsedit->product_type ?? '') == '2' ? 'selected' : '' }}>Made to Order</option>
                                            </select>

                                            <label for="category">Category</label>
                                            <select name="category" id="category" class="form-control form-control-sm" required>
                                                <option value="">-- Select Category --</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" 
                                                        {{ ($productsedit->category ?? '') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>


                                            <label for="packaging">Packaging</label>
                                            <input type="number" name="packaging" value="{{ $productsedit->packaging ?? '' }}" class="form-control form-control-sm" id="packaging" required>

                                             <label for="w_capital">Wholesale Capital</label>
                                            <input type="number" step="0.01" name="w_capital" value="{{ $productsedit->w_capital ?? '' }}" class="form-control form-control-sm" id="whole_capital" required>

                                            <label for="w_price">Wholesale Price</label>
                                            <input type="number" step="0.01" name="w_price" value="{{ $productsedit->w_price ?? '' }}" class="form-control form-control-sm" id="whole_price" required>

                                            <label for="w_unit">Wholesale Unit</label>
                                            <select name="w_unit" class="form-control form-control-sm" id="wholesale_unit">
                                                <option value="">-- Select Unit --</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}" {{ ($productsedit->w_unit ?? '') == $unit->id ? 'selected' : '' }}>
                                                        {{ $unit->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <label for="r_capital">Retail Capital</label>
                                            <input type="number" step="0.01" name="r_capital" value="{{ $productsedit->r_capital ?? '' }}" class="form-control form-control-sm" id="retail_capital" required>

                                            <label for="r_price">Retail Price</label>
                                            <input type="number" step="0.01" name="r_price" value="{{ $productsedit->r_price ?? '' }}" class="form-control form-control-sm" id="retail_price" required>

                                            <label for="r_unit">Retail Unit</label>
                                            <select name="r_unit" class="form-control form-control-sm" id="wholesale_unit" required>
                                                <option value="">-- Select Unit --</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}" {{ ($productsedit->r_unit ?? '') == $unit->id ? 'selected' : '' }}>
                                                        {{ $unit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <label for="image">Image</label>
                                        <input type="file" name="image" class="form-control form-control-sm" id="image">
                                        

                                        <button type="submit" class="btn bg-main-7 text-light mt-2 w-100">
                                             <i class="fas fa-save"></i> Save
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Product Table Column -->
                        <div class="col-lg-12 col-md-12 product-list">
                            <div class="card">
                                <div class="table-responsive">
                                    <table id="example3" class="table table-bordered table-hover" width="100%">
                                        <thead class="bg-main-9 text-dark">
                                            <tr>
                                                <th>Image</th>
                                                <th>Barcode</th>
                                                <th>Product Name</th>
                                                <th>Category</th>
                                                <th>Type</th>
                                                <th>Packaging</th>
                                                <th>W. Capital</th>
                                                <th>W. Price</th>
                                                <th>R. Capital</th>
                                                <th>R. Price</th>
                                                <th>R. Qty</th>
                                                <th>W. Qty</th>
                                                <th class="text-center align-middle">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($products as $product)
                                                <tr id="row-{{ $product->id }}">
                                                    <td class="text-center align-middle">
                                                        @if(!empty($product->image) && Storage::disk('public')->exists('uploads/products/' . $product->image))
                                                            <img src="{{ asset('storage/uploads/products/' . $product->image) }}" 
                                                                width="40" height="40" 
                                                                style="border-radius: 5%;"  
                                                                class="product-image" 
                                                                alt="Product Image">
                                                        @endif
                                                    </td>
                                                    <td class="text-main-10 text-center align-middle align-middle">{{ $product->barcode }}</td>
                                                    <td class="text-main-8 align-middle ">{{ $product->product_name }}</td>
                                                    <td class="text-main-1 text-center align-middle">{{ $product->category_name }}</td>
                                                    <td class="text-main-3 text-center align-middle">
                                                        {{ $product->product_type == '1' ? 'Standard' : 'Made to Order' }}
                                                    </td>
                                                    <td class="text-center align-middle">{{ $product->packaging }}</td>
                                                    <td class="text-center align-middle">₱{{ number_format($product->w_capital, 2) }}</td>
                                                    <td class="text-center align-middle">₱{{ number_format($product->w_price, 2) }}</td>
                                                    <td class="text-center align-middle">₱{{ number_format($product->r_capital, 2) }}</td>
                                                    <td class="text-main-4 text-center align-middle">₱{{ number_format($product->r_price, 2) }}</td>
                                                    <td class="text-main-7 text-center align-middle">{{ number_format($product->rqty) }} {{ $product->r_unit_name ?? '' }}</td>
                                                    <td class="text-main-6 text-center align-middle">{{ number_format($product->wqty) }} {{ $product->w_unit_name ?? '' }}</td>
                                                    <td class="text-center align-middle">
                                                        <a href="#" class="btn btn-info btn-sm edit-btn" data-id="{{ $product->id }}" title="Edit">
                                                            <i class="fas fa-info-circle"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-danger btn-sm delete-row" data-model="Product" data-id="{{ $product->id }}" title="Delete" 
                                                        onclick="return confirm('Are you sure you want to delete this product?')">
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
<form id="post-form" action="{{ route('productEdit') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="id" id="id">
</form>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".edit-btn").forEach(function (btn) {
            btn.addEventListener("click", function () {
                let userId = this.getAttribute("data-id");
                document.getElementById("id").value = userId;
                document.getElementById("post-form").submit();
            });
        });
    });
</script>
@endsection