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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-gray">
                        <b>PRODUCT LIST</b>
                    </h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Product Form Column -->
                        <div class="col-lg-3 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">ADD PRODUCT</div>
                                <div class="panel-body bg-form">
                                    <form class="p-2" id="product_form_data" method="POST" action="{{ isset($productedit) ? route('productUpdate', $productedit->id) : route('productCreate') }}">
                                        @csrf
                                        @if(isset($productedit))
                                            @method('PUT')
                                            <input type="hidden" name="id" value="{{ $productedit->id }}">
                                        @endif

                                        <div class="form-group">
                                            <label for="barcode">Barcode <i class="fas fa-sync"></i></label>
                                            <input type="text" name="barcode" value="{{ $productedit->barcode ?? '' }}" class="form-control form-control-sm" onclick="generateBarcode()" id="barcode" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            
                                            <label for="product_name">Product Name</label>
                                            <input type="text" name="product_name" value="{{ $productedit->product_name ?? '' }}" class="form-control form-control-sm" id="product_name" required>

                                            <label for="product_name">Product Type</label>
                                            <select name="product_type" id="product_type" class="form-control form-control-sm" required>
                                                <option value="">-- Select Type --</option>
                                                <option value="1" {{ ($productedit->product_type ?? '') == '1' ? 'selected' : '' }}>Standard</option>
                                                <option value="2" {{ ($productedit->product_type ?? '') == '2' ? 'selected' : '' }}>Made to Order</option>
                                            </select>

                                            <label for="category">Category</label>
                                            <select name="category" id="category" class="form-control form-control-sm" required>
                                                <option value="">-- Select Category --</option>
                                                <option value="Drink" {{ ($productedit->category ?? '') == 'Drink' ? 'selected' : '' }}>Drink</option>
                                                <option value="Vegetable" {{ ($productedit->category ?? '') == 'Vegetable' ? 'selected' : '' }}>Vegetable</option>
                                                <option value="Grocery" {{ ($productedit->category ?? '') == 'Grocery' ? 'selected' : '' }}>Grocery</option>
                                                <option value="Restaurant" {{ ($productedit->category ?? '') == 'Restaurant' ? 'selected' : '' }}>Restaurant</option>
                                                <option value="Meat" {{ ($productedit->category ?? '') == 'Meat' ? 'selected' : '' }}>Meat</option>
                                                <option value="Snacks" {{ ($productedit->category ?? '') == 'Snacks' ? 'selected' : '' }}>Snacks</option>
                                                <option value="Others" {{ ($productedit->category ?? '') == 'Others' ? 'selected' : '' }}>Others</option>
                                            </select>

                                            <label for="packaging">Packaging</label>
                                            <input type="text" name="packaging" value="{{ $productedit->packaging ?? '' }}" class="form-control form-control-sm" id="packaging" required>

                                             <label for="w_capital">Wholesale Capital</label>
                                            <input type="number" step="0.01" name="w_capital" value="{{ $productedit->w_capital ?? '' }}" class="form-control form-control-sm" id="whole_capital" required>

                                            <label for="w_price">Wholesale Price</label>
                                            <input type="number" step="0.01" name="w_price" value="{{ $productedit->w_price ?? '' }}" class="form-control form-control-sm" id="whole_price" required>

                                            <label for="w_unit">Wholesale Unit</label>
                                            <select name="w_unit" id="w_unit" class="form-control form-control-sm" required>
                                                <option value="">-- Select Unit --</option>
                                                <option value="pc" {{ ($productedit->w_unit ?? '') == 'pc' ? 'selected' : '' }}>pc</option>
                                                <option value="box" {{ ($productedit->w_unit ?? '') == 'box' ? 'selected' : '' }}>box</option>
                                                <option value="pack" {{ ($productedit->w_unit ?? '') == 'pack' ? 'selected' : '' }}>pack</option>
                                                <option value="bot" {{ ($productedit->w_unit ?? '') == 'bot' ? 'selected' : '' }}>bot</option>
                                                <option value="can" {{ ($productedit->w_unit ?? '') == 'can' ? 'selected' : '' }}>can</option>
                                                <option value="kg" {{ ($productedit->w_unit ?? '') == 'kg' ? 'selected' : '' }}>kg</option>
                                                <option value="g" {{ ($productedit->w_unit ?? '') == 'g' ? 'selected' : '' }}>g</option>
                                                <option value="ml" {{ ($productedit->w_unit ?? '') == 'ml' ? 'selected' : '' }}>ml</option>
                                                <option value="ltr" {{ ($productedit->w_unit ?? '') == 'ltr' ? 'selected' : '' }}>ltr</option>
                                                <option value="dozen" {{ ($productedit->w_unit ?? '') == 'dozen' ? 'selected' : '' }}>dozen</option>
                                            </select>

                                            <label for="r_capital">Retail Capital</label>
                                            <input type="number" step="0.01" name="r_capital" value="{{ $productedit->r_capital ?? '' }}" class="form-control form-control-sm" id="retail_capital" required>

                                            <label for="r_price">Retail Price</label>
                                            <input type="number" step="0.01" name="r_price" value="{{ $productedit->r_price ?? '' }}" class="form-control form-control-sm" id="retail_price" required>

                                            <label for="r_unit">Retail Unit</label>
                                            <select name="r_unit" id="r_unit" class="form-control form-control-sm" required>
                                                <option value="">-- Select Unit --</option>
                                                <option value="pc" {{ ($productedit->r_unit ?? '') == 'pc' ? 'selected' : '' }}>pc</option>
                                                <option value="box" {{ ($productedit->r_unit ?? '') == 'box' ? 'selected' : '' }}>box</option>
                                                <option value="pack" {{ ($productedit->r_unit ?? '') == 'pack' ? 'selected' : '' }}>pack</option>
                                                <option value="bot" {{ ($productedit->r_unit ?? '') == 'bot' ? 'selected' : '' }}>bot</option>
                                                <option value="can" {{ ($productedit->r_unit ?? '') == 'can' ? 'selected' : '' }}>can</option>
                                                <option value="kg" {{ ($productedit->r_unit ?? '') == 'kg' ? 'selected' : '' }}>kg</option>
                                                <option value="g" {{ ($productedit->r_unit ?? '') == 'g' ? 'selected' : '' }}>g</option>
                                                <option value="ml" {{ ($productedit->r_unit ?? '') == 'ml' ? 'selected' : '' }}>ml</option>
                                                <option value="ltr" {{ ($productedit->r_unit ?? '') == 'ltr' ? 'selected' : '' }}>ltr</option>
                                                <option value="dozen" {{ ($productedit->r_unit ?? '') == 'dozen' ? 'selected' : '' }}>dozen</option>
                                            </select>
                                        </div>

                                        <label for="image">Image</label>
                                        <input type="file" name="image" value="{{ $productedit->image ?? '' }}" class="form-control form-control-sm" id="image" required>


                                        <button type="submit" class="btn btn-success mt-2">
                                            <i class="glyphicon glyphicon-save"></i> Save
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Product Table Column -->
                        <div class="col-lg-9 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">PRODUCT LIST</div>
                                <div class="panel-body">
                                    <table id="example3" class="table table-striped table-bordered table-hover" width="100%">
                                        <thead>
                                            <tr>
                                                <th>image</th>
                                                <th>Barcode</th>
                                                <th>Product Name</th>
                                                <th>Description</th>
                                                <th>Retail Price</th>
                                                <th>Wholesale Price</th>
                                                <th>Retail Qty</th>
                                                <th>Wholesale Qty</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($products as $index => $product)
                                                <tr>
                                                    <td><img src="{{ asset('upload/product/$product->image') }}" alt=""></td>
                                                    <td>{{ $product->barcode }}</td>
                                                    <td>{{ $product->product_name }}</td>
                                                    <td>
                                                        <strong>Type:</strong> {{ ucfirst($product->product_type) ?? 'N/A' }}<br>
                                                        <strong>Category:</strong> {{ $product->category }}<br>
                                                        <strong>Packaging:</strong> {{ $product->packaging }}<br>
                                                    </td>
                                                    <td>₱{{ number_format($product->r_price, 2) }}</td>
                                                    <td>₱{{ number_format($product->w_price, 2) }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('productEdit', $product->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                        <a href="#" class="btn btn-primary btn-sm">Delete</a>
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
<form id="post-form" action="{{ route('userEdit') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="id" id="user-id">
</form>
<script>
    function editPostForm(id) {
        document.getElementById('user-id').value = id;

        document.getElementById('post-form').submit();
    }
</script>
@endsection