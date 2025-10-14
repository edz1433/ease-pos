<script>
document.addEventListener("DOMContentLoaded", function () {
    const topProductsCtx   = document.getElementById('topProducts').getContext('2d');
    const categoryPieCtx   = document.getElementById('categoryPie').getContext('2d');
    const grossSalesProfitCtx   = document.getElementById('grossSalesAnalyticsChart').getContext('2d');
    const netSalesProfitCtx   = document.getElementById('netSalesAnalyticsChart').getContext('2d');

    let topProductsChart, categoryPieChart, grossSalesProfitChart, netSalesProfitChart;

    function loadDashboardData(filter = 'month', category = null, startDate = null, endDate = null) {
        $.ajax({
            url: "<?php echo e(route('dashboardData')); ?>",
            method: "GET",
            data: { filter, category, start_date: startDate, end_date: endDate },
            success: function (res) {
                // ---------------- Top Products (Bar) ----------------
                if (topProductsChart) topProductsChart.destroy();

                topProductsChart = new Chart(topProductsCtx, {
                    type: 'bar',
                    data: {
                        labels: res.labels,
                        datasets: [{
                            label: 'Total Sold',
                            data: res.data.map(Number),
                            backgroundColor: [
                                'rgba(252, 32, 79, 0.8)', 'rgba(252, 32, 100, 0.8)',
                                'rgba(252, 32, 120, 0.8)', 'rgba(252, 32, 140, 0.8)',
                                'rgba(252, 32, 160, 0.8)', 'rgba(32, 252, 194, 0.8)',
                                'rgba(32, 200, 194, 0.8)', 'rgba(32, 170, 194, 0.8)',
                                'rgba(32, 140, 194, 0.8)', 'rgba(32, 110, 194, 0.8)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    title: ctx => res.names[ctx[0].dataIndex],
                                    label: ctx => `Sold: ${ctx.raw}`
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true },
                            x: { ticks: { autoSkip: false, maxRotation: 45 } }
                        }
                    }
                });

                // ---------------- Categories (Modern Doughnut) ----------------
                if (categoryPieChart) categoryPieChart.destroy();

                categoryPieChart = new Chart(categoryPieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: res.categories.labels,
                        datasets: [{
                            data: res.categories.data.map(Number),
                            backgroundColor: [
                                'rgba(252, 32, 79, 0.8)', 'rgba(252, 32, 100, 0.8)',
                                'rgba(252, 32, 120, 0.8)', 'rgba(252, 32, 140, 0.8)',
                                'rgba(252, 32, 160, 0.8)', 'rgba(32, 252, 194, 0.8)',
                                'rgba(32, 200, 194, 0.8)', 'rgba(32, 170, 194, 0.8)',
                                'rgba(32, 140, 194, 0.8)', 'rgba(32, 110, 194, 0.8)'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff',
                            hoverOffset: 12,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: ctx => `${ctx.label}: ${ctx.raw}%`
                                }
                            },
                            title: {
                                display: true,
                                text: 'Category Sales Distribution',
                                font: { size: 18, weight: 'bold' },
                                padding: { top: 10, bottom: 20 }
                            },
                            legend: {
                                position: 'right',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 15,
                                    font: { size: 13 }
                                }
                            }
                        }
                    },
                    plugins: [
                        {
                            id: 'centerText',
                            beforeDraw(chart) {
                                const {width, height} = chart;
                                const ctx = chart.ctx;
                                ctx.save();
                                const total = res.categories.data.reduce((a, b) => a + Number(b), 0);
                                ctx.font = "bold 16px sans-serif";
                                ctx.fillStyle = "#444";
                                ctx.textAlign = "center";
                                ctx.textBaseline = "middle";
                                ctx.fillText("Total", width / 2, height / 2 - 10);
                                ctx.font = "bold 18px sans-serif";
                                ctx.fillStyle = "#111";
                                ctx.fillText(total + "%", width / 2, height / 2 + 15);
                                ctx.restore();
                            }
                        }
                    ]
                });

                // ---------------- Top Products List ----------------
                const listContainer = document.getElementById("topProductsList");
                listContainer.innerHTML = "";
                res.list.forEach(item => {
                    const li = document.createElement("li");
                    li.className = "list-group-item py-1 px-2";
                    li.style.fontSize = "0.8rem";
                    li.textContent = `${item.rank}. ${item.name}`;
                    listContainer.appendChild(li);
                });

                // ---------------- Gross Sales & Profit (Line) ----------------
                if (grossSalesProfitChart) grossSalesProfitChart.destroy();

                grossSalesProfitChart = new Chart(grossSalesProfitCtx, {
                    type: 'line',
                    data: {
                        labels: res.analytics.labels,
                        datasets: [
                            {
                                label: 'Sales',
                                data: res.analytics.sales.map(Number),
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                tension: 0.3,
                                fill: true
                            },
                            {
                                label: 'Profit',
                                data: res.analytics.profit.map(Number),
                                borderColor: 'rgba(255, 99, 132, 1)',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                tension: 0.3,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: { display: true, text: 'POS Daily Sales & Profit', font: { size: 18 } },
                            legend: { position: 'top' }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { callback: val => '₱' + val.toLocaleString() }
                            },
                            x: { title: { display: true, text: 'Date' } }
                        }
                    }
                });

                // ---------------- Net Sales & Profit (Line) ----------------
                if (netSalesProfitChart) netSalesProfitChart.destroy();

                netSalesProfitChart = new Chart(netSalesProfitCtx, {
                    type: 'line',
                    data: {
                        labels: res.analytics.labels,
                        datasets: [
                            {
                                label: 'Sales',
                                data: res.analytics.sales.map(Number),
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                tension: 0.3,
                                fill: true
                            },
                            {
                                label: 'Profit',
                                data: res.analytics.profit.map(Number),
                                borderColor: 'rgba(255, 99, 132, 1)',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                tension: 0.3,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: { display: true, text: 'POS Daily Sales & Profit', font: { size: 18 } },
                            legend: { position: 'top' }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { callback: val => '₱' + val.toLocaleString() }
                            },
                            x: { title: { display: true, text: 'Date' } }
                        }
                    }
                });

            }
        });
    }

    // ---------------- Filter Form ----------------
    $("#filterForm").on("submit", function (e) {
        e.preventDefault();
        const filter = $("#filterSelect").val();
        const category = $("#category").val();
        const start = $("#startDate").val();
        const end = $("#endDate").val();
        loadDashboardData(filter, category, start, end);
    });

    $("#filterSelect").on("change", function () {
        if ($(this).val() === "custom") {
            $("#startDate, #endDate").show();
        } else {
            $("#startDate, #endDate").hide().val("");
        }
    });

    // ---------------- Initial Load ----------------
    loadDashboardData();
});
</script>

<script>
document.getElementById('filterSelect').addEventListener('change', function () {
    const showCustom = this.value === 'custom';
    document.getElementById('startDate').style.display = showCustom ? 'inline-block' : 'none';
    document.getElementById('endDate').style.display = showCustom ? 'inline-block' : 'none';
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
const ctx = document.getElementById('grossSalesProfitChart').getContext('2d');

const grossSalesProfitChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Sep 1', 'Sep 2', 'Sep 3', 'Sep 4', 'Sep 5', 'Sep 6', 'Sep 7'], // Dates
        datasets: [
            {
                label: 'Sales',
                data: [5000, 7000, 6500, 8000, 9000, 7500, 8500],
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.3,
                fill: true
            },
            {
                label: 'Profit',
                data: [2500, 3200, 3000, 4000, 4500, 3700, 4200],
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.3,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'POS Daily Sales & Profit',
                font: { size: 18 }
            },
            legend: {
                position: 'top'
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Amount (₱)'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Date'
                }
            }
        }
    }
});
</script><?php /**PATH E:\xampp\htdocs\ease-pos\resources\views/script/dashboardScript.blade.php ENDPATH**/ ?>