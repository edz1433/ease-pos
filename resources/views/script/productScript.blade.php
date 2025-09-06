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

