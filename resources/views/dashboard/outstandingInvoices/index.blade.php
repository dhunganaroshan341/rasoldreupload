@extends('layouts.main')

@section('header-left')
    <a href="#" class="btn btn-primary float-right m-2" data-toggle="modal" data-target="#invoiceModal"
        role="button">Create Invoice</a>
@endsection

@section('content')
    {{-- Invoice Form Modal --}}
    @include('dashboard.outstandingInvoices.invoice_form_modal')


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

@section('footer_file')
@endsection
