@extends('layouts.main')

@section('head_file')
    <!-- DataTables CSS -->
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-colreorder-bs5/css/colReorder.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-keytable-bs5/css/keyTable.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-rowreorder-bs5/css/rowReorder.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-select-bs5/css/select.bootstrap5.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <h2>Transactions</h2>
            <!-- Add any button here if needed -->
        </div>
        @if (session()->get('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        <table id="data-table-combine" width="100%" class="table table-striped table-bordered align-middle text-nowrap">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Medium</th>
                    <th>Remarks</th>
                    <th>Type</th>
                    <th>Client/Service</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction['transaction_date'] }}</td>
                        <td>{{ $transaction['amount'] }}</td>
                        <td>{{ $transaction['medium'] }}</td>
                        <td>{{ $transaction['remarks'] }}</td>
                        <td>{{ ucfirst($transaction['type']) }}</td>
                        <td>
                            @php
                                $clientService = $clientServices->firstWhere(
                                    'client_id',
                                    $transaction['client_service'],
                                );
                            @endphp
                            @if ($clientService)
                                {{ $clientService->client->name }} - {{ $clientService->service->name }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if ($transaction['type'] === 'income')
                                <a href="{{ route('incomes.edit', ['income' => $transaction['id']]) }}"
                                    class="btn btn-link text-dark">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            @else
                                <a href="{{ route('expenses.edit', ['expense' => $transaction['id']]) }}"
                                    class="btn btn-link text-dark">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('footer_file')
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="{{ asset('assets/plugins/jquery/dist/jquery.min.js') }}"></script>
    <!-- DataTables JavaScript -->
    <script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-colreorder/js/dataTables.colReorder.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-colreorder-bs5/js/colReorder.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-keytable-bs5/js/keyTable.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-rowreorder/js/dataTables.rowReorder.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-rowreorder-bs5/js/rowReorder.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-select/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-select-bs5/js/select.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/dist/jszip.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var options = {
                dom: '<"row mb-3"<"col-lg-8 d-lg-block"<"d-flex d-lg-inline-flex justify-content-center mb-md-2 mb-lg-0 me-0 me-md-3"l><"d-flex d-lg-inline-flex justify-content-center mb-md-2 mb-lg-0 "B>><"col-lg-4 d-flex d-lg-block justify-content-center"fr>>t<"row mt-3"<"col-md-auto me-auto"i><"col-md-auto ms-auto"p>>',
                buttons: [{
                        extend: 'copy',
                        className: 'btn-sm'
                    },
                    {
                        extend: 'csv',
                        className: 'btn-sm'
                    },
                    {
                        extend: 'excel',
                        className: 'btn-sm'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn-sm'
                    },
                    {
                        extend: 'print',
                        className: 'btn-sm'
                    }
                ],
                responsive: true,
                colReorder: true,
                keys: true,
                rowReorder: true,
                select: true,
                columnDefs: [{
                        targets: [6],
                        orderable: false
                    } // Disable ordering for the Edit column
                ]
            };

            if ($(window).width() <= 767) {
                options.rowReorder = false;
                options.colReorder = false;
            }

            $('#data-table-combine').DataTable(options);
        });
    </script>
@endsection
