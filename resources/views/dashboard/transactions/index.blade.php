@extends('layouts.main')
@section('head_file')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
@endsection
@section('script')
@endsection
@section('content')
    @php
        // Initialize variables
        // this php section is used in transactio_ribbon_bottom.blade inside components
        $totalIncome = 0;
        $totalExpense = 0;
        $filteredBalance = 0;
        // Iterate over merged transactions
        foreach ($mergedTransactions as $transaction) {
            if ($transaction['type'] === 'income') {
                $totalIncome += $transaction['amount'];
            } elseif ($transaction['type'] === 'expense') {
                $totalExpense += $transaction['amount'];
            }
        }
        // Calculate the filtered balance
        $filteredBalance = $totalIncome - $totalExpense;
        // Add starting amount to get the final balance
        $filteredBalance = $startingAmount['totalBalanceUpTo'] + $filteredBalance;
    @endphp

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Transaction Records</h2>

        <x-session-success />
        {{-- jquery alert --}}
        <div id="alertContainer"></div>
        {{-- to show alert message from x-income-modal  --}}


        <!-- Toggle Buttons -->
        <div class="d-flex justify-content-between mb-3 flex-wrap">
            <button class="btn btn-light btn-sm" type="button" data-toggle="collapse" data-target="#filterContainer"
                aria-expanded="true" aria-controls="filterContainer">
                <span id="filterToggleIcon" class="mr-2">&#x25BC;</span> Filter
            </button>
            <button class="btn btn-light btn-sm" type="button" data-toggle="collapse" data-target="#summaryContainer"
                aria-expanded="true" aria-controls="summaryContainer">
                <span id="summaryToggleIcon" class="mr-2">&#x25BC;</span> Summary & Actions
            </button>
        </div>

        <!-- Filter Container -->
        <div class="collapse show" id="filterContainer">
            <div class="card mb-3 shadow-sm border-light">
                <div class="card-body">
                    @include('component.date_filter_transactions')
                </div>
            </div>
        </div>

        <!-- Summary & Actions Container -->
        <div class="collapse show" id="summaryContainer">
            <div class="card mb-3 shadow-sm border-light">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
                        <!-- Account Summary (Left Side) -->
                        <div class="flex-fill mb-3 mb-md-0">
                            @include('component.account_summary_transactions')
                        </div>

                        <!-- Buttons (Right Side) -->
                        <div class="d-flex flex-column flex-md-row">
                            <a data-toggle="modal" data-target="#incomeModal"
                                class="btn btn-sm btn-success text-white mb-2 mb-md-0 mr-md-2">
                                <i class="dw dw-add"></i> Income Record
                            </a>
                            <a data-toggle="modal" data-target="#expenseModal" class="btn btn-sm btn-dark text-white">
                                <i class="dw dw-add"></i> Expense Record
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Table -->
        <div class="table-responsive">
            <table id="data-table-buttons" width="100%" class="table table-bordered align-middle">
                <thead class="bg-light">
                    <tr>
                        <th colspan="5" class="text-center bg-success text-white">Income</th>
                        <th colspan="5" class="text-center bg-dark text-white">Expense</th>
                    </tr>
                    <tr>
                        <!-- Income Table Headers -->
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Source</th>
                        <th>Medium</th>
                        <th>Remarks</th>
                        <!-- Expense Table Headers -->
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Source</th>
                        <th>Medium</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paginatedTransactions as $transaction)
                        <tr>
                            @if ($transaction['type'] === 'income')
                                <!-- Income Columns -->
                                <td>{{ $transaction['transaction_date'] }}</td>
                                <td>{{ $transaction['amount'] }}</td>
                                <td class="source">
                                    <span class="bg-success text-white">
                                        @php
                                            $client_id = null;

                                            if (
                                                isset($transaction['client_service']) &&
                                                $transaction['client_service'] != null
                                            ) {
                                                $client_id =
                                                    \App\Models\ClientService::find($transaction['client_service'])
                                                        ->client->id ?? null;
                                            }
                                        @endphp

                                        <a href="{{ $client_id ? route('ledger.show', $client_id) : '#' }}">
                                            {{ \App\Models\ClientService::find($transaction['client_service'])->client->name ?? 'no specific name' }}
                                        </a>

                                    </span>--
                                    <span class="bg-white text-success">
                                        {{ \App\Models\ClientService::find($transaction['client_service'])->service->name ?? 'no specific service' }}
                                    </span>

                                    <a href="{{ $transaction['income_id'] ? route('incomes.edit', $transaction['income_id']) : '#' }}"
                                        class="btn btn-link text-dark">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                                <td>{{ $transaction['medium'] }}</td>
                                <td>{{ $transaction['remarks'] }}</td>

                                <!-- Empty Expense Columns -->
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            @else
                                <!-- Empty Income Columns -->
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                                <!-- Expense Columns -->
                                <td>{{ $transaction['transaction_date'] }}</td>
                                <td>{{ $transaction['amount'] }}</td>
                                <td class="source">
                                    <span class="bg-white text-dark">
                                        @php
                                            $expense_client_id = null;

                                            if (
                                                isset($transaction['expense_client_service']) &&
                                                $transaction['expense_client_service'] != null
                                            ) {
                                                $expense_client_id =
                                                    \App\Models\ClientService::find(
                                                        $transaction['expense_client_service'],
                                                    )->client->id ?? null;
                                            }
                                        @endphp

                                        <a
                                            href="{{ $expense_client_id ? route('ledger.show', $expense_client_id) : '#' }}">
                                            {{ \App\Models\ClientService::find($transaction['client_service'])->client->name ?? 'no specific name' }}
                                        </a>
                                    </span>--
                                    </span>--
                                    <span class="bg-white text-danger">
                                        {{ \App\Models\ClientService::find($transaction['client_service'])->service->name ?? 'no specific service' }}
                                    </span>

                                    <a href="{{ $transaction['expense_id'] ? route('expenses.edit', $transaction['expense_id']) : '#' }}"
                                        class="btn btn-link text-white">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                                <td>{{ $transaction['medium'] }}</td>
                                <td>{{ $transaction['remarks'] }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        {{-- Pagination links --}}
        @include('component.pagination_links_transactions')

        <!-- Ribbon -->
        @include('component.transaction_ribbon_bottom')

        <!-- Income Modal -->
        @include('component.income_modal_new')

        <!-- Expense Modal -->
        {{-- @include('component.expense_modal') --}}
        @include('dashboard.expenses.expense_modal')
    </div>


    <script>
        $(document).ready(function() {
            $('#editIncomeModal').modal('show');
            // Function to export transactions
            function exportTransactions() {
                const startDate = $('#start_date').val(); // Using jQuery to get start date value
                const endDate = $('#end_date').val(); // Using jQuery to get end date value

                const url = '{{ route('transactions.export') }}' + `?start_date=${startDate}&end_date=${endDate}`;

                window.location.href = url;
            }

            $(document).on('click', '.editTransaction[data-target="#editIncomeModal"]', function() {
                var incomeId = $(this).data('id');
                var incomeSource = $(this).data('source');
                var transactionDate = $(this).data('transaction_date');
                var amount = $(this).data('amount');
                var medium = $(this).data('medium');

                ;
                console.log('Income ID:', incomeId);
                console.log('Income Source:', incomeSource);
                console.log('Transaction Date:', transactionDate);
                console.log('Amount:', amount)
                console.log('Medium:', medium);

                $('#income_id').val(incomeId);
                $('#income_source').val(incomeSource);
                $('#income_transaction_date').val(transactionDate);
                $('#income_amount').val(amount);
                $('#medium').val(medium);

                $('#editIncomeModal').modal('show');
            });


            // Event handler for editing expense transactions
            $(document).on('click', '.editTransaction[data-target="#editExpenseModal"]', function() {
                // Extract data attributes from the clicked element
                var expenseId = $(this).data('id');
                var expenseSource = $(this).data('source');
                var expenseTransactionDate = $(this).data('transaction_date');
                var expenseAmount = $(this).data('amount');
                var expenseMedium = $(this).data('medium');
                $('#editExpenseModal').modal('show');
                alert('Expenses:id:' + expenseId, +' expense source: ' + expenseSource);
                // Populate the modal form fields
                $('#expense_id').val(expenseId);
                $('#expense_source').val(expenseSource);
                $('#expense_transaction_date').val(expenseTransactionDate);
                $('#expense_amount').val(expenseAmount);
                $('#expense_medium').val(expenseMedium);

                // Show the modal

            });

            Function to handle income modal population
            $(document).on('click', '.editTransaction[data-target="#editIncomeModal"]', function() {
                var incomeId = $(this).data('id');

                $.ajax({
                    url: 'incomes/' + incomeId + 'edit',
                    method: 'GET',
                    success: function(response) {
                        // Populate the modal with the data
                        $('#income_id').val(response.id);
                        $('#income_source').val(response.source);
                        $('#income_transaction_date').val(response
                            .transaction_date);
                        $('#income_amount').val(response.amount);
                        $('#medium').val(response.medium);

                        // Show the modal
                        $('#editIncomeModal').modal('show');
                    },
                    error: function(xhr) {
                        console.error('Error fetching data');
                    }
                });
            });

            // Handle form submission via AJAX
            $('#incomeForm').on('submit', function(event) {
                event.preventDefault();

                let formData = $(this).serialize();
                let url =
                    'incomes'; // Make sure this route matches your controller method

                $.ajax({
                    url: 'incomes/' + incomeId + '/update',
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        // Optionally, display a success message or close the modal
                        $('#editIncomeModal').modal('hide');
                        location.reload(); // Or update the table without reloading
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = '';
                        $.each(errors, function(key, value) {
                            errorMessages += `<li>${value}</li>`;
                        });
                        $('#incomeErrorMessages').removeClass('d-none').html(
                            errorMessages);
                    }
                });
            });

            // Optional: Open the modal based on URL parameter
            if (window.location.search.includes('open-income-modal=true')) {
                $('#editIncomeModal').modal('show');
            }



        });
    </script>
@endsection
@section('footer_file')
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/dist/jszip.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#data-table-buttons').DataTable({
                responsive: true,
                dom: '<"row mb-3"<"col-md-6"B><"col-md-6"fr>>t<"row mt-3"<"col-md-auto me-md-auto"i><"col-md-auto ms-md-auto"p>>',
                buttons: [{
                        extend: 'copy',
                        className: 'btn-sm'
                    },
                    {
                        extend: 'csv',
                        className: 'btn-sm'
                    },
                    {
                        extend: 'excel',
                        className: 'btn-sm'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn-sm'
                    },
                    {
                        extend: 'print',
                        className: 'btn-sm'
                    }
                ],
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-form');

            deleteForms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    const confirmed = confirm('Are you sure you want to delete this client?');
                    if (!confirmed) {
                        event.preventDefault(); // Prevent form submission if not confirmed
                    }
                });
            });
        });
    </script>

    <script src="{{ asset('js/income.js') }}"></script>
    <script src="{{ asset('js/expense.js') }}"></script>
    <script src="{{ asset('js/transactionIndex.js') }}"></script>
@endsection
