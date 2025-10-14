<script>
$(document).ready(function () {
    let retailPriceManuallyEdited = false;

    function calculateRetailFields() {
        const packaging = parseFloat($('#packaging').val()) || 1;
        const wCapital = parseFloat($('#whole_capital').val()) || 0;

        if (packaging > 1 && wCapital > 0) {
            const rCapital = wCapital / packaging;
            $('#retail_capital').val(rCapital.toFixed(2)).prop('readonly', true);

            if (!retailPriceManuallyEdited) {
                const rPrice = rCapital * 1.10;
                $('#retail_price').val(rPrice.toFixed(2));
            }

            $('#retail_price').prop('readonly', false);
        } else if (packaging === 1) {
            $('#retail_capital, #retail_price').prop('readonly', false);
        }
    }

    function handleMadeToOrder() {
        const isMTO = $('#product_type').val() === 'made-to-order';

        if (isMTO) {
            $('#whole_price, #whole_capital, #packaging, #retail_capital, #retail_price')
                .val('0.00')
                .prop('readonly', true);
            $('#retail_unit, #wholesale_unit').val('').prop('readonly', true);
        } else {
            $('#packaging').prop('readonly', false);
            $('#retail_unit, #wholesale_unit').prop('readonly', false);
            handlePackaging();
        }
    }

    function handlePackaging() {
        const packaging = parseFloat($('#packaging').val()) || 1;

        if (packaging === 1) {
            $('#whole_price, #whole_capital').val('0.00').prop('readonly', true);
            $('#wholesale_unit').val('').prop('readonly', true);

            $('#retail_capital, #retail_price').prop('readonly', false);
        } else {
            $('#whole_price, #whole_capital').prop('readonly', false);
            $('#wholesale_unit').prop('readonly', false);

            $('#retail_capital').prop('readonly', true);
        }
    }

    $('#retail_price').on('input', function () {
        retailPriceManuallyEdited = true;
    });

    $('#packaging, #whole_capital').on('input', function () {
        retailPriceManuallyEdited = false;
        handlePackaging();
        calculateRetailFields();
    });

    $('#product_type').on('change', function () {
        retailPriceManuallyEdited = false;
        handleMadeToOrder();
        calculateRetailFields();
    });

    // Initial run
    handleMadeToOrder();
    calculateRetailFields();
});
</script>
<script>
$(document).ready(function () {
    $('#barcode').on('input', function () {
        const barcode = $(this).val();

        if (barcode.length > 0) {
            $.ajax({
                url: '{{ route("getProductPresets") }}',
                method: 'GET',
                data: { barcode: barcode },
                success: function (response) {
                    if (response.success && response.preset) {
                        const preset = response.preset;
                        $('#product_name').val(preset.product_name);
                    }
                }
            });
        }
    });
});
</script>
<script>
function generateBarcode(fieldId) {
    let url = "{{ route('getNextBarcode', ':id') }}".replace(':id', fieldId);

    console.log("📡 Calling URL:", url); // Debug which endpoint is called

    fetch(url)
        .then(response => {
            console.log("✅ Raw response:", response);
            return response.json();
        })
        .then(data => {
            console.log("📦 JSON data:", data);

            if (data.next_barcode) {
                document.getElementById(fieldId).value = data.next_barcode;
            } else {
                alert(data.error || 'Failed to generate barcode');
            }
        })
        .catch(error => console.error("❌ Fetch error:", error));
}
</script>
<script>
    $(document).ready(function () {
        const $form = $('.product-form');
        const $list = $('.product-list');
        const $icon = $('#toggleForm').find('i');

        // Check sessionStorage on load
        const isVisible = sessionStorage.getItem('formVisible') === 'true';

        if (isVisible) {
            $form.show();
            $list.removeClass('col-lg-12').addClass('col-lg-9');
            $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $form.hide();
            $list.removeClass('col-lg-9').addClass('col-lg-12');
            $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }

        // Toggle logic
        $('#toggleForm').on('click', function () {
            $form.slideToggle(250, function () {
                const visible = $form.is(':visible');

                $list.toggleClass('col-lg-12', !visible)
                     .toggleClass('col-lg-9', visible);

                $icon.toggleClass('fa-eye', !visible)
                     .toggleClass('fa-eye-slash', visible);

                // Save to sessionStorage
                sessionStorage.setItem('formVisible', visible);
            });
        });
    });
</script>

<script>
    function editPostForm(id) {
        document.getElementById('user-id').value = id;

        document.getElementById('post-form').submit();
    }
