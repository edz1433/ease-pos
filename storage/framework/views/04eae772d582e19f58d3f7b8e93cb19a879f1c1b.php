
<!-- jQuery -->
<script src="<?php echo e(asset('template/plugins/jquery/jquery.min.js')); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo e(asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo e(asset('template/dist/js/adminlte.min.js')); ?>"></script>

<!-- Toastr -->
<script src="<?php echo e(asset('template/plugins/toastr/toastr.min.js')); ?>"></script>
<!-- SweetAlert2 -->
<script src="<?php echo e(asset('template/plugins/sweetalert2/sweetalert2.min.js')); ?>"></script>

<!-- DataTables  & Plugins -->
<script src="<?php echo e(asset('template/plugins/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('template/plugins/datatables-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('template/plugins/datatables-buttons/js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')); ?>"></script> 
<script src="<?php echo e(asset('template/plugins/jszip/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('template/plugins/pdfmake/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('template/plugins/pdfmake/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('template/plugins/datatables-buttons/js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('template/plugins/datatables-buttons/js/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('template/plugins/datatables-buttons/js/buttons.colVis.min.js')); ?>"></script>

<!-- Select2 -->
<script src="<?php echo e(asset('template/plugins/select2/js/select2.full.min.js')); ?>"></script>

<!-- fullCalendar 2.2.5 -->
<script src="<?php echo e(asset('template/plugins/moment/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('template/plugins/fullcalendar/fullcalendar.js')); ?>"></script>
<script src="<?php echo e(asset('template/dist/js/luxon.min.js')); ?>"></script>



<script>
    // document.addEventListener('contextmenu', function (e) {
    //     e.preventDefault();
    // });
    <?php if(Session::has('error')): ?>
        toastr.options = {
            "closeButton":true,
            "progressBar":true,
            'positionClass': 'toast-bottom-right'
        }
        toastr.error("<?php echo e(session('error')); ?>")
    <?php endif; ?>
    
    <?php if(Session::has('error1')): ?>
        toastr.options = {
            "closeButton":true,
            "progressBar":true,
            'positionClass': 'toast-bottom-center'
        }
        toastr.error("<?php echo e(session('error1')); ?>")
    <?php endif; ?>

    <?php if(Session::has('success')): ?>
        toastr.options = {
            "closeButton":true,
            "progressBar":true,
            'positionClass': 'toast-bottom-right'
        }
        toastr.success("<?php echo e(session('success')); ?>")
    <?php endif; ?>

    <?php if($errors->any()): ?>
            var errorMessage = "";
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                errorMessage += "<?php echo e($error); ?>" + "<br>";
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right"
            };
            toastr.error(errorMessage);
    <?php endif; ?>


        $(function () {
            $("#table-1").DataTable({
                "responsive": false,
                "lengthChange": false, // Removes the "Show Entries" dropdown
                "autoWidth": true,
                "searching": false, // Hides the search input
                "paging": true, // Enables pagination
                "dom": '<"top">rt<"bottom"p><"clear">', // Pagination only at the bottom
                "pageLength": 9, // Sets the number of rows per page to 9
                //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $("#default-table").DataTable({
                "responsive": false,
                "lengthChange": false, 
                "autoWidth": true,
                "info": false,
                "paging": true,  // Ensure pagination is enabled
                "dom": '<"row"<"col-md-6 text-left"B><"col-md-6 text-right"f>>' +  // Buttons and search box at the top
                    '<"row"<"col-md-12"t>>' + // Table content in a row
                    '<"row"<"col-md-6"l><"col-md-6"p>>', // Length control and pagination at the bottom
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        title: 'Exported Data',
                        className: 'btn btn-success btn-sm',
                        text: '<i class="fas fa-file-excel"></i>' // Font Awesome Excel icon
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Exported Data',
                        className: 'btn btn-danger btn-sm',
                        text: '<i class="fas fa-file-pdf"></i>' // Font Awesome PDF icon
                    }
                ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');


        $("#example3").DataTable({
            "responsive": false,
            "lengthChange": false, 
            "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]

        }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');

        $('.select2').select2()
    });
   
</script>

<?php /**PATH C:\xampp\htdocs\ease-pos\resources\views/script/masterScriptCashier.blade.php ENDPATH**/ ?>