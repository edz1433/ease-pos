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
                        <div class="col-lg-4 col-md-12">
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
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" value="{{ $productedit->barcode ?? '' }}" class="form-control form-control-sm" id="barcode" required>

                                            <label for="product_name">Product Name</label>
                                            <input type="text" name="product_name" value="{{ $productedit->product_name ?? '' }}" class="form-control form-control-sm" id="product_name" required>

                                            <label for="category">Category</label>
                                            <input type="text" name="category" value="{{ $productedit->category ?? '' }}" class="form-control form-control-sm" id="category" required>

                                            <label for="packaging">Packaging</label>
                                            <input type="text" name="packaging" value="{{ $productedit->packaging ?? '' }}" class="form-control form-control-sm" id="packaging" required>

                                            <label for="retail_capital">Retail Capital</label>
                                            <input type="number" step="0.01" name="retail_capital" value="{{ $productedit->retail_capital ?? '' }}" class="form-control form-control-sm" id="retail_capital" required>

                                            <label for="retail_price">Retail Price</label>
                                            <input type="number" step="0.01" name="retail_price" value="{{ $productedit->retail_price ?? '' }}" class="form-control form-control-sm" id="retail_price" required>

                                            <label for="retail_unit">Retail Unit</label>
                                            <input type="text" name="retail_unit" value="{{ $productedit->retail_unit ?? '' }}" class="form-control form-control-sm" id="retail_unit" required>

                                            <label for="whole_capital">Wholesale Capital</label>
                                            <input type="number" step="0.01" name="whole_capital" value="{{ $productedit->whole_capital ?? '' }}" class="form-control form-control-sm" id="whole_capital" required>

                                            <label for="whole_price">Wholesale Price</label>
                                            <input type="number" step="0.01" name="whole_price" value="{{ $productedit->whole_price ?? '' }}" class="form-control form-control-sm" id="whole_price" required>

                                            <label for="wholesale_unit">Wholesale Unit</label>
                                            <input type="text" name="wholesale_unit" value="{{ $productedit->wholesale_unit ?? '' }}" class="form-control form-control-sm" id="wholesale_unit" required>
                                        </div>

                                        <button type="submit" class="btn btn-success">
                                            <i class="glyphicon glyphicon-save"></i> Save
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Product Table Column -->
                        <div class="col-lg-8 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">PRODUCT LIST</div>
                                <div class="panel-body">
                                    <table id="example3" class="table table-striped table-bordered table-hover" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Barcode</th>
                                                <th>Product Name</th>
                                                <th>Category</th>
                                                <th>Retail Price</th>
                                                <th>Wholesale Price</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($products as $index => $product)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $product->barcode }}</td>
                                                    <td>{{ $product->product_name }}</td>
                                                    <td>{{ $product->category }}</td>
                                                    <td>₱{{ number_format($product->retail_price, 2) }}</td>
                                                    <td>₱{{ number_format($product->whole_price, 2) }}</td>
                                                    <td class="text-center">
                                                        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                        <form action="{{ route('product.destroy', $product->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?')">Delete</button>
                                                        </form>
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