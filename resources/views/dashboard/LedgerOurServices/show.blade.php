@extends('layouts.main')

@section('head_file')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
@endsection

@section('content')
    @include('dashboard.invoices.invoice_form_modal')

    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2>Ledger</h2>
            <button class="btn btn-primary" data-toggle="modal" data-target="#ledgerModal">Generate Invoice &nbsp<i
                    class="fa fa-download"></i></button>
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
                        Ledger Entries for {{ $client->name }}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data-table-default" class="table table-striped table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"> Select</th>
                                        <th>Date</th>
                                        <th>Source</th>
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
                                        @php
                                            $balance +=
                                                $ledger->transaction_type == 'income'
                                                    ? $ledger->amount
                                                    : -$ledger->amount;
                                            $remainingAmount = $ledger->client_service->id
                                                ? $remainingAmounts[$ledger->client_service->id] ?? 'N/A'
                                                : 'N/A';
                                            $client_service_name =
                                                $ledger->client_service->name ??
                                                ($ledger->client_service->client->name .
                                                    '-' .
                                                    $ledger->client_service->service->name ??
                                                    'N/A');
                                        @endphp
                                        <tr>
                                            <td><input type="checkbox" class="ledger-checkbox" value="{{ $ledger->id }}">
                                            </td>
                                            <td>{{ $ledger->transaction_date }}</td>
                                            <td><a
                                                    href="{{ route('ClientServices.edit', ['client_service_id' => $ledger->client_service->id]) }}">{{ $client_service_name }}</a>
                                            </td>
                                            <td>{{ $ledger->medium }}</td>
                                            <td>{{ $ledger->transaction_type == 'expense' ? '$' . number_format($ledger->amount, 2) : '--' }}
                                            </td>
                                            <td>{{ $ledger->transaction_type == 'income' ? '$' . number_format($ledger->amount, 2) : '--' }}
                                            </td>
                                            <td>${{ number_format($balance, 2) }}</td>
                                            <td>{{ $ledger->remaining_amount ? "$client_service_name - Remaining: $" . number_format($remainingAmount, 2) : '' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button id="process-selected" class="btn btn-success mt-3">Process Selected Ledgers</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#checkAll').click(function() {
                $('.ledger-checkbox').prop('checked', this.checked);
            });

            $('#process-selected').click(function() {
                var selectedLedgers = $('.ledger-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
                if (selectedLedgers.length) {
                    console.log('Selected Ledgers:', selectedLedgers);
                } else {
                    alert('Please select at least one ledger entry to process.');
                }
            });
        });
    </script>
@endsection

@section('footer_file')
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
@endsection
