@extends('layouts.main')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <h2>Contracts</h2>
            <a href="{{ route('contracts.create') }}" class="btn btn-primary">Add New Contract</a>
        </div>
        @if (session()->get('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @extends('layouts.main')

    @section('content')
        <div class="container mt-4">
            <h2>{{ $crud_item }}</h2>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Contract Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $contract)
                        <tr>
                            <td>{{ $contract->id }}</td>
                            <td>{{ $contract->name }}</td>
                            <td>{{ $contract->description }}</td>
                            <td>{{ $contract->price }}</td>
                            <td>{{ $contract->start_date }}</td>
                            <td>{{ $contract->end_date }}</td>
                            <td>{{ $contract->duration }}

                            </td>
                            <td>{{ ucfirst($contract->status) }}</td>
                            <td>
                                <a href="{{ route('contracts.edit', $contract->id) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                {{-- <form action="{{ route('contracts.destroy', $contract->id) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $items->links() }} <!-- Pagination links -->

            <a href="{{ route('contracts.create') }}" class="btn btn-primary mt-3">Create New Contract</a>
        </div>
    @endsection

@endsection
