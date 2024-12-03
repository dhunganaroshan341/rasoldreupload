@extends('layouts.main')

@section('header_file')
    <style>
        .description-column {
            width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endsection

@section('header-left')
    <h2> Charts of Account</h2>
@endsection

@section('header-right')
    {{-- <button type="button" class="btn btn-primary mr-2 mt-3" onclick="toggleSection('employee')">
        <i class="fas fa-users"></i>
        <span class="ml-1">Employee Details</span>
    </button>
    <button type="button" class="btn btn-success mt-3" onclick="toggleSection('payroll')">
        <i class="fas fa-money-bill-wave"></i>
        <span class="ml-1">Payroll Details</span>
    </button> --}}
@endsection



@section('content')
    <div class="d-flex justify-content-between mb-3">

        {{-- add new  button component used  to add sservices --}}
        {{-- <x-add-new-button route="OurServices.create" label="create " /> --}}
        <button type="button" class=" btn btn-primary btn-lg" data-toggle="modal" data-target="#chartOfAccountsModal">
            <small>create New</small>
            <i class="fas fa-plus"></i>
        </button>

    </div>
    <x-charts-of-account.modal-form :uniqueAccountTypes="$uniqueAccountTypes" />
    <x-charts-of-account.default-table :chartsOfAccount="$chartsOfAccount" />
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

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this employee?')) {
                document.getElementById("delete-employee-" + id).submit();
            }
        }

        function toggleSection(section) {
            if (section === 'employee') {
                $('#employee-section').show();
                $('#employee-payroll-section').hide();
            } else if (section === 'payroll') {
                $('#employee-section').hide();
                $('#employee-payroll-section').show();
            }
        }

        function showPayroll(employeeId) {
            // This function can still be used if you need to load specific payroll data
            // For now, it can be a placeholder or extended with AJAX if needed
        }
    </script>
@endsection
