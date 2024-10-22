@extends('layouts.main')

@section('head_file')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <h2>Clients</h2>
            <a href="{{ route('clients.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        </div>
        @if (session()->get('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        <table id="data-table-buttons" width="100%" class="table table-bordered align-middle">
            <thead class="bg-light">
                <tr>
                    <th colspan="5" class="text-center bg-success text-white">Income</th>
                    <th colspan="5" class="text-center bg-dark text-white">Expense</th>
                </tr>
                <tr>
                    <!-- Income Table Headers -->
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Source</th>
                    <th>Medium</th>
                    <th>remarks</th>
                    <!-- Expense Table Headers -->
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Source</th>
                    <th>Medium</th>
                    <th>remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($paginatedTransactions as $transaction)
                    <tr>
                        <!-- Common Transaction Date Column -->
                        <td>{{ $transaction['transaction_date'] }}</td>

                        <!-- Income Columns -->
                        <td>{{ $transaction['income_amount'] ?? '' }}</td>
                        <td class="source">
                            @if (!empty($transaction['income_source']))
                                <span class="bg-success text-white">
                                    @php
                                        $client_id = null;

                                        if (
                                            isset($transaction['client_service']) &&
                                            $transaction['client_service'] != null
                                        ) {
                                            $client_id =
                                                \App\Models\ClientService::find($transaction['client_service'])->client
                                                    ->id ?? null;
                                        }
                                    @endphp

                                    <a href="{{ $client_id ? route('ledger.show', $client_id) : '#' }}">
                                        {{ \App\Models\ClientService::find($transaction['client_service'])->client->name ?? 'no specific name' }}
                                    </a>
                                </span> --
                                <span class="bg-white text-success">
                                    {{ \App\Models\ClientService::find($transaction['client_service'])->service->name ?? 'no specific service' }}
                                </span>

                                <a href="{{ $transaction['client_service'] ? route('incomes.edit', $transaction['client_service']) : '#' }}"
                                    class="btn btn-link text-dark">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            @endif
                        </td>
                        <td>{{ $transaction['medium'] ?? '' }}</td>
                        <td>{{ $transaction['remarks'] ?? '' }}</td>

                        <!-- Expense Columns -->
                        <td>{{ $transaction['expense_amount'] ?? '' }}</td>
                        <td>{{ $transaction['expense_source'] ?? '' }}</td>
                        <td class="hoverthis">
                            @if (!empty($transaction['expense_source']))
                                {{ $transaction['expense_source'] }}
                                <a href="{{ $transaction['expense_id'] ? route('expenses.edit', $transaction['expense_id']) : '#' }}"
                                    class="btn btn-link text-dark">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            @endif
                        </td>
                        <td>{{ $transaction['medium'] ?? '' }}</td>
                        <td>{{ $transaction['remarks'] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    @endsection
    @section('styles')
        <style>
            .action-row {
                position: relative;
            }

            .action-buttons {
                display: none;
                position: absolute;
                right: 0;
                top: 0;
                background: rgba(41, 38, 38, 0.3);
                padding: 1px;
                box-shadow: 0 2px 5px rgba(211, 204, 204, 0.2);
                transition: opacity 0.5s ease, transform 0.5s ease;
                white-space: nowrap;
                transform: translateY(-100%);
                opacity: 0;
                z-index: 1000;
            }

            .action-row:hover .action-buttons {
                display: block;
                transform: translateY(0);
                opacity: 1;
            }

            .action-buttons a,
            .action-buttons button {
                display: inline-block;
                margin-right: 10px;
            }

            .action-buttons a {
                text-decoration: none;
                color: inherit;
            }

            .action-buttons button {
                border: none;
                background: none;
                cursor: pointer;
            }

            .delete-form {
                display: inline-block;
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
                $('#data-table-buttons').DataTable({
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteForms = document.querySelectorAll('.delete-form');

                deleteForms.forEach(form => {
                    form.addEventListener('submit', function(event) {
                        const confirmed = confirm('Are you sure you want to delete this client?');
                        if (!confirmed) {
                            event.preventDefault(); // Prevent form submission if not confirmed
                        }
                    });
                });
            });
        </script>
    @endsection
