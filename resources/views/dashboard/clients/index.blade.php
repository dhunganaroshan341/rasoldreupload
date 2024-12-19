@extends('layouts.main')

@section('header-left-title', 'Clients')

@section('content')
    <div class="container mt-5">
        @include('components.create-new-button', [
            'route' => 'clients.create',
            'routeName' => '',
        ])
        <table id="data-table-default" width="100%" class="table table-bordered align-middle">
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
                    <tr>
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
                            <a title="Financial Summary"
                                href="{{ route('ledgerClientService.index', ['client_id' => $client->id]) }}">
                                <i class="fas fa-book"></i>
                            </a>
                            &nbsp;
                            <a title="View Client" href="{{ route('clients.show', ['client' => $client->id]) }}">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('script-items')
    {{-- DataTables Scripts --}}
    <script src="{{ asset('/assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

    {{-- DataTables Initialization --}}
    <script>
        $(document).ready(function() {
            // Initialize main table
            var mainTable = $('#data-table-default').DataTable({
                responsive: true
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
