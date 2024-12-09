@extends('layouts.main')

@push('style-items')
    <style>
        .description-column {
            width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endpush
@section('header-left-title', 'Employees')
@section('header-right')
    <button type="button" class="btn  mr-2 mt-3 btn-sm ml-2" onclick="toggleSection('employee')">
        <i class="fas fa-users text-primary"></i>
        <span class="ml-1">Employee Details</span>
    </button>
    <button type="button" class="btn   mt-3 btn-sm mr-2 " onclick="toggleSection('payroll')">
        <i class="fas fa-money-bill-wave text-success"></i>
        <span class="ml-1">Payroll Details</span>
    </button>
    <button type="button" class="btn mt-3 mr-2  " data-toggle="modal" data-target="#modelId">
        <small>salary</small>
        <i class="fas fa-plus  text-primary"></i>
        <i class="fas fa-money-bill-wave text-success"></i>
    </button>
@endsection



@section('content')
    {{-- @include('components.create-new-button', [
        'route' => 'employees.create',
        'routeName' => 'create Employee',
    ]) --}}
    @include('components.employees.employee-payroll-form')
    <!-- Employee Details Section -->
    <div id="employee-section">
        <!-- Employee Table -->
        <h3 id="paryollDetailsHeading">Employee Details</h3>
        @include('components.employees.employee-table', ['employees' => $employees])
    </div>

    <!-- Employee Payroll Section -->
    <div id="employee-payroll-section" style="display:none;">
        <h3 id="paryollDetailsHeading">Payroll Details</h3>
        @include('components.employees.employee-payroll-table', ['payrolls' => $payrolls])
    </div>
@endsection


@push('script-items')
    <script src="{{ asset('/assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#data-table-default').DataTable({
                responsive: true
            });
            $('#data-table-default-payroll').DataTable({
                responsive: true
            })
        });
        // data table export options

        $('#data-table-payroll').DataTable({
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
        // end of datatable
    </script>
    <script>
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
@endpush
