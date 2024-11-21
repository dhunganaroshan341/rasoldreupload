<div>
    <canvas id="income-expense-chart"></canvas>
    <script src="{{ asset('assets/plugins/chart.js/dist/chart.umd.js') }}"></script>
    <script>
        var ctx = document.getElementById('income-expense-chart').getContext('2d');

        // Extracting amounts and dates from the incoming data
        var incomeAmounts = @json($incomes); // Array of income amounts
        var expenseAmounts = @json($expenses); // Array of expense amounts
        var labels = @json($transaction_dates); // Array of transaction dates

        // Prepare datasets for Chart.js
        var incomeData = labels.map((label, index) => incomeAmounts[index] || 0); // Match income with dates
        var expenseData = labels.map((label, index) => expenseAmounts[index] || 0); // Match expenses with dates

        var incomeExpenseChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels, // Transaction dates as labels
                datasets: [{
                    label: 'Income',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    data: incomeData, // Income data points
                    fill: true,
                }, {
                    label: 'Expenses',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    data: expenseData, // Expense data points
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Transaction Dates' // X-axis title
                        }
                    }
                }
            }
        });
    </script>
</div>
