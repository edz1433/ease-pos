@extends('layouts.master')

@section('body')
@include('layouts.formStyle')
<div class="container-fluid">
    <div class="row">
        <!-- Supplier Form Column -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-gray">
                        <b>SUPPLIER FORM</b>
                    </h2>
                </div>
                <div class="card-body bg-form">
                    <form class="p-2" id="supplier_form" method="POST" enctype="multipart/form-data" 
                        action="{{ route('supplierCreate') }}">
                        @csrf
                        <div class="form-group">
                            <label for="supplier_name">Supplier Name</label>
                            <input type="text" name="supplier_name" 
                                class="form-control form-control-sm" id="supplier_name" required>

                            <label for="contact_person">Contact Person</label>
                            <input type="text" name="contact_person" 
                                class="form-control form-control-sm" id="contact_person" required>

                            <label for="contact_number">Contact Number</label>
                            <input type="text" name="contact_number" 
                                class="form-control form-control-sm" id="contact_number" required>

                            <label for="email">Email</label>
                            <input type="email" name="email" 
                                class="form-control form-control-sm" id="email">

                            <label for="address">Address</label>
                            <textarea name="address" id="address" rows="2" 
                                class="form-control form-control-sm"></textarea>

                            <label for="amount_payable">Amount Payable</label>
                            <input type="number" step="0.01" name="amount_payable" 
                                class="form-control form-control-sm" id="amount_payable" required>
                        </div>

                        <button type="submit" class="btn bg-main-7 text-light w-100">
                            <i class="fas fa-save"></i> {{ isset($supplierEdit) ? 'Update' : 'Save' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Supplier Table Column -->
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-gray">
                        <b>SUPPLIER LIST</b>
                    </h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example3" class="table table-bordered table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>Contact Person</th>
                                    <th>Address</th>
                                    <th>Number</th>
                                    <th>Email</th>
                                    <th>Amount Payable</th>
                                    <th>Address</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $supplier)
                                <tr id="row-{{ $supplier->id }}">
                                    <td class="text-main-8 font-weight-bold">{{ $supplier->supplier_name }}</td>
                                    <td class="text-main-8 font-weight-bold">{{ $supplier->contact_person }}</td>
                                    <td class="text-main-8 font-weight-bold">{{ $supplier->address }}</td>
                                    <td class="text-main-8 font-weight-bold">{{ $supplier->contact_number }}</td>
                                    <td class="text-main-8 font-weight-bold">{{ $supplier->email }}</td>
                                    <td class="text-main-1 font-weight-bold">₱{{ number_format($supplier->amount_payable, 2) }}</td>
                                    <td class="text-main-8 font-weight-bold">{{ $supplier->address }}</td>
                                    <td class="text-center">
                                        <button 
                                            type="button" 
                                            class="btn btn-info btn-sm edit-btn" 
                                            data-id="{{ $supplier->id }}"
                                            data-supplier_name="{{ $supplier->supplier_name }}"
                                            data-contact_person="{{ $supplier->contact_person }}"
                                            data-contact_number="{{ $supplier->contact_number }}"
                                            data-email="{{ $supplier->email }}"
                                            data-address="{{ $supplier->address }}"
                                            data-amount_payable="{{ $supplier->amount_payable }}"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button value="{{ $supplier->id }}" class="btn btn-danger btn-sm delete-row" data-model="Supplier" data-id="{{ $supplier->id }}">
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
        </div> <!-- end col -->
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Handle Edit button click
    $(document).on('click', '.edit-btn', function () {
        const button = $(this);

        // Fill the form fields with the data attributes
        $('#supplier_name').val(button.data('supplier_name'));
        $('#contact_person').val(button.data('contact_person'));
        $('#contact_number').val(button.data('contact_number'));
        $('#email').val(button.data('email'));
        $('#address').val(button.data('address'));
        $('#amount_payable').val(button.data('amount_payable'));

        // Change form action to update route
        const supplierId = button.data('id');
        const updateUrl = `{{ route('supplierUpdate', ':id') }}`.replace(':id', supplierId);
        $('#supplier_form').attr('action', updateUrl);

        // Add hidden input for ID if not present
        if (!$('#supplier_form input[name="id"]').length) {
            $('#supplier_form').append(`<input type="hidden" name="id" value="${supplierId}">`);
        } else {
            $('#supplier_form input[name="id"]').val(supplierId);
        }

        // Change submit button text to "Update"
        $('#supplier_form button[type="submit"]').html('<i class="fas fa-save"></i> Update');
    });

    // Optional: Reset form when the page is loaded or after successful submit
    $(document).on('resetForm', function () {
        $('#supplier_form').trigger('reset');
        $('#supplier_form').attr('action', "{{ route('supplierCreate') }}");
        $('#supplier_form button[type="submit"]').html('<i class="fas fa-save"></i> Save');
        $('#supplier_form input[name="id"]').remove();
    });

});
</script>

@endsection
