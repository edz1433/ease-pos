
<!-- jQuery -->
<script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>

<!-- Toastr -->
<script src="{{ asset('template/plugins/toastr/toastr.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<!-- DataTables  & Plugins -->
<script src="{{ asset('template/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script> 
<script src="{{ asset('template/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('template/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('template/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('template/plugins/select2/js/select2.full.min.js') }}"></script>

<!-- fullCalendar 2.2.5 -->
<script src="{{ asset('template/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('template/plugins/fullcalendar/fullcalendar.js') }}"></script>

<!-- Date Range Picker -->
<script src="{{ asset('template/plugins/daterangepicker/daterangepicker.js') }}"></script>

<!-- ChartJS -->
<script src="{{ asset('template/plugins/chart.js/Chart.min.js') }}"></script>

{{-- Notification --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.querySelector('[data-widget="pushmenu"]');
        toggleBtn.addEventListener('click', function (e) {
            e.preventDefault();
            document.body.classList.toggle('sidebar-text-hide');
        });
    });
</script>
<script>
    // document.addEventListener('contextmenu', function (e) {
    //     e.preventDefault();
    // });
    @if(Session::has('error'))
        toastr.options = {
            "closeButton":true,
            "progressBar":true,
            'positionClass': 'toast-bottom-right'
        }
        toastr.error("{{ session('error') }}")
    @endif
    
    @if(Session::has('error1'))
        toastr.options = {
            "closeButton":true,
            "progressBar":true,
            'positionClass': 'toast-bottom-center'
        }
        toastr.error("{{ session('error1') }}")
    @endif

    @if(Session::has('success'))
        toastr.options = {
            "closeButton":true,
            "progressBar":true,
            'positionClass': 'toast-bottom-right'
        }
        toastr.success("{{ session('success') }}")
    @endif

    @if($errors->any())
            var errorMessage = "";
            @foreach($errors->all() as $error)
                errorMessage += "{{ $error }}" + "<br>";
            @endforeach
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right"
            };
            toastr.error(errorMessage);
    @endif


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
{{-- Date Range --}}
<script>
  $(function () {
    let startOfYear = moment().startOf('year');   // January 1
    let endOfYear = moment().endOf('year');       // December 31

    $('#reservation').daterangepicker({
      showDropdowns: true,
      autoUpdateInput: true,
      startDate: startOfYear,
      endDate: endOfYear,
      locale: {
        format: 'MM/DD/YYYY',
        cancelLabel: 'Clear'
      }
    });

    // Optional: Clear input on cancel
    $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
    });
  });
</script>
<script>
$(document).on('click', '.delete-row', function(e){
    e.preventDefault();

    var id = $(this).data('id');  
    var model = $(this).data('model');  

    var url = "{{ route('delete') }}";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });

    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => { 
        if (result.isConfirmed){
            $.ajax({
                type: "POST",
                url: url,
                data: { 
                    id: id,
                    model: model
                },
                success: function (response) {  
                    $("#row-" + id).fadeOut(500);

                    Swal.fire({
                        title: 'Deleted!',
                        text: response.message,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    });

                    if(model == "PurchaseItem"){
                       location.reload();
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Something went wrong.',
                        icon: 'error'
                    });
                }
            });
        }
    })
});  
</script>


