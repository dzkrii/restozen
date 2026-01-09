@props(['stats'])

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 sm:px-6 sm:pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Penjualan Bulanan
        </h3>
    </div>

    <div class="max-w-full overflow-x-auto custom-scrollbar mt-4">
        <div id="chartOne" class="-ml-5 h-[300px] w-full min-w-[690px] pl-2 xl:min-w-full"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const options = {
            series: [{
                name: 'Total Revenue',
                data: @json($stats['monthly_sales'])
            }],
            chart: {
                type: 'area',
                height: 300,
                fontFamily: 'Inter, sans-serif',
                toolbar: {
                    show: false,
                },
                zoom: {
                    enabled: false
                },
            },
            colors: ['#10B981'], // Brand color (Teal)
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: 'smooth',
                width: 2,
            },
            xaxis: {
                categories: @json($stats['months']),
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    style: {
                        colors: '#9ca3af',
                        fontSize: '12px',
                    },
                },
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#9ca3af',
                        fontSize: '12px',
                    },
                    formatter: function (value) {
                        if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                        if (value >= 1000) return (value / 1000).toFixed(1) + 'K';
                        return value;
                    }
                },
            },
            grid: {
                strokeDashArray: 5,
                borderColor: '#e5e7eb',
                yaxis: {
                    lines: {
                        show: true,
                    },
                },
                xaxis: {
                    lines: {
                        show: false,
                    },
                },
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 10,
                },
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function (val) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val);
                    }
                }
            },
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
        };

        // Dark mode adapter
        if (document.documentElement.classList.contains('dark')) {
            options.grid.borderColor = '#374151';
            options.chart.foreColor = '#9ca3af';
            options.tooltip.theme = 'dark';
        }

        const chart = new ApexCharts(document.querySelector("#chartOne"), options);
        chart.render();
    });
</script>
