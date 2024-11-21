@extends('layouts.main')

@section('header_file')
@endsection

@section('header-left')
    <a href="#" class="btn btn-primary float-right m-2" data-toggle="modal" data-target="#invoiceModal"
        role="button">Create Invoice</a>
@endsection

@section('content')
    @include('dashboard.outStandingInvoices.invoice_form_modal') <!-- Include the modal form -->
    <table id="data-table-default" width="100%" class="table table-striped table-bordered align-middle text-nowrap">
        <thead class="bg-light">
            <tr>
                <th>ID</th>
                <th>Bill Number</th>
                <th>Total Amount</th>
                <th>Paid Amount</th>
                <th>Remaining Amount</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Client</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $invoice)
                <tr class="hover-item position-relative">
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->bill_number }}</td>
                    <td>{{ number_format($invoice->total_amount, 2) }}</td>
                    <td>{{ number_format($invoice->paid_amount, 2) }}</td>
                    <td>{{ number_format($invoice->remaining_amount, 2) }}</td>
                    <td>{{ $invoice->due_date->format('d M Y') }}</td>
                    <td>
                        @if ($invoice->status == 'paid')
                            <span class="badge badge-success">Paid</span>
                        @elseif ($invoice->status == 'unpaid')
                            <span class="badge badge-danger">Unpaid</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                    <td>{{ $invoice->client->name }}</td>
                    <td>
                        <a href="{{ route('invoice.show', $invoice->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('invoice.edit', $invoice->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('invoice.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('footer_file')
    {{-- <script src="{{ asset('/assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script> --}}
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#data-table-default').DataTable({
                responsive: true
            });

            // Handle the Edit Invoice button click
            $(document).on('click', '.editInvoiceBtn', function() {
                var invoice = $(this).data(
                    'invoice'); // Get the invoice data from the button's data attribute

                // Set modal title and button text for editing
                $('#invoiceModalLabel').text('Edit Invoice');
                $('#submitBtn').text('Update Invoice');

                // Populate the form with the existing invoice data
                $('#bill_number').val(invoice.bill_number);
                $('#total_amount').val(invoice.total_amount);
                $('#paid_amount').val(invoice.paid_amount);
                $('#remaining_amount').val(invoice.remaining_amount);
                $('#due_date').val(invoice.due_date);
                $('#status').val(invoice.status);
                $('#client_id').val(invoice.client_id);
                $('#invoice_id').val(invoice.id); // Set hidden input with the invoice ID

                // Change form method to PUT (for updating)
                $('#invoiceForm').attr('action', '{{ route('outstanding-invoices.update', '') }}/' +
                    invoice.id);
                $('input[name="_method"]').val('PUT'); // Ensure the correct method is used
            });

            // Handle form submission with AJAX
            $('#invoiceForm').on('submit', function(e) {
                e.preventDefault();
                var formData = $(this).serialize(); // Collect form data

                $.ajax({
                    url: $(this).attr('action'), // Use dynamic URL from the form's action attribute
                    method: 'POST', // Method can still be POST even when updating, because _method handles it
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            // Close modal and reload table
                            $('#invoiceModal').modal('hide');
                            alert(response.message || 'Invoice updated successfully!');
                            location.reload(); // Reload the page to show the updated invoice
                        } else {
                            alert('Failed to update invoice');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
