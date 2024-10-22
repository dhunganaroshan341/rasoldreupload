<div>
    {{-- Optional: Uncomment to debug the data --}}
    {{-- @php
        dd($incomes, $expenses); // Check the values here
    @endphp --}}

    <canvas id="income-expense-chart"></canvas>
    <script src="{{ asset('assets/plugins/chart.js/dist/chart.umd.js') }}"></script>
    <script>
        var ctx = document.getElementById('income-expense-chart').getContext('2d');

        // Extracting amounts and dates from incomes and expenses
        var incomeAmounts = @json($incomes->pluck('amount'));
        var expenseAmounts = @json($expenses->pluck('amount'));

        // Format dates in PHP before passing to JavaScript
        var incomeDates = @json(
            $incomes->pluck('created_at')->map(function ($date) {
                return $date->format('Y-m-d'); // Formatting the date
            }));
        var expenseDates = @json(
            $expenses->pluck('created_at')->map(function ($date) {
                return $date->format('Y-m-d'); // Formatting the date
            }));

        // Combine dates for x-axis labels
        var labels = [...new Set([...incomeDates, ...expenseDates])].sort(); // Unique sorted dates

        // Prepare datasets for Chart.js
        var incomeData = labels.map(label => {
            const index = incomeDates.indexOf(label);
            return index !== -1 ? incomeAmounts[index] : 0; // Income amount or 0 if no income
        });

        var expenseData = labels.map(label => {
            const index = expenseDates.indexOf(label);
            return index !== -1 ? expenseAmounts[index] : 0; // Expense amount or 0 if no expense
        });

        var incomeExpenseChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Income',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    data: incomeData,
                    fill: true,
                }, {
                    label: 'Expenses',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    data: expenseData,
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
                            text: 'Transaction Dates'
                        }
                    }
                }
            }
        });
    </script>
</div>
