@extends('layouts.main')

@section('content')
    <h1>update <span class="text-success">{{ $employee->name }}</span> </h1>

    @include('dashboard.employees.form')
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
@endsection
