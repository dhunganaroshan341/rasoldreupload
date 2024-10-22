@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Service {{ $our_service->name }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('OurServices.update', $our_service->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input value="{{ $our_service->name }}" type="text" class="form-control" id="name"
                                    name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description">{{ $our_service->description }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="price">Price:</label>
                                <input value="{{ $our_service->price }}" type="number" class="form-control" id="price"
                                    name="price" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="duration">Duration:</label>
                                <div class="input-group">
                                    <input min="1" type="number" class="form-control" id="duration" name="duration"
                                        value="{{ $our_service->duration }}">
                                    <select class="custom-select" id="duration_type" name="duration_type">
                                        {{-- <option value="hours"
                                            {{ $our_service->duration_type == 'hours' ? 'selected' : '' }}>Hours</option> --}}
                                        <option value="days"
                                            {{ $our_service->duration_type == 'days' ? 'selected' : '' }}>Days</option>
                                        <option value="weeks"
                                            {{ $our_service->duration_type == 'weeks' ? 'selected' : '' }}>Weeks</option>
                                        <option value="months"
                                            {{ $our_service->duration_type == 'months' ? 'selected' : '' }}>Months</option>
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <label for="category">Category:</label>
                                <input value="{{ $our_service->category }}" type="text" class="form-control"
                                    id="category" name="category">
                            </div> --}}
                            <div class="form-group">
                                <label for="status">Status:</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active" {{ $our_service->status == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ $our_service->status == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
