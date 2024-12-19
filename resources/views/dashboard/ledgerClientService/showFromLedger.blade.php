@extends('layouts.main')

@push('style-items')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
@endpush
{{-- adding income expenses  creation button --}}
@section('header-left')
    {{-- <x-add-income-expense-link-button /> this one goes to another page form --}}
    <x-income-expense-modal-button />
@endsection
@section('header-right')
    <a title="{{ 'client ' . $client->name . ' ledger' }}" name="goto" id="goto" class="btn btn-primary mt-3 mr-4"
        href="{{ route('ledger.show', ['ledger' => $client->id]) }}" role="button">
        <i class="fa fa-book"></i>
        Client ledger</a>
@endsection
@section('content')
    @include('dashboard.invoicePayments.invoice_form_modal')

    {{-- to input income and expense here --}}
    <x-income-creation-modal :clientId="$client->id" :clientServices="$clientServices" />
    <x-expense-creation-modal :clientId="$client->id" :clientServices="$clientServices" />


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
                                            $client_service_name =
                                                $ledger->clientService->name ??
                                                $ledger->clientService->service->name .
                                                    '-' .
                                                    $ledger->clientService->client->name;
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



                        {{-- <button id="process-selected" class="btn btn-success mt-3">Process Selected Ledgers</button> --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Display Ledger Calculation Totals --}}
        <div class="mt-4">
            <div class="card shadow-lg p-4 bg-white rounded">
                <div class="card-body">
                    <h4 class="card-title text-center mb-4">Financial Summary</h4>
                    <h5 class="text-info text-center">{{ $clientServiceName }}</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Total Amount:</strong></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p>${{ number_format($clientService->amount, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Income:</strong></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p>${{ number_format($ledgerCalculationForClientService['clientServiceTotalIncome'], 2) }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Expenses:</strong></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p>${{ number_format($ledgerCalculationForClientService['clientServiceTotalExpense'], 2) }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Balance:</strong></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p>${{ number_format($ledgerCalculationForClientService['clientServiceBalance'], 2) }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Remaining:</strong></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p>${{ $clientService->amount - $ledgerCalculationForClientService['clientServiceTotalIncome'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('script-items')
    <script src="{{ asset('assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    {{-- DataTables Initialization --}}
    <script>
        $(document).ready(function() {
            $('#data-table-default').DataTable({
                responsive: true,
                scrollX: true,
                columnDefs: [{
                    targets: 'no-order', // Target the class name
                    orderable: false,
                }],
                dom: 'Bfrtip', // Layout definition
                buttons: [{
                        extend: 'copy',
                        text: '<i class="fa fa-copy"></i> Copy',
                        titleAttr: 'Copy to clipboard'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        titleAttr: 'Export to Excel',
                        exportOptions: {
                            columns: ':visible:not(.exclude-column)' // Exclude columns with 'exclude-column' class
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-csv"></i> CSV',
                        titleAttr: 'Export to CSV',
                        exportOptions: {
                            columns: ':visible:not(.exclude-column)' // Exclude columns with 'exclude-column' class
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i> PDF',
                        titleAttr: 'Export to PDF',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':visible:not(.exclude-column)' // Exclude columns with 'exclude-column' class
                        },
                        customize: function(doc) {
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                                .length + 1).join('*').split('');
                            doc.content[1].table.body.forEach(function(row) {
                                row.forEach(function(cell) {
                                    cell.styles = {
                                        ...cell.styles,
                                        alignment: 'center' // Center-align all text in the PDF
                                    };
                                });
                            });
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print',
                        titleAttr: 'Print the table',
                        exportOptions: {
                            columns: ':visible:not(.exclude-column)' // Exclude columns with 'exclude-column' class
                        }
                    }
                ]
            });
        });
    </script>

    {{-- invoice part --}}
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
@endpush
