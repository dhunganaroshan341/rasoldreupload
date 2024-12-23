@extends('layouts.main')
@section('header-left-title', 'Client-Info')
@section('header_file')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <style>
        .description-column {
            width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endsection
@section('header-right')
    <a href="{{ route('clients.show', ['client' => $client->id]) }}" class="badge bg-sidebar mt-4 mr-3">
        card-view
    </a>

@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Display the client's name at the top -->
                <h4>Services Used</h4>
                <h2><span class="text-success">{{ $client->name }}</span></h2>
                <div class="float-right mb-4"><a
                        title  = "Edit this  clientService-{{ $client->name }}"class="btn btn-success"
                        href="{{ route('clients.edit', ['client' => $client->id]) }}"> <i class="fas fa-pencil"></i></a>
                </div>

                <table id="data-table-default" width="100%" class="table table-striped table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Service Category</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Duration</th>
                            <th>Email Service</th>
                            <th>Hosting Service</th>
                            <th>Advance paid</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($client->clientServices as $service)
                            <tr>
                                <td>{{ $service->id }}</td>
                                <td>{{ $service->service->name . '-' . $client->name }}</td>
                                <!-- Assuming there's a related `Service` model -->
                                <td>{!! $service->name ?? '<small>specific-name/if required</small>' !!}</td>
                                <td>{{ $service->amount ?? $service->service->price }}</td>
                                <td>{{ $service->duration ?? $service->service->duration }} -
                                    {{ $service->duration_type ?? $service->service->duration_type }}</td>
                                <td>{{ $service->email_service ?? $client->email_service }}</td>
                                <td>{{ $service->hosting_service ?? $client->hosting_service }}</td>
                                {{-- advance paid --}}
                                <td>
                                    {{ $service->advance_paid }}
                                </td>
                                <td>
                                    <a title="show ledger"
                                        href="{{ route('ledger-client-service.show', ['ledger_client_service' => $service->id]) }}"
                                        class="btn btn-link">
                                        <i class="fa fa-list"></i>
                                    </a>
                                    <a href="{{ route('ClientServices.edit', ['client_service_id' => $service->id]) }}"
                                        class="btn btn-link">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form
                                        action="{{ route('ClientServices.destroy', ['client_service_id' => $service->id]) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
