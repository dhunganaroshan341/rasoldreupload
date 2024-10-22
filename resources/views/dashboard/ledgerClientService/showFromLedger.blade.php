@extends('layouts.main')

@section('head_file')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
@endsection
{{-- adding income expenses  creation button --}}
@section('header-left')
    <x-add-income-expense-link-button />
@endsection
@section('content')
    @include('dashboard.invoices.invoice_form_modal')

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2>ClientService Ledger</h2>
            <!-- Trigger modal on button click -->
            <button class="btn btn-primary" id="generateInvoice" data-toggle="modal" data-target="#invoiceModal">
                Generate Invoice &nbsp<i class="fa fa-download"></i>
            </button>

        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Ledger Entries
                        <span class="bg-danger text-light fs-6">
                            {{ $clientService->name ?? $clientService->client->name . '-' . $clientService->service->name }}
                        </span>

                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data-table-default" class="table table-striped table-bordered align-middle">
                                <thead>
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"> Select</th>
                                            <th>Date</th>
                                            {{-- <th>Source</th> --}}
                                            <th>Medium</th>
                                            <th>Debit (Expense)</th>
                                            <th>Credit (Income)</th>
                                            <th>Balance</th>
                                            <th>Summary</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    @php $balance = 0; @endphp
                                    @foreach ($ledgers as $ledger)
                                        {{-- Ledger Calculations --}}
                                        @php
                                            $balance +=
                                                $ledger->transaction_type == 'income'
                                                    ? $ledger->amount
                                                    : -$ledger->amount;
                                            $client_service_name = $ledger->clientService->name ?? 'N/A';
                                        @endphp
                                        <tr>
                                            <td><input type="checkbox" class="ledger-checkbox" value="{{ $ledger->id }}">
                                            </td>
                                            <td>{{ $ledger->transaction_date }}</td>
                                            {{-- <td><a
                                                    href="{{ route('ClientServices.edit', ['client_service_id' => $ledger->clientService->id]) }}">{{ $ledger->clientService->name }}</a>
                                            </td> --}}
                                            <td>{{ $ledger->medium }}</td>
                                            <td>{{ $ledger->transaction_type == 'expense' ? '$' . number_format($ledger->amount, 2) : '--' }}
                                            </td>
                                            <td>{{ $ledger->transaction_type == 'income' ? '$' . number_format($ledger->amount, 2) : '--' }}
                                            </td>
                                            <td>${{ number_format($balance, 2) }}</td>
                                            <td>{{ $ledger->clientService->remaining_amount ? "$client_service_name - Remaining: $" . number_format($ledger->clientService->remaining_amount, 2) : 'cleared' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Display Ledger Calculation Totals --}}
                        <div class="mt-4">
                            <h4>Ledger Summary
                                <br>
                                <h3 class="text-info"> {{ $clientServiceName }}</h3>
                            </h4>
                            <p class="mt-2"><strong>Total Amount :</strong>
                                ${{ number_format($clientService->amount, 2) }}
                            </p>
                            <p><strong>Total Income:</strong>
                                ${{ number_format($ledgerCalculationForClientService['clientServiceTotalIncome'], 2) }}</p>
                            <p><strong>Total Expenses:</strong>
                                ${{ number_format($ledgerCalculationForClientService['clientServiceTotalExpense'], 2) }}
                            </p>
                            <p><strong>Balance:</strong>
                                ${{ number_format($ledgerCalculationForClientService['clientServiceBalance'], 2) }}</p>
                        </div>

                        <button id="process-selected" class="btn btn-success mt-3">Process Selected Ledgers</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_file')
    <script>
        $(document).ready(function() {
            // Handle "Generate Invoice" button click
            $('#generateInvoice').click(function() {
                var selectedIds = [];

                // Get all selected checkbox values (IDs)
                $('.ledger-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    alert('Please select at least one ledger entry.');
                    return;
                }

                // Send AJAX request to fetch details of selected records
                $.ajax({
                    url: '/get-multiple-details', // Route to fetch selected ledger data from Laravel
                    method: 'GET',
                    data: {
                        ids: selectedIds
                    },
                    success: function(response) {
                        if (response.success) {
                            // Clear previous content in the modal
                            $('#invoiceDetailsList').empty();

                            var totalClientServiceAmount = response.total_client_service_amount;
                            var totalPaidAmount = 0;

                            // Populate modal with fetched data
                            $.each(response.data, function(index, item) {
                                // Ensure item.amount and related values are numbers
                                var itemAmount = parseFloat(item.amount) || 0;
                                var clientServiceAmount = parseFloat(item.client_service
                                    .amount) || 0;
                                var remainingAmount = parseFloat(item.client_service
                                    .remaining_amount) || 0;

                                totalPaidAmount += clientServiceAmount;

                                $('#invoiceDetailsList').append(`
                                <tr>
                                    <td>${item.client_service.name ? item.client_service.name : 'N/A'}</td>
                                    <td>$${itemAmount.toFixed(2)}</td>
                                    <td>$${clientServiceAmount.toFixed(2)}</td>
                                    <td>$${remainingAmount.toFixed(2)}</td>
                                </tr>
                            `);
                            });

                            // Update total amounts in the modal
                            $('#totalPaidAmount').text('$' + totalPaidAmount.toFixed(2));
                            $('#totalClientServiceAmount').text('$' + totalClientServiceAmount
                                .toFixed(2));

                            // Show the invoice modal
                            $('#invoiceModal').modal('show');
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while fetching the details.');
                    }
                });
            });

            // Process invoice generation when the "Generate Invoice" button is clicked
            $('#generateInvoiceButton').click(function() {
                var totalPaidAmount = parseFloat($('#totalPaidAmount').text().replace('$', '')) || 0;
                var totalClientServiceAmount = parseFloat($('#totalClientServiceAmount').text().replace('$',
                    ''));

                if (totalPaidAmount > totalClientServiceAmount) {
                    alert('Total paid amount cannot exceed the client service total amount.');
                    return;
                }

                // Handle logic for invoice generation here (e.g., send data to the server)
                alert('Invoice generated successfully!');
                $('#invoiceModal').modal('hide');
            });
        });
    </script>





    <script src="{{ asset('assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#data-table-default').DataTable({
                responsive: true,
                scrollX: true,
            });
        });
    </script>
@endsection
