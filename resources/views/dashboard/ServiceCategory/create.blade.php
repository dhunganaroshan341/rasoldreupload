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
                    <div class="card-header">
                        {{ isset($ServiceCategory) ? 'Edit ServiceCategory' : 'Create ServiceCategory' }}
                    </div>

                    <div class="card-body">
                        <form method="POST"
                            action="{{ isset($ServiceCategory) ? route('ServiceCategory.update', $ServiceCategory->id) : route('ServiceCategory.store') }}">
                            @csrf
                            @if (isset($ServiceCategory))
                                @method('PUT')
                            @endif

                            <div class="form-group">
                                <label for="name">Category Name:</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ isset($ServiceCategory) ? $ServiceCategory->name : '' }}" required>
                            </div>

                            <div class="form-group">
                                <label for="parent_category_id">Parent Category:</label>
                                <select class="form-control" id="parent_category_id" name="parent_category_id">
                                    <option value="">Select Parent Category</option>
                                    @foreach ($parentCategories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ isset($ServiceCategory) && $ServiceCategory->parent_category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="4">{{ isset($ServiceCategory) ? $ServiceCategory->description : '' }}</textarea>
                            </div>
                            <button type="submit"
                                class="btn btn-primary">{{ isset($ServiceCategory) ? 'Update' : 'Create' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