</script>
@if(request()->is('products/classifications*'))
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("categoryForm");
    const idInput = document.getElementById("categoryId");
    const nameInput = document.getElementById("categoryName");
    const iconInput = document.getElementById("selectedIcon");
    const iconPreview = document.getElementById("selectedIconPreview");
    const submitBtn = document.getElementById("formSubmitBtn");
    const cancelBtn = document.getElementById("categoryCancelBtn");

    // Icon picker
    document.querySelectorAll(".icon-picker-item").forEach(function (el) {
        el.addEventListener("click", function (e) {
            e.preventDefault();
            iconInput.value = this.dataset.value;
            iconPreview.className = this.dataset.value;
        });
    });

    // Edit category (switch to update mode)
    document.querySelectorAll(".edit-category").forEach(function (btn) {
        btn.addEventListener("click", function () {
            idInput.value = this.dataset.id;
            nameInput.value = this.dataset.name;
            iconInput.value = this.dataset.icon;
            iconPreview.className = this.dataset.icon;

            submitBtn.innerHTML = '<i class="fas fa-save"></i>';
            cancelBtn.classList.remove("d-none"); // show X button
            nameInput.focus();
        });
    });

    // Reset back to create mode
    form.addEventListener("reset", function () {
        idInput.value = "";
        nameInput.value = "";
        iconInput.value = "";
        iconPreview.className = "fas fa-question";
        submitBtn.innerHTML = '<i class="fas fa-plus"></i>';
        cancelBtn.classList.add("d-none"); // hide X button
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("addUnitForm");
    const idInput = document.getElementById("unitId");
    const nameInput = document.getElementById("unitName");
    const submitBtn = document.getElementById("unitSubmitBtn");
    const cancelBtn = document.getElementById("unitCancelBtn");

    // Edit unit (switch to update mode)
    document.querySelectorAll(".edit-unit").forEach(function (btn) {
        btn.addEventListener("click", function () {
            idInput.value = this.dataset.id;
            nameInput.value = this.dataset.name;

            submitBtn.innerHTML = '<i class="fas fa-save"></i>';
            cancelBtn.classList.remove("d-none");
            nameInput.focus();
        });
    });

    // Reset back to create mode
    form.addEventListener("reset", function () {
        idInput.value = "";
        nameInput.value = "";
        submitBtn.innerHTML = '<i class="fas fa-plus"></i>';
        cancelBtn.classList.add("d-none");
    });
});
</script>
@endif

