@extends('layouts.main')

@section('header-left')
    <a href="#" class="badge bg-sidebar text-light float-right m-2" data-toggle="modal" data-target="#invoiceModal"
        role="button">Create Invoice</a>
@endsection
@section('header-right')
    {{-- <a title="" href="{{ route('outstanding-invoices.client.show', ['id', $invoices->clientService->client->id]) }}"
        class="text-sidebarbg text-light float-right mt-4 mr-3" role="button">Client Invoices</a> --}}
    <a href="{{ route('outstanding-invoices.index') }}" class="badge badge-primary mt-4 mr-4">invoices</a>
@endsection

@section('content')
    {{-- Invoice Form Modal --}}
    @include('dashboard.outstandingInvoices.invoice_form_modal')

    {{-- Invoice Table --}}
    @include('components.invoice-table')

    {{-- Modal Popup for Viewing Invoice Details --}}
    @include('dashboard.outstandingInvoices.modal_popup')
@endsection


@push('script-items')
    <script src="{{ asset('/assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#data-table-default').DataTable({
                responsive: true
            });
        });

        // AJAX Delete
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();

            // Get the invoice ID from the button's data-id attribute
            let invoiceId = $(this).data('id');

            // Get the form associated with this button
            let form = $(`#delete-form-${invoiceId}`);
            let url = form.attr('action');

            // Show a confirmation dialog
            if (!confirm('Are you sure you want to delete this invoice?')) {
                return;
            }

            // Send the AJAX DELETE request
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE',
                },
                success: function(response) {
                    // Handle the success response
                    alert(response.message || 'Invoice deleted successfully!');

                    // Remove the row (assuming the form is inside a table row)
                    form.closest('tr').remove();
                },
                error: function(xhr, status, error) {
                    // Handle the error response
                    alert(xhr.responseJSON?.message || 'An error occurred while deleting the invoice.');
                }
            });
        });
    </script>

    {{-- pushing script for invoices modal popup --}}
    <script>
        $(document).ready(function() {
            // When the view button is clicked
            $(document).on('click', '.view-invoice', function() {
                var invoiceId = $(this).data('id'); // Get the invoice ID from the clicked link

                // Make the AJAX request to the API
                $.ajax({
                    url: '/api/outstanding-invoices/' + invoiceId, // Adjust API URL
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            // Populate the modal with data
                            var invoice = response.data; // The main invoice data
                            var clientService = invoice
                                .client_service; // Nested client service object

                            // Access the name of the client service
                            var clientServiceName = clientService ? clientService.name :
                                'No Client Service';

                            var modalContent = `
    <div class="row">
        <div class="col-md-6">
            <p><strong>Invoice ID:</strong> ${invoice.id}</p>
            <p><strong>Client Service Name:</strong> ${clientServiceName}</p>
            <p><strong>Client Service ID:</strong> ${invoice.client_service_id}</p>
            <p><strong>Total Amount:</strong> ${invoice.total_amount}</p>
            <p><strong>Previous Remaining Amount:</strong> ${invoice.prev_remaining_amount}</p>
            <p><strong>All Total:</strong> ${invoice.all_total}</p>
        </div>
        <div class="col-md-6">
            <p><strong>Paid Amount:</strong> ${invoice.paid_amount}</p>
            <p><strong>Remaining Amount:</strong> ${invoice.remaining_amount ? invoice.remaining_amount : 'N/A'}</p>
            <p><strong>Discount Amount:</strong> ${invoice.discount_amount}</p>
            <p><strong>Discount Percentage:</strong> ${invoice.discount_percentage}</p>
            <p><strong>Due Date:</strong> ${invoice.due_date}</p>
            <p><strong>Status:</strong> ${invoice.status}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Remarks:</strong> ${invoice.remarks ? invoice.remarks : 'N/A'}</p>
            <p><strong>Created At:</strong> ${invoice.created_at}</p>
        </div>
        <div class="col-md-6">
            <p><strong>Updated At:</strong> ${invoice.updated_at}</p>
        </div>
    </div>
`;


                            // Insert the content into the modal body
                            $('#invoiceDetails').html(modalContent);

                            // Show the modal
                            $('.bd-example-modal-lg').modal('show');
                        } else {
                            alert('Error fetching invoice details');
                        }
                    },
                    error: function() {
                        alert('An error occurred while fetching invoice details');
                    }
                });
            });
        });
    </script>
@endpush
