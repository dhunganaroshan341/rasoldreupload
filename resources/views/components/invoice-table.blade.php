<div>
    <!-- You must be the change you wish to see in the world. - Mahatma Gandhi -->
</div>
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
                    <!-- View invoice button -->
                    @if (Route::currentRouteName() !== 'outstanding-invoices.clientService.show')
                        <a href="{{ route('outstanding-invoices.clientService.show', ['id' => $invoice->clientService->id]) }}"
                            class="view-invoice" data-id="{{ $invoice->id }}">
                            <i class="fas fa-eye"></i>
                        </a>
                    @endif


                    <!-- Show Edit/Delete only for the last invoice -->
                    @if ($loop->last)
                        <!-- Edit invoice button -->
                        <a href="{{ route('outstanding-invoices.edit', $invoice->id) }}" class="text-dark">
                            <i class="fas fa-pencil"></i>
                        </a>

                        <!-- Delete invoice button -->
                        <form id="delete-form-{{ $invoice->id }}"
                            action="{{ route('outstanding-invoices.destroy', $invoice->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="delete-btn text-dark" data-id="{{ $invoice->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>

</table>
