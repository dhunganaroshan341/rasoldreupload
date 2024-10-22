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
                    <div class="card-header">{{ isset($service) ? 'Edit Service' : 'Create Service' }}</div>

                    <div class="card-body">
                        <form method="POST"
                            action="{{ isset($service) ? route('OurServices.update', $service->id) : route('OurServices.store') }}">
                            @csrf
                            @if (isset($service))
                                @method('PUT')
                            @endif

                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', isset($service) ? $service->name : '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description">{{ old('description', isset($service) ? $service->description : '') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="price">Price:</label>
                                <input min='1' type="number" class="form-control" id="price" name="price"
                                    value="{{ old('price', isset($service) ? $service->price : '') }}" required>
                            </div>

                            <div class="form-group">
                                <label for="duration">Duration:</label>
                                <div class="input-group">
                                    <input min='1' type="number" class="form-control" id="duration" name="duration"
                                        value="{{ old('duration', isset($service) ? $service->duration : '') }}">
                                    <select class="custom-select" id="duration_type" name="duration_type">
                                        <option value="days"
                                            {{ old('duration_type', isset($service) ? $service->duration_type : '') == 'days' ? 'selected' : '' }}>
                                            Days
                                        </option>
                                        <option value="weeks"
                                            {{ old('duration_type', isset($service) ? $service->duration_type : '') == 'weeks' ? 'selected' : '' }}>
                                            Weeks
                                        </option>
                                        <option value="months"
                                            {{ old('duration_type', isset($service) ? $service->duration_type : '') == 'months' ? 'selected' : '' }}>
                                            Months
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="form-group">
                                <label for="category">Category:</label>
                                <select class="form-control" id="category" name="category">
                                    <option value="">Select or Add Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category', isset($service) ? $service->category_id : '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                    <option value="new">Add New Category</option>
                                </select>
                            </div> --}}

                            <div class="form-group">
                                <label for="status">Status:</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="active"
                                        {{ old('status', isset($service) ? $service->status : '') == 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="inactive"
                                        {{ old('status', isset($service) ? $service->status : '') == 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ isset($service) ? 'Update' : 'Create' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
