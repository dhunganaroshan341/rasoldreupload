@extends('layouts.main')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Service</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('clients.update', $client->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $client->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description">{{ $client->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="price">Price:</label>
                                <input min='1' type="number" class="form-control" id="price" name="price"
                                    value="{{ $client->price }}" required>
                            </div>
                            <div class="form-group">
                                <label for="duration">Duration:</label>
                                <div class="input-group">
                                    <input min='1' type="number" class="form-control" id="duration"
                                        name="duration_number" value="{{ $client->duration }}">
                                    <select class="custom-select" id="duration_type" name="duration_type">
                                        <option value="hours" {{ $client->duration_type == 'hours' ? 'selected' : '' }}>
                                            hours
                                        </option>
                                        <option value="days" {{ $client->duration_type == 'days' ? 'selected' : '' }}>
                                            Days
                                        </option>
                                        <option value="weeks" {{ $client->duration_type == 'weeks' ? 'selected' : '' }}>
                                            Weeks
                                        </option>
                                        <option value="months" {{ $client->duration_type == 'months' ? 'selected' : '' }}>
                                            Months
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="category">Category:</label>
                                <select class="form-control" id="category" name="category">
                                    <option value="">Select or Add Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $client->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                    <option value="new">Add New Category</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="status">Status:</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active" {{ $client->status == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ $client->status == 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this section for additional JavaScript -->
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Script for handling services and modals
            $('#category').on('change', function() {
                var selectedValue = $(this).val();
                if (selectedValue == 'new') {
                    // Handle new category logic
                    $('#newCategoryModal').modal('show');
                }
            });

            // You may add more JavaScript functions here as needed
        });
    </script>
@endsection
@endsection