<script>
document.addEventListener("DOMContentLoaded", function() {
    const storagePath = "{{ asset('storage/uploads/products') }}";
    // ✅ Function to set form mode dynamically
    function setFormMode(isUpdate, productId = null) {
        const methodInput = document.querySelector('input[name="_method"]');
        const idInput = document.getElementById('product_id');
        if (methodInput && idInput) {
            methodInput.value = isUpdate ? 'PUT' : 'POST';
            idInput.value = isUpdate ? productId : '';
        }
    }

    // Initialize DataTable
    const productTable = document.querySelector('.product-table');
    if (!productTable) {
        console.error('Product table element not found.');
        return;
    }

    if (typeof $.fn.DataTable !== 'undefined' && $.fn.DataTable.isDataTable(productTable)) {
        $(productTable).DataTable().clear().destroy();
    }

    const dataTable = $(productTable).DataTable({
        processing: true,
        ajax: {
            url: "{{ route('products.ajax') }}",
            error: function(xhr, error, thrown) {
                console.error('DataTable AJAX error:', error, thrown);
                alert('Failed to load product data. Please try again.');
            }
        },
        columns: [
            {
                data: 'image',
                className: 'text-center align-middle',
                render: function(data) {
                    return data
                        ? `<img src="${storagePath}/${encodeURIComponent(data)}" class="product-img" alt="Product Image" style="max-width: 50px;">`
                        : '<span>No image</span>';
                }
            },
            { 
                data: null,
                className: 'align-middle',
                render: function(data) {
                    return `
                        <strong class="text-main-8">${data.product_name || 'N/A'}</strong><br>
                        <span class="text-main-1">${data.category_name || 'No category'}</span><br>
                        <small class="text-main-1">Warranty: ${data.warranty || 'None'}</small><br>
                        <small class="text-main-1">Replacement duration: ${data.rep_duration || 'N/A'}</small>
                    `;
                }
            },
            { 
                data: null,
                className: 'align-middle',
                render: data => `R-${data.barcode || 'N/A'}${data.w_barcode ? '<br>W-' + data.w_barcode : ''}`
            },
            { data: 'packaging', className: 'align-middle', render: data => data || 'N/A' },
            { 
                data: null,
                className: 'align-middle',
                render: function(data) {
                    let badge = '';
                    if (data.wqty == 0) badge = '<span class="badge bg-danger">Out</span>';
                    else if (data.wqty < 10) badge = '<span class="badge bg-warning text-dark">Low</span>';
                    return `
                        <small>Cap: ₱${parseFloat(data.w_capital || 0).toFixed(2)}</small><br>
                        <small>Price: ₱${parseFloat(data.w_price || 0).toFixed(2)}</small><br>
                        <small>Qty: ${data.wqty || 0} ${data.w_unit_name || ''} ${badge}</small>
                    `;
                }
            },
            { 
                data: null,
                className: 'align-middle',
                render: function(data) {
                    let badge = '';
                    if (data.rqty == 0) badge = '<span class="badge bg-danger">Out</span>';
                    else if (data.rqty < 10) badge = '<span class="badge bg-warning text-dark">Low</span>';
                    return `
                        <small>Cap: ₱${parseFloat(data.r_capital || 0).toFixed(2)}</small><br>
                        <small>Price: ₱${parseFloat(data.r_price || 0).toFixed(2)}</small><br>
                        <small>Qty: ${data.rqty || 0} ${data.r_unit_name || ''} ${badge}</small>
                    `;
                }
            },
            { 
                data: null,
                className: 'align-middle',
                render: data => `R-${data.total_sold_r || 0}<br>W-${data.total_sold_w || 0}`
            },
            {
                data: null,
                className: 'text-center align-middle',
                render: function(data) {
                    return `
                        <a href="javascript:void(0)" 
                        class="btn btn-warning btn-sm adjust-stock-btn" 
                        data-id="${data.id || ''}" 
                        data-name="${encodeURIComponent(data.product_name || '') + ' ' + (data.model || '')}" 
                        title="Stock Management">
                        <i class="fas fa-cubes"></i>
                        </a>
                        <button class="btn btn-info btn-sm edit-btn" 
                                data-id="${data.id || ''}"
                                data-barcode="${encodeURIComponent(data.barcode || '')}"
                                data-w_barcode="${encodeURIComponent(data.w_barcode || '')}"
                                data-product_name="${encodeURIComponent(data.product_name || '')}"
                                data-model="${encodeURIComponent(data.model || '')}"
                                data-more_details="${encodeURIComponent(data.more_details || '')}"
                                data-product_type="${encodeURIComponent(data.product_type || '')}"
                                data-category="${encodeURIComponent(data.category || '')}"
                                data-packaging="${encodeURIComponent(data.packaging || '')}"
                                data-warranty="${encodeURIComponent(data.warranty || '')}"
                                data-rep_duration="${encodeURIComponent(data.rep_duration || '')}"
                                data-w_capital="${data.w_capital || ''}"
                                data-w_price="${data.w_price || ''}"
                                data-w_unit="${encodeURIComponent(data.w_unit || '')}"
                                data-r_capital="${data.r_capital || ''}"
                                data-r_price="${data.r_price || ''}"
                                data-r_unit="${encodeURIComponent(data.r_unit || '')}"
                                data-r_stock_alert="${data.r_stock_alert || ''}"
                                data-w_stock_alert="${data.w_stock_alert || ''}"
                                title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="#" class="btn btn-danger btn-sm delete-row" 
                        data-model="Product" 
                        data-id="${data.id || ''}" 
                        title="Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                    `;
                }
            }
        ],
        responsive: true,
        autoWidth: false,
        order: [],
        drawCallback: attachEditButtonListeners
    });

    // Cache DOM elements
    const productForm = document.getElementById('product_form_data');
    const saveBtn = productForm.querySelector('#saveBtn');
    const formHeading = document.getElementById('formHeading');

    // Populate form
    function populateForm(data) {
        const safeValue = (v) => v ? decodeURIComponent(v) : '';
        document.getElementById('barcode').value = safeValue(data.barcode);
        document.getElementById('w_barcode').value = safeValue(data.w_barcode);
        document.getElementById('product_name').value = safeValue(data.product_name);
        document.getElementById('model').value = safeValue(data.model);
        document.getElementById('more_details').value = safeValue(data.more_details);
        document.getElementById('product_type').value = safeValue(data.product_type);
        document.getElementById('category').value = safeValue(data.category);
        document.getElementById('packaging').value = safeValue(data.packaging);
        document.getElementById('warranty').value = safeValue(data.warranty);
        document.getElementById('rep_duration').value = safeValue(data.rep_duration);
        document.getElementById('whole_capital').value = safeValue(data.w_capital);
        document.getElementById('whole_price').value = safeValue(data.w_price);
        document.getElementById('wholesale_unit').value = safeValue(data.w_unit);
        document.getElementById('retail_capital').value = safeValue(data.r_capital);
        document.getElementById('retail_price').value = safeValue(data.r_price);
        document.getElementById('retail_unit').value = safeValue(data.r_unit);
        document.getElementById('r_stock_alert').value = safeValue(data.r_stock_alert);
        document.getElementById('w_stock_alert').value = safeValue(data.w_stock_alert);

        // ✅ Set form to UPDATE mode
        setFormMode(true, data.id);
        formHeading.textContent = 'EDIT PRODUCT';
        saveBtn.innerHTML = '<i class="fas fa-save"></i> Update Product';
        productForm.scrollIntoView({ behavior: 'smooth' });
    }

    // Reset form to create new product
    function resetForm() {
        productForm.reset();
        setFormMode(false);
        formHeading.textContent = 'ADD PRODUCT';
        saveBtn.innerHTML = '<i class="fas fa-plus"></i> Add Product';
    }

    function handleEditClick(event) {
        const btn = event.currentTarget;
        const data = { ...btn.dataset };
        populateForm(data);
    }

    function attachEditButtonListeners() {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.removeEventListener('click', handleEditClick);
            btn.addEventListener('click', handleEditClick);
        });
    }

    // Attach add button handler
    document.querySelectorAll('.add-product-btn').forEach(btn => {
        btn.addEventListener('click', resetForm);
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('product_form_data');
    const saveBtn = document.getElementById('saveBtn');
    const productTable = $('.product-table').DataTable(); // use existing DataTable

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        saveBtn.disabled = true;
        saveBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Saving...`;

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = `<i class="fas fa-save"></i> Save Product`;

            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Success', text: data.message, timer: 1500, showConfirmButton: false });
                form.reset();
                $('#remove_image').val(0);
                productTable.ajax.reload(null, false); // 🔁 reload DataTable (stay on current page)
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message });
            }
        })
        .catch(() => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = `<i class="fas fa-save"></i> Save Product`;
            Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
        });
    });
});
</script>
<script>
$(document).ready(function () {
    // ✅ Delegated event listener for dynamically loaded DataTable buttons
    $(document).on('click', '.adjust-stock-btn', function () {
        const productId = $(this).data('id');
        const productName = decodeURIComponent($(this).data('name'));

        $('#adjustment_product_id').val(productId);
        $('#adjustment_product_name').text(productName);
        
        // Reset optional fields
        $('#stockAdjustmentForm')[0].reset();
        $('#branchTransferGroup, #saleIdGroup, #transNumberGroup').hide();

        // Show modal
        $('#stockAdjustmentModal').modal('show');
    });

    // ✅ Toggle "Transfer To" branch dropdown
    $('#adjustment_type').on('change', function () {
        const type = $(this).val();
        if (type === 'transfer') {
            $('#branchTransferGroup, #transNumberGroup').show();
        } else {
            $('#branchTransferGroup, #transNumberGroup').hide();
        }
    });

    // ✅ Show Sale ID only when reason is "Customer Return"
    $('#reason').on('change', function () {
        if ($(this).val() === 'Customer Return') {
            $('#saleIdGroup').show();
        } else {
            $('#saleIdGroup').hide();
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stockForm = document.getElementById('stockAdjustmentForm');
    const productTable = $('.product-table').DataTable();

    stockForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(stockForm);
        const submitBtn = stockForm.querySelector('button[type="submit"]');

        submitBtn.disabled = true;
        submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Saving...`;

        fetch(stockForm.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
            body: formData
        })
        .then(async res => {
            const contentType = res.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return res.json();
            } else {
                // Fallback for redirect responses (like Laravel redirect()->back())
                return { success: res.ok, message: res.ok ? 'Stock adjusted successfully!' : 'An error occurred.' };
            }
        })
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `Save Adjustment`;

            if (data.success) {
                $('#stockAdjustmentModal').modal('hide'); // hide modal
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message || 'Stock adjusted successfully!',
                    timer: 1500,
                    showConfirmButton: false
                });
                stockForm.reset();
                productTable.ajax.reload(null, false); // 🔁 reload DataTable without losing pagination
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Something went wrong.' });
            }
        })
        .catch(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `Save Adjustment`;
            Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.' });
        });
    });
});
</script>


