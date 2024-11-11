<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title> Rass </title>
    <!-- Add Livewire Styles -->

    <!-- Site favicon -->
    {{-- <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendors/images/apple-touch-icon.png') }}"> --}}
    {{-- <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/favicon-32x32.png') }}"> --}}
    {{-- <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}"> --}}

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">


    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css" rel="stylesheet" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <link rel="stylesheet" href="{{ asset('src/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('src/fonts/foundation-icons/foundation-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/vendors/styles/core.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/vendors/styles/icon-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('src/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/src/plugins/jvectormap/jquery-jvectormap-2.0.3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}">

    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}"> --}}
    <script src="//cdn.datatables.net/2.1.2/js/dataTables.min.js')}}"></script>


    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}"
        rel="stylesheet" />
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> --}}

    @yield('header_file')

    @yield('styles')
    @stack('styles')

    <style>
        .bg-sidebar {
            background: #0b132b;
        }

        .text-sidebar-bg {
            color: #0b132b;
        }

        .text-golden {
            color: rgb(189, 166, 36);

        }

        /* sidebar hover */
        /* Tooltip container */
        .tooltip-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        /* Tooltip text */
        .tooltip-text {
            visibility: hidden;
            width: 160px;
            background-color: #333;
            color: #834a4a;
            text-align: center;
            border-radius: 5px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 100%;
            /* Position above the container */
            left: 50%;
            margin-left: -80px;
            /* Center the tooltip */
            opacity: 0;
            transition: opacity 0.3s;
            white-space: nowrap;
        }

        /* Show tooltip on hover */
        .tooltip-container:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* action button styles */

        .action-row {
            position: relative;
        }

        .action-buttons-not-disabled {
            /* display: none; */
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


</head>

<body>
