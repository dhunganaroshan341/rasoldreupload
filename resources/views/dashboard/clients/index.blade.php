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
            'route' => 'clients.create',
            'routeName' => ' clients',
        ])
        <table id="data-table-buttons" width="100%" class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Service Used</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientsWithServices as $client)
                    <tr class="action-row">
                        <td>{{ $client->id }}</td>
                        <td>
                            <a title="clientservices"
                                href="{{ route('ledger.show', ['ledger' => $client->id]) }}">{{ $client->name }}</a>
                            <br>
                            @if (!empty($client->pan_no))
                                <small class="text-success">PAN NO: {{ $client->pan_no }}</small>
                            @endif
                        </td>

                        <td style="color: rgb(188, 174, 82); box-shadow: 2px 2px 2px 2px, aliceblue">
                            @if ($client->clientServices->isNotEmpty())
                                @foreach ($client->clientServices as $service)
                                    <span>
                                        <a title="edit-{{ $service->name ?? $service->client->name . '-' . $service->service->name }}"
                                            href="{{ route('ClientServices.edit', ['client_service_id' => $service->id]) }}">
                                            {{ $service->name != null ? $service->name : $service->service->name }}
                                        </a>
                                        <span class="tooltip-text">Edit this service: {{ $service->name }}</span>
                                        @if (!$loop->last)
                                            <span class="text-primary">,</span> &nbsp;
                                        @endif
                                    </span>
                                @endforeach
                            @else
                                <span>No services available</span>
                            @endif
                        </td>
                        <td style="color: rgb(92, 75, 75)">{{ $client->address }}</td>
                        <td class="text-info">{{ $client->email }}</td>
                        <td class="text-success">{{ $client->phone }}</td>
                        {{-- <td class="action-buttons">
                            <a href="{{ route('ClientServices.index', ['client_id' => $client->id]) }}"
                                class="btn btn-info btn-sm">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm delete-btn">
                                    <i class="fa fa-trash text-dark"></i>
                                </button>
                            </form>
                        </td> --}}
                        <td>
                            {{-- testing action table button component --}}
                            <x-action-table-buttons :parameters="[
                                'indexRoute' => 'ClientServices.index',
                                'indexRouteId' => $client->id,
                                'indexRouteIdVariable' => 'client_id',

                                'showRoute' => 'clients.show',
                                'showRouteId' => $client->id,
                                'showRouteIdVariable' => 'client',

                                'editRoute' => 'clients.edit',
                                'editRouteId' => $client->id,
                                'editRouteIdVariable' => 'client',

                                'destroyRoute' => 'clients.destroy',
                                'destroyRouteId' => $client->id,
                                'destroyRouteIdVariable' => 'client',
                            ]" />


                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
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
                        className: 'btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)' // Excludes the last column (Actions)
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)' // Excludes the last column (Actions)
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)' // Excludes the last column (Actions)
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)' // Excludes the last column (Actions)
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn-sm',
                        exportOptions: {
                            columns: ':not(:last-child)' // Excludes the last column (Actions)
                        }
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
