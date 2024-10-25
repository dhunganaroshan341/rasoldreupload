@extends('layouts.main')
@section('head_file')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
@endsection
{{-- adding income expenses  creation button --}}
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

    <a class=" btn btn-secondary mt-4 mr-5w" href="{{ route('ledgerClientService.index', ['client_id' => $client->id]) }}">
        <i class="fa fa-list"></i>
        Services List
    </a>
@endsection
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2>Client Ledger</h2>
            <!-- Button to Open the Income Create Modal -->

            <!-- Trigger modal on button click -->
            <button class="btn btn-primary" id="generateInvoice" data-toggle="modal" data-target="#invoiceModal">
                Generate Invoice &nbsp<i class="fa fa-download"></i>
            </button>

        </div>

        <x-session-success />
        {{-- invlude income modal component --}}
        <x-income-creation-modal :clientId="$client->id" />
        @include('dashboard.invoices.invoice_form_modal')
        <x-ledger-show :client="$client" :ledgers="$ledgers" :ledgerCalculationForClient="$ledgerCalculationForClient" :totalClientServiceAmount="$totalClientServiceAmount" />

    </div>
@endsection


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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

                                totalPaidAmount += itemAmount;
                                remainingAmount = response.total_client_service_amount -
                                    totalPaidAmount;
                                $('#invoiceDetailsList').append(`
                                <tr>
                                    <td>${item.client_service.name ? item.client_service.name : 'N/A'}</td>

                                    <td>$${itemAmount.toFixed(2)}</td>

                                </tr>
                            `);
                            });
                            //   <td>$${itemAmount.toFixed(2)}</td>
                            // <td>$${remainingAmount.toFixed(2)}</td>

                            // Update total amounts in the modal
                            $('#totalPaidAmount').text('$' + totalPaidAmount.toFixed(2));
                            $('#totalClientServiceAmount').text('$' + f
                                .toFixed(2));
                            $('#totalRemainingAmount').text('$', remainingAmount.toFixed(2));
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
