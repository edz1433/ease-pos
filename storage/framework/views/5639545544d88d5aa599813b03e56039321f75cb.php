<script>
$(document).ready(function() {
    // Set CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#startInventoryBtn').click(function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to start the inventory process?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, start it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX POST request
                $.ajax({
                    url: "<?php echo e(route('inventoryStart')); ?>",
                    method: "POST",
                    data: {}, // Add data here if needed
                    success: function(response) {
                        Swal.fire(
                            'Started!',
                            response.message ?? 'Inventory process has started.',
                            'success'
                        ).then(() => {
                            location.reload(); // reload table or page
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error!',
                            'Something went wrong.',
                            'error'
                        );
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
});
</script>
 <?php if(request()->is('inventory/form*')): ?>
<script>
$(document).ready(function() {
    // Initialize DataTable without pagination
    var table = $('#inventory-table').DataTable({
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
$(document).ready(function(){

    function updateRowSubtotal(row){
        let qty = parseFloat(row.find('.qty-input').val()) || 0;
        let capital = parseFloat(row.find('.capital-input').val()) || 0;
        let subtotal = qty * capital;
        row.find('.subtotal').text(subtotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        return subtotal;
    }

    function updateGrandTotal(){
        let total = 0;
        $('#inventory-table tbody tr').each(function(){
            total += updateRowSubtotal($(this));
        });
        $('#grandTotal').text(total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
    }

    $('#inventory-table').on('input', '.qty-input, .capital-input', function(){
        const row = $(this).closest('tr');
        updateRowSubtotal(row);
        updateGrandTotal();
    });

    // Auto-save after typing stops
    let typingTimer;
    const doneTypingInterval = 500;

    $('#inventory-table').on('input', '.qty-input, .capital-input', function(){
        clearTimeout(typingTimer);

        const row = $(this).closest('tr');
        const itemId = row.find('.qty-input').data('id');
        const qty = parseFloat(row.find('.qty-input').val()) || 0;
        const capital = parseFloat(row.find('.capital-input').val()) || 0;

        typingTimer = setTimeout(function(){
            $.ajax({
                url: "<?php echo e(route('inventoriesItemSave', $inventory->id)); ?>",
                type: 'POST',
                data: {
                    items: [{ id: itemId, qty: qty, capital: capital }]
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){
                    console.log('Item saved successfully');
                },
                error: function(err){
                    console.error('Error saving item', err);
                }
            });
        }, doneTypingInterval);
    });

    updateGrandTotal();
});
</script>
<script>
$('#saveInventoryBtn').on('click', function() {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to save this inventory?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, save it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo e(route('finalizeInventory')); ?>",
                type: 'POST',
                data: {},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                     Swal.fire({
                        title: 'Saved!',
                        text: 'Inventory has been updated.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "<?php echo e(route('inventoryRead')); ?>";
                    });
                },
                error: function(err) {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                }
            });
        }
    });
});
</script>
<?php endif; ?><?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/script/inventoryScript.blade.php ENDPATH**/ ?>