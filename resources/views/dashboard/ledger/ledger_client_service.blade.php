@extends('layouts.main')

@section('header_file')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
@endsection

@section('title', 'Ledgers')

@section('content')
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

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
                        <li class="nav-item">
                            <a href="#nav-tab-employees" data-bs-toggle="tab" class="nav-link">Employees</a>
                        </li>
                        <li class="nav-item">
                            <a href="#nav-tab-expenses" data-bs-toggle="tab" class="nav-link">Expenses</a>
                        </li>
                        <li class="nav-item">
                            <a href="#nav-tab-ledger" data-bs-toggle="tab" class="nav-link">Client Service Ledger</a>
                        </li>
                        <li class="nav-item next-button">
                            <a href="javascript:;" data-click="next-tab" class="nav-link text-primary"><i
                                    class="fa fa-arrow-right"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="panel-heading-btn me-2 ms-2 d-flex">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-secondary"
                        data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                </div>
            </div>
            <div class="panel-body tab-content">
                <div class="tab-pane fade active show" id="nav-tab-clients">
                    <div class="d-flex justify-content-between mb-3">
                        <h2> Clients Ledger </h2>
                        <a href="{{ route('clients.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New
                            Client</a>
                    </div>

                    <table id="data-table-clients" width="100%"
                        class="table table-striped table-bordered align-middle text-nowrap">
                        <thead class="bg-light">
                            <tr>
                                <th>id</th>
                                <th>Name</th>
                                <th>Address</th>
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
                                    <td>{{ $client->address }}</td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->phone }}</td>
                                    <td>
                                        <a href="{{ route('ledger.show', $client->id) }}" class="btn btn-info btn-sm"
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

                <div class="tab-pane fade" id="nav-tab-employees">
                    <div class="d-flex justify-content-between mb-3">
                        <h2> Employees Ledger </h2>
                        <a href="{{ route('employees.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add
                            New
                            Employee</a>
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
                                        <a href="" class="btn btn-warning btn-sm" title="Generate Invoice">
                                            <i class="fas fa-file-download"></i>
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
                        <a href="{{ route('expenses.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New
                            Expense</a>
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

                <div class="tab-pane fade" id="nav-tab-ledger">
                    <div class="d-flex justify-content-between mb-3">
                        <h2> Client Service Ledger </h2>
                    </div>

                    <table id="data-table-ledger" width="100%"
                        class="table table-striped table-bordered align-middle text-nowrap">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ledger as $entry)
                                <tr>
                                    <td>{{ $entry->id }}</td>
                                    <td>{{ $entry->description }}</td>
                                    <td>{{ $entry->amount }}</td>
                                    <td>{{ $entry->date }}</td>
                                    <td>
                                        <a href="{{ route('ledger.show', $entry->id) }}" class="btn btn-info btn-sm"
                                            title="View Ledger Entry">
                                            <i class="fas fa-eye"></i>
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

@section('footer_file')
    <script src="{{ asset('assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#data-table-clients').DataTable({
                responsive: true,
            });
            $('#data-table-employees').DataTable({
                responsive: true,
            });
            $('#data-table-expenses').DataTable({
                responsive: true,
            });
            $('#data-table-ledger').DataTable({
                responsive: true,
            });
        });
    </script>
@endsection
