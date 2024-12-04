@extends('layouts.main')

@section('header-left')
    <a href="#" class="btn btn-primary float-right m-2" data-toggle="modal" data-target="#invoiceModal"
        role="button">Create Invoice</a>
@endsection

@section('content')
    {{-- Invoice Form Modal --}}
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel">Create Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="invoiceForm" method="POST" action="/api/outstanding-invoices">
                        @csrf
                        <div class="mb-3">
                            <label for="client_id" class="form-label">Client</label>
                            <select class="form-select" id="client_id" name="client_id" required>
                                <option value="">Select Client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="service_id" class="form-label">Client Service</label>
                            <select class="form-select" id="service_id" name="client_service_id" required>
                                <option value="">Select Service</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="total_amount" class="form-label">Invoice Amount</label>
                            <input type="number" class="form-control" id="total_amount" name="total_amount" required
                                min="0">
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Create Invoice</button>
                    </form>
                    <div id="message"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Invoice Table --}}
    <table id="data-table-default" class="table table-striped table-bordered">
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
                <tr>
                    <td>{{ $invoice->id ?? 'N/A' }}</td>
                    <td>{{ $invoice->bill_number ?? 'N/A' }}</td>
                    <td>{{ number_format($invoice->total_amount ?? 0, 2) }}</td>
                    <td>{{ number_format($invoice->paid_amount ?? 0, 2) }}</td>
                    <td>{{ number_format($invoice->remaining_amount ?? 0, 2) }}</td>
                    <td>{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : 'No Due Date' }}</td>
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
                        <form action="{{ route('outstanding-invoices.destroy', $invoice->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const $serviceSelect = $('#service_id');
            const $dueDateInput = $('#due_date');
            const $totalAmountInput = $('#total_amount');

            // Populate services based on selected client
            $('#client_id').change(function() {
                const clientId = $(this).val();
                resetFormFields();
                if (clientId) {
                    $.getJSON(`/api/clients/${clientId}`, function(data) {
                        if (data.success) {
                            $serviceSelect.append(data.client.client_services.map(service =>
                                `<option value="${service.id}">${service.name}</option>`
                            ));
                        } else {
                            showErrorOption($serviceSelect, 'No services found');
                        }
                    }).fail(() => showErrorOption($serviceSelect, 'Error loading services'));
                }
            });

            // Update invoice details based on service selection
            $serviceSelect.change(function() {
                const serviceId = $(this).val();
                if (serviceId) {
                    $.getJSON(`/api/services/${serviceId}/latest-invoice`, function(data) {
                        if (data.success) {
                            $dueDateInput.val(data.dueDate || '');
                            $totalAmountInput.val(data.payableAmount || 0);
                        }
                    }).fail(() => console.error('Error fetching service details'));
                }
            });

            // Reset form on modal close
            $('#invoiceModal').on('hidden.bs.modal', resetFormFields);

            function resetFormFields() {
                $serviceSelect.html('<option value="">Select Service</option>');
                $dueDateInput.val('');
                $totalAmountInput.val('');
            }

            function showErrorOption($select, message) {
                $select.append(`<option disabled>${message}</option>`);
            }
        });
    </script>
@endpush
