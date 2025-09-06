<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('topProducts').getContext('2d');

    const labels = @json($topProducts->pluck('row_number'));        // 1,2,3...
    const data = @json($topProducts->pluck('total_wvat'));
    const names = @json($topProducts->pluck('product_name'));       // for tooltips

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Sales',
                data: data.map(Number),
                backgroundColor: [
                    'rgba(252, 32, 79, 0.8)', 'rgba(252, 32, 100, 0.8)',
                    'rgba(252, 32, 120, 0.8)', 'rgba(252, 32, 140, 0.8)',
                    'rgba(252, 32, 160, 0.8)', 'rgba(32, 252, 194, 0.8)',
                    'rgba(32, 200, 194, 0.8)', 'rgba(32, 170, 194, 0.8)',
                    'rgba(32, 140, 194, 0.8)', 'rgba(32, 110, 194, 0.8)'
                ],
                borderColor: 'rgba(0,0,0,0.1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: context => names[context[0].dataIndex], // show product_name in tooltip
                        label: context => {
                            const value = parseFloat(context.raw) || 0;
                            return '₱' + value.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        display: false
                    },
                    grid: {
                        drawTicks: false
                    }
                },
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 0
                    },
                    grid: {
                        drawTicks: false
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