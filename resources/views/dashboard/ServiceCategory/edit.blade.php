@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Service {{ $our_service->name }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('dashboard.OurServices.update') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input value="{{ $our_service->name }}" type="text" class="form-control" id="name"
                                    name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea value ={{ $our_service->description }}class="form-control" id="description" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="price">Price:</label>
                                <input value="{{ $our_service->price }}" type="number" class="form-control" id="price"
                                    name="price" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="duration">Duration:</label>
                                <input value="{{ $our_service->duration }}" type="number" class="form-control"
                                    id="duration" name="duration">
                            </div>
                            <div class="form-group">
                                <label for="category">Category:</label>
                                <input value="{{ $our_service->category }}" type="text" class="form-control"
                                    id="category" name="category">
                            </div>
                            <div class="form-group">
                                <label for="status">Status:</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
