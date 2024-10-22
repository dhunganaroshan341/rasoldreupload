@extends('layouts.main')

@section('header_file')
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" />
    {{-- <link href="{{ asset('assets/css/default/app.min.css') }}" rel="stylesheet" /> --}}
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
@endsection

@section('title', 'Ledgers')

@section('content')
    <div class="container mt-5">
        {{-- session success error and success --}}
        <x-session-success />


        <div class="panel panel-inverse panel-with-tabs">
            <div class="panel-heading p-0">
                <div class="tab-overflow">
                    <ul class="nav nav-tabs nav-tabs-inverse">
                        <li class="nav-item prev-button">
                            <a href="javascript:;" data-click="prev-tab" class="nav-link text-primary"><i
                                    class="fa fa-arrow-left"></i></a>
                        </li>
                        <li class="nav-item">
                            <a href="#nav-tab-clients" data-bs-toggle="tab" class="nav-link active">Clients</a>
                        </li>
                        <li class="nav-item ">
                            <a href="#nav-tab-employees" data-bs-toggle="tab" class="nav-link">Employees</a>
                        </li>
                        <li class="nav-item">
                            <a href="#nav-tab-expenses" data-bs-toggle="tab" class="nav-link">Expenses</a>
                        </li>
                        <li class="nav-item"><a href="#nav-tab-services" data-bs-toggle="tab" class="nav-link">Services</a>
                        </li>
                        {{-- <li class="nav-item"><a href="#nav-tab-expenses" data-bs-toggle="tab" class="nav-link">Assets</a>
                        </li> --}}
                        <li class="nav-item next-button">
                            <a href="javascript:;" data-click="next-tab" class="nav-link text-primary"><i
                                    class="fa fa-arrow-right"></i></a>
                        </li>
                    </ul>
                </div>

            </div>
            <div class="panel-body tab-content">
                <div class="tab-pane fade active show" id="nav-tab-clients">
                    <div class="d-flex justify-content-between mb-3">
                        <h2> Clients Ledger </h2>
                        {{-- add new  button component used  to add client --}}
                        <x-add-new-button route="clients.create" label="Add New Client" />

                    </div>

                    <table id="data-table-default" width="100%"
                        class="table table-striped table-bordered align-middle text-nowrap">
                        <thead class="bg-light">
                            <tr>
                                <th>id</th>
                                <th>Name</th>
                                {{-- <th>Address</th> --}}
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                                <tr>
                                    <td>{{ $client->id }}</td>
                                    <td>{{ $client->name }}</td>
                                    {{-- <td>{{ $client->address }}</td> --}}
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->phone }}</td>
                                    <td>

                                        <a href="{{ route('ledger.show', $client->id) }}" class="btn btn-info btn-sm"
                                            title="View Ledger">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('ledger-client-service.index', ['client_id' => $client->id]) }}"
                                            class="btn bg-sidebar text-white btn-sm" title="View Client Services">
                                            <i class="fas fa-list"></i>
                                        </a>


                                        {{-- <a href="" class="btn btn-warning btn-sm" title="Generate Invoice">
                                                <i class="fas fa-file-download"></i>
                                            </a> --}}

                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="nav-tab-employees">
                    <div class="d-flex justify-content-between mb-3">
                        <h2> Employees Ledger </h2>
                        <x-add-new-button route="employees.create" label="Add New Employee" />
                    </div>

                    <table id="data-table-employees" width="100%"
                        class="table table-striped table-bordered align-middle text-nowrap">
                        <thead class="bg-light">
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->position }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->phone }}</td>
                                    <td>
                                        <a href="{{ route('ledger.show', $employee->id) }}" class="btn btn-info btn-sm"
                                            title="View Ledger">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        {{-- <a href="" class="btn btn-info btn-sm" title="Generate Invoice">
                                            <i class="fas fa-fa-list"></i>
                                        </a> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- servivces navtab --}}
                <div class="tab-pane fade" id="nav-tab-services">
                    <div class="d-flex justify-content-between mb-3">
                        <h2> Services Ledger </h2>
                        <x-add-new-button route="OurServices.create" label="Add New Service" />
                    </div>

                    <table id="data-table-services" width="100%"
                        class="table table-striped table-bordered align-middle text-nowrap">
                        <thead class="bg-light">
                            <tr>
                                <th>Service Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <td>{{ $service->name }}</td>
                                    <td>
                                        <a href="{{ route('ledger.show', $service->id) }}" class="btn btn-info btn-sm"
                                            title="View Ledger">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="tab-pane fade" id="nav-tab-expenses">
                    <div class="d-flex justify-content-between mb-3">
                        <h2> Expenses Ledger </h2>

                        <x-add-new-button route="expenses.create" label="Add New Expense" />
                    </div>

                    <table id="data-table-expenses" width="100%"
                        class="table table-striped table-bordered align-middle text-nowrap">
                        <thead class="bg-light">
                            <tr>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $expense)
                                <tr>
                                    <td>{{ $expense->description }}</td>
                                    <td>{{ $expense->amount }}</td>
                                    <td>{{ $expense->date }}</td>
                                    <td>
                                        <a href="{{ route('ledger.show', $expense->id) }}" class="btn btn-info btn-sm"
                                            title="View Ledger">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="" class="btn btn-warning btn-sm" title="Generate Invoice">
                                            <i class="fas fa-file-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_file')
    {{-- for tabs --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('/assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <!-- ================== BEGIN page-js ================== -->
    <script src="{{ asset('/assets/plugins/@highlightjs/cdn-assets/highlight.min.js') }}"></script>
    <script src="{{ asset('/assets/js/demo/render.highlight.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTables for each table
            $('#data-table-default').DataTable({
                responsive: true
            });
            $('#data-table-employees').DataTable({
                responsive: true
            });
            $('#data-table-expenses').DataTable({
                responsive: true
            });
        });
    </script>
@endsection
