@extends('layouts.main')

@section('header_file')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
@endsection
{{-- @section('title', 'invoices') --}}

@section('content')
    <table id="data-table-default" width="100%" class="table table-striped table-bordered align-middle text-nowrap">
        <thead class="bg-light">
            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Source</th>
                {{-- <th>Medium</th>
                <th>Type</th> --}}
                <th data-orderable="false">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoices as $invoice)
                {{-- @php
                    $transaction = (object) $transactionArray;
                    // Determine the client service based on transaction type
                    if ($transaction->type === 'income') {
                        $clientService = $clientServices['income'][$transaction->client_service] ?? null;
                    } elseif ($transaction->type === 'expense') {
                        $clientService = $clientServices['expense'][$transaction->client_service] ?? null;
                    } else {
                        $clientService = null;
                    }
                @endphp --}}
                <tr class="hover-item position-relative">
                    <td>{{ $invoice->created_at }}</td>
                    <td>{{ $invoice->amount }}</td>
                    <td>{{ $invoice->client ? $invoice->client->name : 'N/A' }}</td> <!-- Displaying client service name -->
                    {{-- <td>{{ $transaction->medium }}</td>
                    <td>{{ ucfirst($transaction->type) }}</td> --}}
                    <td class="position-relative">
                        <a href="{{ route('invoice.download', ['id' => $invoice->id]) }}" class="btn btn-link text-dark">
                            <i class="fa fa-download"></i>
                        </a>
                        {{-- <a href="{{ route('invoices.show', ['id' => $transaction->id]) }}" class="btn btn-link text-dark">
                            <i class="fa fa-eye"></i>
                        </a> --}}
                        <a href="{{ route('invoices.edit', ['id' => $invoice->id]) }}" class="btn btn-link text-dark">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>
@endsection

@section('footer_file')
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
    </script>
@endsection
