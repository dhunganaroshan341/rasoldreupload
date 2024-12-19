@extends('layouts.main')
@push('style-items')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <style>
        .description-column {
            width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Display the client's name at the top -->
                <h4>Services Used</h4>
                <h2><span class="text-success">{{ $client->name }}</span></h2>

                @include('components.client-service-list-ledger')
            </div>
        </div>
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
        });
    </script>
@endpush
