@extends('layouts.main')

@section('header_file')
    <style>
        .description-column {
            width: 150px;
            /* Adjust this value as needed */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endsection

{{-- @section('title', 'Our Services') --}}

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2> Our Services</h2>
        {{-- add new  button component used  to add sservices --}}
        <x-add-new-button route="OurServices.create" label="Add New Service" />

    </div>
    <table id="data-table-default" width="100%" class="table table-striped table-bordered align-middle text-nowrap">
        <thead class="bg-light">
            <tr>
                <th>ID</th>
                <th>service </th>




                <th>Price</th>
                <th class="description-column">Description</th> {{-- Apply the custom class here --}}
                <th>Duration</th>
                <th>Duration Type</th>
                <th>Category</th>
                <th>Status</th>
                <th data-orderable="false">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($OurServices as $service)
                <tr class="hover-item position-relative">
                    <td width = "1%">{{ $service->id }}</td>
                    <td width = "2%">{{ $service->name }}</td>
                    {{-- Apply the custom class here --}}
                    <td width = "10%">{{ $service->price }}</td>
                    <td width="5%">{{ $service->description }}</td>
                    <td width = "10%">{{ $service->duration }}</td>
                    <td width = "10%">{{ $service->duration_type }}</td>
                    <td width = "10%">{{ $service->category }}</td>
                    <td width = "10%">{{ $service->status }}</td>
                    <td class="position-relative">
                        <a href="{{ route('OurServices.edit', $service->id) }}" class="btn btn-link text-dark">
                            <i class="fa fa-edit"></i>
                        </a>
                        <form id="delete-{{ $service->id }}" action="{{ route('OurServices.destroy', $service->id) }}"
                            method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete({{ $service->id }})"
                                class="btn btn-link text-dark">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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
            if (confirm('Are you sure you want to delete this item?')) {
                document.getElementById("delete-" + id).submit();
            }
        }
    </script>
@endsection
