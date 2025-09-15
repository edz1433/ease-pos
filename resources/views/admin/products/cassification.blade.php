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
        <!-- Categories Column -->
        <div class="col-md-6">
            <h5 class="mb-3">Categories</h5>

            <!-- Add Category Form -->
            <form id="addCategoryForm" method="POST" action="{{ route('classificationRead') }}">
                @csrf
                <div class="row g-2 align-items-center">
                    
                    <!-- Category Name Input -->
                    <div class="col-md-10">
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Enter new category" required>
                    </div>

                    <!-- Icon Picker -->
                    <div class="col-md-1">
                        <div class="input-group input-group-sm">
                            <div class="btn-group">
                                <!-- Dropdown button styled same as form-control-sm -->
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i id="selectedIconPreview" class="{{ old('icon', 'fas fa-question') }}"></i>
                                </button>

                                <!-- Dropdown menu -->
                                <div class="dropdown-menu dropdown-menu-right p-2" style="width:260px; max-height:220px; overflow:auto;">
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-glass-martini-alt" title="Beverages"><i class="fas fa-glass-martini-alt fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-shopping-basket" title="Grocery"><i class="fas fa-shopping-basket fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-utensils" title="Cooked Dish / Meal"><i class="fas fa-utensils fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-box" title="Snack"><i class="fas fa-box fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-mug-hot" title="Coffee"><i class="fas fa-mug-hot fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-ice-cream" title="Ice Cream"><i class="fas fa-ice-cream fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-blender" title="Appliances"><i class="fas fa-blender fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-desktop" title="IT Supplies"><i class="fas fa-desktop fa-lg"></i></a>
                                    <a href="#" class="dropdown-item icon-picker-item text-center" data-value="fas fa-plug" title="Electronics"><i class="fas fa-plug fa-lg"></i></a>
                                </div>
                            </div>
                            <input type="hidden" name="icon" id="selectedIcon" value="{{ old('icon', '') }}">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success btn-sm w-100"><i class="fas fa-plus"></i></button>
                    </div>

                </div>
            </form><br>

            <!-- Categories Table -->
            <table id="example3" class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Icon</th>
                        <th>Category</th>
                        <th style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><i class="{{ $category->icon }}"></i></td>
                            <td>{{ $category->name }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary edit-category" data-id="{{ $category->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Units Column -->
        <div class="col-md-6">
            <h5 class="mb-3">Units</h5>

            <!-- Add Unit Form -->
            <form id="addUnitForm" method="POST" action="{{ route('unitsCreate') }}">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="name" class="form-control form-control-sm" placeholder="Enter new unit" required>
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </form>

            <!-- Units Table -->
            <table id="example3" class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Unit</th>
                        <th style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $index => $unit)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $unit->name }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary edit-unit" data-id="{{ $unit->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("target_form_data");
    const idInput = document.getElementById("item_id");
    const typeInput = document.getElementById("type");
    const nameInput = document.getElementById("name");
    const submitBtn = document.getElementById("submit-btn");

    // Handle Edit Button
    document.querySelectorAll(".edit-btn").forEach(function (btn) {
        btn.addEventListener("click", function () {
            let itemId = this.getAttribute("data-id");
            let itemType = this.getAttribute("data-type");
            let itemName = this.getAttribute("data-name");

            // Fill form
            idInput.value = itemId;
            typeInput.value = itemType;
            nameInput.value = itemName;

            // Change action to update route
            form.action = "{{ url('classification/update') }}/" + itemId;

            // Change button text
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update';
        });
    });

    // Reset form back to create mode when cleared/refreshed
    form.addEventListener("reset", function () {
        idInput.value = "";
        typeInput.value = "";
        form.action = "{{ route('classificationCreate') }}";
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Save';
    });
});
</script>
@endsection
