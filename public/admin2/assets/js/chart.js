document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Cek Canvas
    var canvas = document.getElementById("chart-bars");
    if (!canvas) return; // Hentikan jika elemen tidak ada

    var ctx = canvas.getContext("2d");

    // 2. Ambil Data dari Jembatan Window
    var dataChart = window.dashboardData || { labels: [], values: [] };

    // 3. Render Chart
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: dataChart.labels,
            datasets: [{
                label: "Jumlah Kejadian",
                tension: 0.4,
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
                backgroundColor: "#4e73df", // Warna Biru
                data: dataChart.values,
                maxBarThickness: 30
            }, ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5],
                        color: '#f0f2f5'
                    },
                    ticks: {
                        suggestedMin: 0,
                        beginAtZero: true,
                        padding: 10,
                        font: {
                            size: 12,
                            family: "Roboto",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#7b809a"
                    },
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false, 
                        drawOnChartArea: false, 
                        drawTicks: false,
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        font: {
                            size: 11,
                            family: "Roboto",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#7b809a"
                    }
                },
            },
        },
    });
});