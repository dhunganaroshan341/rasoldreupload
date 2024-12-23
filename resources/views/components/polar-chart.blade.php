<canvas id="polar-area-chart" width="500" height="500"></canvas>

@push('script-items')
    <script src="{{ asset('assets/plugins/chart.js/dist/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/js/demo/chart-js.demo.js') }}"></script>
    <script src="{{ asset('assets/plugins/@highlightjs/cdn-assets/highlight.min.js') }}"></script>
    <script src="{{ asset('assets/js/demo/render.highlight.js') }}"></script>
    <script>
        // Get the client ID from Blade variable
        const clientId = @json($client->id); // Assuming $client is passed from the controller

        // Function to fetch chart data from the API
        async function fetchChartData() {
            try {
                const response = await fetch(
                    `/api/client/${clientId}/chart-data`); // Correct API endpoint with dynamic client ID
                const chartData = await response.json(); // Parse the JSON response

                if (chartData.success) {
                    // Extract labels and data from the response
                    const labels = chartData.data.labels;
                    const data = chartData.data.data;

                    // Render the Polar Area Chart with the fetched data
                    renderPolarAreaChart('polar-area-chart', labels, data);
                } else {
                    console.error('No data available for chart.');
                }
            } catch (error) {
                console.error('Error fetching chart data:', error);
            }
        }

        // Call the fetchChartData function to load the chart data
        fetchChartData();

        function renderPolarAreaChart(chartId, chartLabels, chartData) {
            var ctx = document.getElementById(chartId).getContext('2d');
            var polarAreaChart = new Chart(ctx, {
                type: 'polarArea',
                data: {
                    labels: chartLabels, // Labels dynamically passed
                    datasets: [{
                        data: chartData, // Data dynamically passed
                        backgroundColor: generateColors(chartData.length), // Dynamic background colors
                        borderColor: generateColors(chartData.length, true), // Dynamic border colors
                        borderWidth: 2,
                        label: 'Polar Chart'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            ticks: {
                                beginAtZero: true,
                                max: Math.max(...chartData) *
                                    1.2, // Set max value slightly higher than the highest value
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': $' + tooltipItem.raw
                                        .toLocaleString(); // Format tooltip with currency
                                }
                            }
                        }
                    }
                }
            });
        }

        function generateColors(count, border = false) {
            const colors = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6c757d'];
            const result = [];
            for (let i = 0; i < count; i++) {
                result.push(border ? colors[i % colors.length] : `${colors[i % colors.length]}80`);
            }
            return result;
        }
    </script>
@endpush



<!-- ================== BEGIN page-js ================== -->
<script src="../assets/plugins/chart.js/dist/chart.umd.js"></script>
<script src="../assets/js/demo/chart-js.demo.js"></script>
<script src="../assets/plugins/@highlightjs/cdn-assets/highlight.min.js"></script>
<script src="../assets/js/demo/render.highlight.js"></script>
<!-- ================== END page-js ================== -->
