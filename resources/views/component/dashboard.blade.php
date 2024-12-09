@extends('layouts.main')
@section('header-left')
    {{-- @php
        $dropdownItemsForIncome = [
            (object) ['link' => route('incomes.index'), 'faClass' => 'eye', 'linkName' => 'View Incomes'],
            (object) ['link' => route('incomes.create'), 'faClass' => 'plus', 'linkName' => 'Add Income'],
        ];
    @endphp --}}
    {{-- <x-dropdown name="Income" :dropdownItem="$dropdownItemsForIncome" /> --}}

    <a name="" id="" class="    mr-3" href="{{ route('incomes.create') }}" role="button">
        Create Income </a>

    <a name="" id="" class="   mr-3" href="{{ route('expenses.create') }}" role="button">
        </i> Create Expense </a>
@endsection

@section('header-right')
    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Add ourServices"name="" id=""
        class="btn  mr-3 mt-3 " href="{{ route('OurServices.create') }}" role="button">
        <i class="fa fa-plus"></i> <i class="dw dw-settings"></i></a>
    <a title="Add clients" name="" id="" class="btn  mr-3 mt-3 " href="{{ route('clients.create') }}"
        role="button">
        <i class="fa fa-plus"></i> <i class="dw dw-user"></i> </a>


    <a title = "Add Employee" name="" id="" class="btn  mr-3 mt-3 " href="{{ route('employees.create') }}"
        role="button">
        <i class="fa fa-plus"></i> Employee </a>
@endsection
@section('content')
    <div class="xs-pd-20-10 pd-ltr-20">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Dashboard</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <div class="dropdown">

                        <a class="btn  dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                            {{ now()->format('d-m-Y') }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Export List</a>
                            <a class="dropdown-item" href="#">Policies</a>
                            <a class="dropdown-item" href="#">View Assets</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row clearfix">
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card-box pd-20 height-100-p">
                    <div class="progress-box text-center">
                        <h5 class="text-white bg-dark p-2 mb-3 rounded">Total Contracts</h5>
                        <span class="d-block"><strong>{{ $dailyReports['contracts'] }}</strong> Contracts</span>
                        <span class="d-block text-success"><strong>{{ $dailyReports['completed_contracts'] }}</strong>
                            Completed Contracts</span>
                        <span class="d-block text-info"><strong>{{ $dailyReports['contracts_in_progress'] }}</strong>
                            Contracts In Progress</span>
                        <span class="d-block text-warning"><strong>{{ $dailyReports['pending_contracts'] }}</strong> Pending
                            Contracts</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 mb-4">
                <div class="card-box pd-20 height-100-p">
                    <h4 class="mb-30 h4">Contract Status</h4>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" role="progressbar"
                            style="width: {{ ($dailyReports['completed_contracts'] / $dailyReports['contracts']) * 100 }}%;"
                            aria-valuenow="{{ ($dailyReports['completed_contracts'] / $dailyReports['contracts']) * 100 }}"
                            aria-valuemin="0" aria-valuemax="100">
                            {{ round(($dailyReports['completed_contracts'] / $dailyReports['contracts']) * 100, 2) }}%
                            Completed
                        </div>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-info" role="progressbar"
                            style="width: {{ ($dailyReports['contracts_in_progress'] / $dailyReports['contracts']) * 100 }}%;"
                            aria-valuenow="{{ ($dailyReports['contracts_in_progress'] / $dailyReports['contracts']) * 100 }}"
                            aria-valuemin="0" aria-valuemax="100">
                            {{ round(($dailyReports['contracts_in_progress'] / $dailyReports['contracts']) * 100, 2) }}% In
                            Progress
                        </div>
                    </div>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-warning" role="progressbar"
                            style="width: {{ ($dailyReports['pending_contracts'] / $dailyReports['contracts']) * 100 }}%;"
                            aria-valuenow="{{ ($dailyReports['pending_contracts'] / $dailyReports['contracts']) * 100 }}"
                            aria-valuemin="0" aria-valuemax="100">
                            {{ round(($dailyReports['pending_contracts'] / $dailyReports['contracts']) * 100, 2) }}%
                            Pending
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}


        {{-- to show recent clients and to show income expense chart ok  --}}
        <div class="row">
            {{-- getting recent clients from this component --}}
            <x-client-services-list />
            <div class="col-lg-8 col-md-6 col-sm-12 mb-4">
                <div class="card-box pd-30 pt-10 height-100-p">
                    <h2 class="mb-30 h4">Transaction Trend</h2>
                    <x-income-expense-chart />
                </div>
            </div>
        </div>



    </div>
@endsection
