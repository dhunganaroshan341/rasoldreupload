@extends('layouts.main')

@section('header-left-title', 'Clients')

@section('content')
    <div class="container mt-5">
        @include('components.create-new-button', [
            'route' => 'clients.create',
            'routeName' => '',
        ])
        <table id="clients-table" width="100%" class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>PAN</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientsWithServices as $client)
                    <tr class="action-row">
                        <td width="5%">{{ $client->id }}</td>
                        <td width="20%">
                            <a title="clientservices"
                                href="{{ route('ledger.show', ['ledger' => $client->id]) }}">{{ $client->name }}</a>
                        </td>
                        <td width="15%">{{ $client->address }}</td>
                        <td width="10%">{{ $client->pan_no ?? '-' }}</td>
                        <td width="15%">{{ $client->email }}</td>
                        <td width="10%">{{ $client->phone }}</td>
                        <td width="25%">
                            <button class="btn btn-sm toggle-services" data-target="#services-{{ $client->id }}">
                                <i class="fa fa-caret-down"></i>
                            </button>
                            <a title="Financial Summary"
                                href="{{ route('ledgerClientService.index', ['client_id' => $client->id]) }}">
                                <i class="fas fa-book"></i>
                            </a>
                            &nbsp
                            <a title="Financial Summary" href="{{ route('clients.show', ['client' => $client->id]) }}">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <tr id="services-{{ $client->id }}" class="collapse-row" style="display: none;">
                        <td colspan="7">
                            @if ($client->clientServices->isNotEmpty())
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Service Name</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Duration</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($client->clientServices as $service)
                                            <tr>
                                                <td>{{ $service->service->name }}</td>
                                                <td>{{ $service->service->description ?? 'N/A' }}</td>
                                                <td>{{ $service->service->price }}</td>
                                                <td>{{ $service->service->duration }}</td>
                                                <td>
                                                    <a href="{{ route('ClientServices.edit', ['client_service_id' => $service->id]) }}"
                                                        class="btn btn-sm "><i class="fas fa-pencil"></i></a>
                                                    <form
                                                        action="{{ route('ClientServices.destroy', ['client_service_id' => $service->id]) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm "><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No services available for this client.</p>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('script-items')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Services
            document.querySelectorAll('.toggle-services').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const targetRow = document.querySelector(targetId);
                    if (targetRow.style.display === 'none') {
                        targetRow.style.display = 'table-row';
                    } else {
                        targetRow.style.display = 'none';
                    }
                });
            });

            // Confirmation for delete
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(event) {
                    if (!confirm('Are you sure you want to delete this item?')) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>

    {{-- DataTables Scripts --}}
    <script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    {{-- DataTables Initialization --}}
    <script>
        $(document).ready(function() {
            $('#clients-table').DataTable({
                responsive: true,
                scrollX: true,
                columnDefs: [{
                    targets: 'no-order', // Target the class name
                    orderable: false,
                }],
                dom: 'Bfrtip', // Layout definition
                buttons: [{
                        extend: 'copy',
                        text: '<i class="fa fa-copy"></i> Copy',
                        titleAttr: 'Copy to clipboard'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        titleAttr: 'Export to Excel',
                        exportOptions: {
                            columns: ':visible:not(.exclude-column)' // Exclude columns with 'exclude-column' class
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-csv"></i> CSV',
                        titleAttr: 'Export to CSV',
                        exportOptions: {
                            columns: ':visible:not(.exclude-column)' // Exclude columns with 'exclude-column' class
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i> PDF',
                        titleAttr: 'Export to PDF',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':visible:not(.exclude-column)' // Exclude columns with 'exclude-column' class
                        },
                        customize: function(doc) {
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0]
                                .length + 1).join('*').split('');
                            doc.content[1].table.body.forEach(function(row) {
                                row.forEach(function(cell) {
                                    cell.styles = {
                                        ...cell.styles,
                                        alignment: 'center' // Center-align all text in the PDF
                                    };
                                });
                            });
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Print',
                        titleAttr: 'Print the table',
                        exportOptions: {
                            columns: ':visible:not(.exclude-column)' // Exclude columns with 'exclude-column' class
                        }
                    }
                ]
            });
        });
    </script>
@endpush

@push('style-items')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}"
        rel="stylesheet" />
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
@endpush
