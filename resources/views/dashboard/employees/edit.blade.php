@extends('layouts.main')
@section('header-left-title', 'Update ' . $employee->name)
@section('header-right')
    <a href="{{ route('employees.index') }}" class="mt-4 mr-4 badge badge-dark">Employee</a>
@endsection
@section('content')
    <h1> <span class="text-success"></span> </h1>
    @include('dashboard.employees.form')
@endsection
@push('script-items')
    <script>
        $(document).ready(function() {
            // Check for edit message passed directly
            @if (isset($editMessage))
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000", // Duration for which the toast will be visible
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };
                toastr.info("{{ $editMessage }}", "Edit Mode"); // Use info for edit messages
            @endif
        });
    </script>
@endpush
