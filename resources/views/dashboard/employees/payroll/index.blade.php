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
    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modelId">
        <small>salary</small>
        <i class="fas fa-plus"></i>
        <i class="fas fa-money-bill-wave"></i>
    </button>
@endsection

@section('header-right')
    <button type="button" class="btn btn-primary mr-2 mt-3" onclick="toggleSection('employee')">
        <i class="fas fa-users"></i>
        <span class="ml-1">Employee Details</span>
    </button>
    <button type="button" class="btn btn-success mt-3" onclick="toggleSection('payroll')">
        <i class="fas fa-money-bill-wave"></i>
        <span class="ml-1">Payroll Details</span>
    </button>
@endsection



@section('content')
    @include('components.create-new-button', [
        'route' => " 'employeePayroll.create,['id'=>$employee_id]'",
        'routeName' => 'Add Salary',
    ])
    @include('components.employees.employee-payroll-form')
    <!-- Employee Details Section -->
    <div id="employee-section">
        <!-- Employee Table -->
        @include('components.employees.employee-table', ['employees' => $employees])
    </div>

    <!-- Employee Payroll Section -->
    <div id="employee-payroll-section" style="display:none;">
        <h3 id="paryollDetailsHeading">Payroll Details</h3>
        @include('components.employees.employee-payroll-table', ['payrolls' => $payrolls])
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
