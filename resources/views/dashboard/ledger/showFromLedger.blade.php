@extends('layouts.main')

@section('head_file')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
@endsection

{{-- Adding income expenses creation button --}}
@section('header-left')
    <x-income-expense-modal-button />
@endsection

@section('header-right')
    <style>
        .btn-custom {
            font-size: 0.75rem;
            /* Adjusts the font size */
            padding: 0.25rem 0.5rem;
            /* Adjusts the padding */
            line-height: 1.2;
            /* Adjusts line height */
        }
    </style>

    <a class="btn btn-secondary mt-4 mr-5" href="{{ route('ledgerClientService.index', ['client_id' => $client->id]) }}">
        <i class="fa fa-list"></i> Services List
    </a>
@endsection

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2>Client Ledger</h2>

            <!-- Button to Open the Income Create Modal -->
            <button class="btn btn-primary" id="generateInvoice" data-toggle="modal" data-target="#invoiceModal">
                Generate Invoice &nbsp;<i class="fa fa-download"></i>
            </button>
        </div>

        <x-session-success />

        {{-- Include income modal component --}}
        <!-- Income Modal -->
        @include('component.income_modal_new')

        <!-- Expense Modal -->
        {{-- @include('component.expense_modal') --}}
        @include('dashboard.expenses.expense_modal')

        @include('dashboard.invoicePayments.invoice_form_modal')

        <x-ledger-show :client="$client" :ledgers="$ledgers" :ledgerCalculationForClient="$ledgerCalculationForClient" :totalClientServiceAmount="$totalClientServiceAmount" />
        {{-- Display Ledger Calculation Totals --}}
        <div class="mt-4">
            <h4>Ledger Summary
                <br>
                <h3 class="text-info"> {{ $client->name }}</h3>
            </h4>
            <p class="mt-2"><strong>Total Amount :</strong>
                ${{ number_format($ledgerCalculationForClient['clientTotalAmount'], 2) }}

            </p>
            <p><strong>Total Income:</strong>
                ${{ number_format($ledgerCalculationForClient['clientTotalIncome'], 2) }}</p>
            <p><strong>Total Expenses:</strong>
                ${{ number_format($ledgerCalculationForClient['clientTotalExpense'], 2) }}
            </p>
            <p><strong>Balance:</strong>
                ${{ number_format($ledgerCalculationForClient['clientBalance'], 2) }}</p>

            <p><strong>Total Remaining:</strong>
                {{ $ledgerCalculationForClient['clientTotalRemaining'] }}
            </p>
        </div>

        {{-- <button id="process-selected" class="btn btn-success mt-3">Process Selected Ledgers</button> --}}
    </div>
    @include('components.polar-chart')
    </div>
@endsection



@push('script-items')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
                                var itemAmount = parseFloat(item.amount) || 0;

                                // Update total paid amount
                                totalPaidAmount += itemAmount;

                                $('#invoiceDetailsList').append(`
                                <tr>
                                    <td>${item.client_service.name ? item.client_service.name : 'N/A'}</td>
                                    <td>$${itemAmount.toFixed(2)}</td>
                                </tr>
                            `);
                            });

                            // Calculate remaining amount after the loop
                            var remainingAmount = totalClientServiceAmount - totalPaidAmount;

                            // Update total amounts in the modal
                            $('#totalPaidAmount').text('$' + totalPaidAmount.toFixed(2));
                            $('#totalClientServiceAmount').text('$' + totalClientServiceAmount
                                .toFixed(2));
                            $('#totalRemainingAmount').text('$' + remainingAmount.toFixed(2));

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

                // Handle logic for invoice generation here
                alert('Invoice generated successfully!');
                $('#invoiceModal').modal('hide');
            });
        });
    </script>


    {{-- DataTables Scripts --}}
    <script src="{{ asset('assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

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
@endpush
