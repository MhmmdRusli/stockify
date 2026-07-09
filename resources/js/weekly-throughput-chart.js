import ApexCharts from 'apexcharts';

function initWeeklyThroughputChart() {
    const chartElement = document.getElementById('weekly-throughput-chart');
    if (!chartElement) return;

    chartElement.innerHTML = '';

    let labels, dataIn, dataOut;
    try {
        labels = JSON.parse(chartElement.dataset.labels || '[]');
        dataIn = JSON.parse(chartElement.dataset.in || '[]');
        dataOut = JSON.parse(chartElement.dataset.out || '[]');
    } catch (e) {
        console.error('[weekly-throughput-chart] Gagal parse data dari atribut data-*:', e);
        return;
    }

    const netMovement = dataIn.map((num, idx) => num - (dataOut[idx] || 0));

    const options = {
        series: [
            { name: 'Net Pergerakan', type: 'line', data: netMovement },
            { name: 'Barang Masuk', type: 'column', data: dataIn },
            { name: 'Barang Keluar', type: 'column', data: dataOut }
        ],
        chart: {
            height: 280,
            type: 'line',
            stacked: false,
            toolbar: { show: false },
            fontFamily: 'Space Grotesk, sans-serif'
        },
        stroke: {
            width: [3, 0, 0],
            curve: 'smooth',
            dashArray: [0, 0, 0]
        },
        plotOptions: {
            bar: {
                columnWidth: '35%',
                borderRadius: 4
            }
        },
        colors: ['#f5a623', '#14b8a6', '#f43f5e'],
        fill: {
            opacity: [1, 0.85, 0.85]
        },
        labels: labels,
        markers: { size: [4, 0, 0] },
        xaxis: {
            type: 'category',
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { colors: '#9ca3af' } }
        },
        yaxis: {
            labels: { style: { colors: '#9ca3af' } }
        },
        tooltip: {
            shared: true,
            intersect: false,
            theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center',
            labels: { colors: document.documentElement.classList.contains('dark') ? '#fff' : '#111827' }
        },
        grid: {
            borderColor: 'rgba(156, 163, 175, 0.1)',
            strokeDashArray: 4
        }
    };

    const chart = new ApexCharts(chartElement, options);
    chart.render();

    // Re-render ulang saat mode gelap di-toggle, biar warna teks/legend ikut menyesuaikan
    document.addEventListener('dark-mode', function () {
        chart.updateOptions({
            tooltip: {
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
            },
            legend: {
                labels: { colors: document.documentElement.classList.contains('dark') ? '#fff' : '#111827' }
            }
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initWeeklyThroughputChart);
} else {
    initWeeklyThroughputChart();
}