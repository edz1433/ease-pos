<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#salesTable').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'desc']] // Sort by date descending by default
        });
        
        // Apply filters
        $('#applyFilters').click(function() {
            const transactionFilter = $('#transactionFilter').val();
            const customerFilter = $('#customerFilter').val();
            const paymentFilter = $('#paymentFilter').val();
            const statusFilter = $('#statusFilter').val();
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();
            
            // Combine filters
            table.column(1).search(transactionFilter).draw();
            table.column(2).search(customerFilter).draw();
            table.column(7).search(paymentFilter).draw();
            table.column(8).search(statusFilter).draw();
            
            // Date range filter
            if (startDate || endDate) {
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        const date = new Date(data[0]);
                        const start = new Date(startDate);
                        const end = new Date(endDate);
                        
                        if ((!startDate || date >= start) && (!endDate || date <= end)) {
                            return true;
                        }
                        return false;
                    }
                );
                table.draw();
                $.fn.dataTable.ext.search.pop(); // Remove the filter for next time
            }
        });
        
        // Reset filters
        $('#resetFilters').click(function() {
            $('#transactionFilter').val('');
            $('#customerFilter').val('');
            $('#paymentFilter').val('');
            $('#statusFilter').val('');
            $('#startDate').val('');
            $('#endDate').val('');
            table.search('').columns().search('').draw();
        });
        
        // View details button click
        $('.view-details').click(function() {
            const saleId = $(this).data('id');
            // In a real application, you would fetch the sale details via AJAX
            // For this example, we'll use sample data
            showSaleDetails(saleId);
        });
        
        // Print button
        $('#printBtn').click(function() {
            window.print();
        });
        
        // Export button
        $('#exportBtn').click(function() {
            // In a real application, this would export the data
            alert('Export functionality would be implemented here');
        });
        
        // Function to show sale details (sample implementation)
        function showSaleDetails(saleId) {
            // Sample data - in a real app, you would fetch this from the server
            const sampleData = {
                transaction_number: 'TRX-00125',
                date: '2023-07-15',
                customer: 'John Smith',
                table_no: 'Table 5',
                payment_method: 'Cash',
                status: 'Completed',
                subtotal: '₱1,137.95',
                vat: '₱112.80',
                discount: '₱50.00',
                total: '₱1,250.75',
                tendered: '₱1,300.00',
                change: '₱49.25',
                items: [
                    { name: 'Burger', quantity: 2, price: '₱250.00', total: '₱500.00' },
                    { name: 'Fries', quantity: 3, price: '₱120.00', total: '₱360.00' },
                    { name: 'Soda', quantity: 2, price: '₱80.00', total: '₱160.00' },
                    { name: 'Ice Cream', quantity: 1, price: '₱230.75', total: '₱230.75' }
                ]
            };
            
            // Populate modal with data
            $('#detailTransaction').text(sampleData.transaction_number);
            $('#detailDate').text(sampleData.date);
            $('#detailCustomer').text(sampleData.customer);
            $('#detailTable').text(sampleData.table_no);
            $('#detailPayment').text(sampleData.payment_method);
            $('#detailStatus').text(sampleData.status);
            $('#detailSubtotal').text(sampleData.subtotal);
            $('#detailVat').text(sampleData.vat);
            $('#detailDiscount').text(sampleData.discount);
            $('#detailTotal').text(sampleData.total);
            $('#detailTendered').text(sampleData.tendered);
            $('#detailChange').text(sampleData.change);
            
            // Populate items table
            const itemsTable = $('#itemsTable tbody');
            itemsTable.empty();
            sampleData.items.forEach(item => {
                itemsTable.append(`
                    <tr>
                        <td>${item.name}</td>
                        <td>${item.quantity}</td>
                        <td>${item.price}</td>
                        <td>${item.total}</td>
                    </tr>
                `);
            });
            
            // Show the modal
            $('#detailsModal').modal('show');
        }
        
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
<script>
$(function() {
    $('#salesDateRange').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD'
        },
        opens: 'right',
        autoUpdateInput: false,
    });

    $('#salesDateRange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
    });

    $('#salesDateRange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
});
</script><?php /**PATH C:\xampp\htdocs\ease-pos\resources\views/script/salesScript.blade.php ENDPATH**/ ?>