@extends('layouts.main')

@section('header-left')
    <a href="#" class="badge bg-sidebar  text-light float-right m-2" data-toggle="modal" data-target="#invoiceModal"
        role="button">Create Invoice</a>
@endsection

@section('content')
    {{-- Invoice Form Modal --}}
    @include('dashboard.outstandingInvoices.invoice_form_modal')


    {{-- Invoice Table --}}
    <table id="data-table-default" width="100%" class="table table-striped table-bordered align-middle ">
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
            @foreach ($taxes as $tax)
                <tr>
                    <td>{{ $tax->id ?? 'N/A' }}</td>
                    <td>{{ $tax->name ?? 'N/A' }}</td>
                    <td>{{ number_format($tax->amount ?? 0, 2) }}</td>
                    <td>{{ number_format($tax->percentage ?? 0, 2) }}</td>
                    <td>{{ number_format($tax->description ?? 0, 2) }}</td>
                    <td>{{ $tax->created_at ? $tax->created_at->format('d M Y') : 'No Date' }}</td>
                    <td>{{ $tax->updated_at ? $tax->updated_at->format('d M Y') : 'No Date' }}</td>
                    <td>
                    <td>
                        <span
                            class="badge {{ $invoice->status === 'paid' ? 'badge-success' : ($invoice->status === 'overdue' ? 'badge-danger' : 'badge-warning') }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td>{{ optional($invoice->clientService)->name ?? 'No Client' }}</td>
                    <td>
                        <a href="{{ route('outstanding-invoices.show', $invoice->id) }}"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('outstanding-invoices.edit', $invoice->id) }}" class="text-warning"><i
                                class="fas fa-pencil"></i></a>
                        <form id="delete-form-{{ $invoice->id }}"
                            action="{{ route('outstanding-invoices.destroy', $invoice->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="delete-btn text-danger" data-id="{{ $invoice->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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
@endpush
