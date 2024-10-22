@extends('layouts.main')

@section('content')
    @if (request()->is($route))
        {{-- Display this content if the URL matches 'services' exactly --}}


        <div class="container mt-5">
            <div class="d-flex justify-content-between mb-3">
                <h2>{{ $crud_item }}</h2>
                <a href="{{ route($crud_item . '.create') }}" class="btn btn-primary">Add New {{ $crud_item }}</a>
            </div>
            @if (session()->get('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        @foreach ($fields as $field)
                            <th>{{ ucfirst(str_replace('_', ' ', $field)) }}</th>
                        @endforeach
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr class="edit-link">
                            @foreach ($fields as $field)
                                <td>{{ $item->$field }}</td>
                            @endforeach
                            <td>
                                @push('css')
                                    <style>
                                        .edit {
                                            display: none;
                                        }

                                        .edit-link:hover .edit {
                                            display: block;
                                        }
                                    </style>
                                @endpush
                                <a class = "edit" href="{{ route($crud_item . '.edit', $item->id) }}">

                                    <i class="dw dw-pen"></i> edit
                                </a>

                                <form action="{{ route($crud_item . '.destroy', $item->id) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    {{-- @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button> --}}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- <div class="d-flex justify-content-center">
        {{ $items->links() }}
    </div> --}}
        </div>
        {{-- @elseif(request()->is($route.'/create'))
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <h2>Add Client</h2>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">Back</a>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}
        {{-- <form action="{{ route($route.'/store') }}" method="POST">
        @csrf
        @foreach ($form_items as item){
            <div class="form-group">
                <label for = "{{ $item_name }}"> {{ $item_name }}</label>
                @if
            </div>

        }
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="client_type">Client Type</label>
            <select name="client_type" id="client_type_select">
                <option value="">Select Client Type</option>
                @foreach ($existingClientTypes as $clientType)
                    <option  value="{{ $clientType->client_type }}">{{ $clientType->client_type }}</option>
                @endforeach
                <option value="new">Enter New Type</option>
            </select>
            {{-- <input type="text" name="client_type" id="new_client_type_input" style="display: none;"> --}}

        {{-- </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" class="form-control" name="address" id="address" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control" name="phone" id="phone" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save</button>
    </form>
</div> --}}
        <script>
            document.getElementById('client_type_select').addEventListener('change', function() {
                if (this.value === 'new') {
                    document.getElementById('new_client_type_input').style.display = 'block';
                } else {
                    document.getElementById('new_client_type_input').style.display = 'none';
                }
            });
        </script>
    @elseif(request()->is('services/*/edit'))
        {{-- Display this content if the URL matches 'services/*/edit' (where * can be any value) --}}
    @else
        {{-- Display this content for any other URL --}}
    @endif
@endsection
