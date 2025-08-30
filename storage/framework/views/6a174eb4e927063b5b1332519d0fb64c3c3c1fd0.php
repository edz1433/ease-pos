<script>
$('#product_id').on('change', function () {
    let selected = $(this).find(':selected');
    let price = selected.data('price');
    let type  = selected.data('type');

    $('#product_price').val(price);
    $('#price_type').val(type);
    $('#selling_price').val(selected.data('selling-price'));
});

$('#savePurchaseModal').on('show.bs.modal', function () {
    const totalCostText = document.getElementById('totalCostDisplay')?.innerText || '0.00';
    const totalAmountInput = document.getElementById('total_amount');
    if (totalAmountInput) {
        totalAmountInput.value = totalCostText.replace(/,/g, ''); // remove comma for number compatibility
    }
});

// Show/Hide fields based on payment mode
document.getElementById('payment_mode').addEventListener('change', function () {
    let mode = this.value;
    document.getElementById('creditFields').style.display = (mode === 'Credit') ? 'block' : 'none';
    document.getElementById('checkFields').style.display = (mode === 'Postdated Check') ? 'block' : 'none';
});
</script>
<script>
$(document).ready(function() {
    // Initialize DataTable without pagination
    var table = $('#purchase-table').DataTable({
        paging: false,         // disable pagination
        ordering: true,        // enable column sorting
        info: false,           // hide "Showing x of y entries"
        lengthChange: false,   // hide entries per page selector
        scrollCollapse: true,
        dom: 'lrtip'           // hides default search box
    });

    // Link your custom search input
    $('#table-search').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
<script>
$('.cancel-btn').on('click', function() {
    var purchaseId = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "This will delete the purchase and all its items!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo e(route('purchasesCancel', ':id')); ?>".replace(':id', purchaseId),
                type: 'GET',
                success: function(response) {
                    Swal.fire(
                        'Cancelled!',
                        'Purchase and its items have been deleted.',
                        'success'
                    ).then(() => {
                        window.location.href = "<?php echo e(route('purchaseRead')); ?>"; // redirect
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Something went wrong. Please try again.',
                        'error'
                    );
                }
            });
        }
    });
});

let sum = 0;
$(".total-amount").each(function() {
    let value = $(this).text().replace(/,/g, ""); // remove commas
    sum += parseFloat(value) || 0;
});
$("#total_amount").val(sum);
</script>




<?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/script/purchaseScript.blade.php ENDPATH**/ ?>