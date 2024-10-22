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
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @include('dashboard.LedgerEmployees.employee-list', ['employees' => $employees]);
    </div>

@section('footer_file')
    <script src="{{ asset('/assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <!-- ================== BEGIN page-js ================== -->
    <script src="{{ asset('/assets/plugins/@highlightjs/cdn-assets/highlight.min.js') }}"></script>
    <script src="{{ asset('/assets/js/demo/render.highlight.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#data-table-clients').DataTable({
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
@endsection
