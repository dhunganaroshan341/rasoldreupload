<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title> Rass | @yield('title')</title>
    <!-- Add Livewire Styles -->

    <!-- Site favicon -->
    {{-- Uncomment if needed
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('vendors/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('vendors/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/favicon-16x16.png') }}">
    --}}

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS Frameworks -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Custom Fonts -->
    <link rel="stylesheet" href="{{ asset('src/fonts/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('src/fonts/foundation-icons/foundation-icons.css') }}">

    <!-- Plugins and Core Styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/vendors/styles/core.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/vendors/styles/icon-font.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/src/plugins/jvectormap/jquery-jvectormap-2.0.3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}">

    <!-- DataTables -->
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}"
        rel="stylesheet" />

    <!-- Gritter -->
    <link href="{{ asset('assets/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" />

    {{-- Uncomment if needed
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    --}}
    @yield('header_file')
    @yield('styles')
    @stack('styles')
    @stack('style-items')

    <style>
        .bg-sidebar {
            background: #0b132b;
        }

        /* Default rotation */
        .rotate {
            display: inline-block;
            transition: transform 0.3s ease;
            /* Smooth rotation */
        }

        /* Rotate upside down */
        .rotate.up {
            transform: rotate(180deg);
        }

        .text-sidebar-bg {
            color: #0b132b;
        }

        .text-golden {
            color: rgb(189, 166, 36);
        }

        /* Sidebar hover */
        .tooltip-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

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
            left: 50%;
            margin-left: -80px;
            opacity: 0;
            transition: opacity 0.3s;
            white-space: nowrap;
        }

        .tooltip-container:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        .modal {
            z-index: 10000;
        }

        .modal-fade {
            z-index: 1000;
        }

        .button {
            background-color: #028090;
            /* Teal button */
            color: #FFFFFF;
            /* White text */
            border: 2px solid #031e23;
            /* Dark border matching sidebar */
            padding: 10px 20px;
            border-radius: 5px;
            /* Optional rounded edges */
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #026a72;
            /* Darker teal on hover */
        }
    </style>
</head>

<body>
