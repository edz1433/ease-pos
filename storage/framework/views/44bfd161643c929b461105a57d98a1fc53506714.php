
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

<!-- Date Range Picker -->
<script src="<?php echo e(asset('template/plugins/daterangepicker/daterangepicker.js')); ?>"></script>

<!-- ChartJS -->
<script src="<?php echo e(asset('template/plugins/chart.js/Chart.min.js')); ?>"></script>


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
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('topProducts').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'Product A', 'Product B', 'Product C', 'Product D', 'Product E',
                'Product F', 'Product G', 'Product H', 'Product I', 'Product J'
            ],
            datasets: [{
                data: [12340, 11200, 9870, 9050, 8600, 7500, 7200, 6890, 6700, 6450],
                backgroundColor: [
                    'rgba(252, 32, 79, 0.8)', 'rgba(252, 32, 100, 0.8)', 'rgba(252, 32, 120, 0.8)',
                    'rgba(252, 32, 140, 0.8)', 'rgba(252, 32, 160, 0.8)', 'rgba(32, 252, 194, 0.8)',
                    'rgba(32, 200, 194, 0.8)', 'rgba(32, 170, 194, 0.8)', 'rgba(32, 140, 194, 0.8)',
                    'rgba(32, 110, 194, 0.8)'
                ],
                borderColor: [
                    'rgba(252, 32, 79, 1)', 'rgba(252, 32, 100, 1)', 'rgba(252, 32, 120, 1)',
                    'rgba(252, 32, 140, 1)', 'rgba(252, 32, 160, 1)', 'rgba(32, 252, 194, 1)',
                    'rgba(32, 200, 194, 1)', 'rgba(32, 170, 194, 1)', 'rgba(32, 140, 194, 1)',
                    'rgba(32, 110, 194, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y', // ⬅️ This makes it horizontal
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '₱' + value.toLocaleString()
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        title: () => '',
                        label: context => '₱' + context.raw.toLocaleString()
                    }
                }
            }
        }
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('monthlySalesChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ],
            datasets: [{
                data: [15000, 17200, 16500, 18300, 19000, 21000, 22000, 20500, 19800, 21500, 23000, 24500],
                backgroundColor: 'rgba(252, 32, 79, 0.1)',
                borderColor: 'rgba(252, 32, 160, 0.8)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(252, 32, 160, 0.8)',
                tension: 0.3, // Smooth curve
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '₱' + value.toLocaleString()
                    }
                }
            },
            plugins: {
                legend: {
                    display: false // No legend needed for single dataset
                },
                tooltip: {
                    callbacks: {
                        title: context => context[0].label, // Month
                        label: context => '₱' + context.raw.toLocaleString() // Value
                    }
                }
            }
        }
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('salesCategoryChart').getContext('2d');

    const data = [30000, 25000, 20000, 15600, 10500]; // sample values
    const total = data.reduce((a, b) => a + b, 0);

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Food', 'Beverage', 'Grocery', 'Health', 'Others'],
            datasets: [{
                data: data,
                backgroundColor: [
                    'rgba(252, 32, 140, 0.8)',  'rgba(32, 252, 194, 0.8)', 'rgba(252, 32, 160, 0.8)', 
                    'rgba(32, 200, 194, 0.8)', 'rgba(32, 170, 194, 0.8)',
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const value = context.raw;
                            const percent = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ₱${value.toLocaleString()} (${percent}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('profitChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ],
            datasets: [{
                label: 'Profit Margin (Avg: 48.2%)',
                data: [42.1, 45.0, 47.2, 46.3, 48.5, 49.1, 50.2, 48.9, 47.0, 46.5, 49.4, 50.1],
                borderColor: 'rgba(32, 252, 194, 0.8)',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                pointBackgroundColor: 'rgba(32, 252, 194, 0.8)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: value => value + '%'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: context => context.raw + '%'
                    }
                }
            }
        }
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('lowStockChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                <?php
                    echo "'Coke 1L', 'Sprite 1.5L', 'Pepsi 500ml', 'Royal 1L', 'Mountain Dew 330ml', 'C2 Apple', 'Mineral Water', 'Iced Tea', 'Gatorade Blue', 'Nestea'";
                ?>
            ],
            datasets: [{
                label: 'Remaining Stocks',
                data: [
                    <?php
                        echo "4, 6, 3, 2, 8, 5, 1, 7, 3, 0";
                    ?>
                ],
                backgroundColor: [
                    <?php
                        echo "'rgba(252, 32, 79, 0.8)', 'rgba(252, 32, 100, 0.8)', 'rgba(252, 32, 120, 0.8)',";
                        echo "'rgba(252, 32, 140, 0.8)', 'rgba(252, 32, 160, 0.8)', 'rgba(32, 252, 194, 0.8)',";
                        echo "'rgba(32, 200, 194, 0.8)', 'rgba(32, 170, 194, 0.8)', 'rgba(32, 140, 194, 0.8)', 'rgba(32, 110, 194, 0.8)'";
                    ?>
                ],
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.parsed.y + ' remaining';
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Low Stock Alert (Threshold: 5)',
                    font: { size: 16 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: '#eaeaea'
                    }
                },
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 40,
                        minRotation: 40
                    }
                }
            }
        }
    });
});
</script>



<?php /**PATH F:\xampp\htdocs\ease-pos\resources\views/script/masterScript.blade.php ENDPATH**/ ?>