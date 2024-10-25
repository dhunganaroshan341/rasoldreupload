@extends('layouts.main')

@section('header_file')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="container mt-5">
        @include('components.create-new-button', [
            'route' => 'incomes.create',
            'routeName' => 'create Income',
        ])

        @if (session()->get('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif

        <table id="data-table-default" width="100%" class="table table-striped table-bordered align-middle text-nowrap">
            <thead class="bg-light">
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th class="description-column">Description</th>
                    <th>Date</th>
                    <th>Client Service ID</th>
                    <th>Summary</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalIncome = 0;
                    $totalExpense = 0;
                    $currentId = 1; // Initialize ID counter
                @endphp

                @foreach ($ledgers as $entry)
                    <tr class="hover-item position-relative">
                        <td>{{ $currentId++ }}</td>
                        <td>{{ ucfirst($entry['type']) }}</td>
                        <td>{{ number_format($entry['amount'], 2) }}</td>
                        <td>{{ $entry['description'] ?? 'N/A' }}</td>
                        <td>{{ $entry['date'] ?? 'N/A' }}</td>
                        <td>{{ $entry['client_service_id'] }}</td>
                        <td>{{ $entry['summary'] ?? '' }}</td>
                    </tr>

                    @if ($entry['type'] === 'income')
                        @php $totalIncome += $entry['amount']; @endphp
                    @elseif ($entry['type'] === 'expense')
                        @php $totalExpense += $entry['amount']; @endphp
                    @endif
                @endforeach

                <!-- Summary Row -->
                <tr class="summary-row">
                    <td colspan="2">Total Income</td>
                    <td>{{ number_format($totalIncome, 2) }}</td>
                    <td colspan="4">Total Expense: {{ number_format($totalExpense, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@section('styles')
    <style>
        .summary-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }

        .hover-item {
            transition: background-color 0.3s ease;
        }

        .hover-item:hover {
            background-color: #f1f1f1;
        }
    </style>
@endsection

@section('footer_file')
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
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
            $('#data-table-default').DataTable({
                responsive: true,
                dom: '<"row mb-3"<"col-md-6"B><"col-md-6"fr>>t<"row mt-3"<"col-md-auto me-md-auto"i><"col-md-auto ms-md-auto"p>>',
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
            });
        });
    </script>
@endsection
