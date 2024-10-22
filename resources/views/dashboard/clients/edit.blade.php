@extends('layouts.main')

@section('script')
    <!-- CSS Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS for checkbox layout -->
    <style>
        .checkbox-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .checkbox-container .form-check {
            margin-right: 1rem;
        }

        .remove-btn {
            border: none;
            background: none;
            cursor: pointer;
        }
    </style>
@endsection

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

        <div class="d-flex justify-content-between mb-3">
            <h2>{!! isset($client) ? 'Edit Client | <i>' . $client->name . '</i>' : 'Add Client' !!}</h2>
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
        @endif

        <form action="{{ isset($client) ? route('clients.update', $client) : route('clients.store') }}" method="POST">
            @csrf
            @if (isset($client))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" id="name"
                    value="{{ old('name', $client->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="service_type">Services Used:</label>
                <select name="service_type" id="service_type" class="form-control">
                    <option value="">Select Services</option>
                    <option class="text-info" value="new">Enter New Service</option>
                    @foreach ($existingServiceTypes as $serviceType)
                        <option value="{{ $serviceType->id }}">
                            {{ $serviceType->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-3" id="new_service_container" style="display: none;">
                <label for="new_service">New Service:</label>
                <input type="text" class="form-control" name="new_service" id="new_service" placeholder="Eg: SEO">
            </div>

            <div id="new_services_list" class="mt-3 checkbox-container">
                <!-- Dynamically added new services will be displayed here -->
            </div>

            <div class="form-group mt-3" id="existing_services_container">
                <label>Existing Services:</label>
                <div id="existing_services_list" class="checkbox-container">
                    @foreach ($existingServiceTypes as $serviceType)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="services[]" value="{{ $serviceType->id }}"
                                {{ in_array($serviceType->id, $clientServices) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $serviceType->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label for="hosting_service">Hosting:</label>
                <input type="text" class="form-control" name="hosting_service" id="hosting_service"
                    placeholder="Hosting Service"
                    value="{{ isset($client->hosting_service) ? $client->hosting_service : '' }}">
            </div>

            <div class="form-group">
                <label for="email_service">Email Service:</label>
                <input type="text" class="form-control" name="email_service" id="email_service"
                    placeholder="Email Service" value="{{ isset($client->email_service) ? $client->email_service : '' }}">
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" name="address" id="address"
                    value="{{ old('address', $client->address ?? '') }}">
            </div>

            <div class="form-group">
                <label for="pan_no">PAN/VAT No:</label>
                <input type="text" class="form-control" name="pan_no" id="pan_no"
                    value="{{ old('pan_no', $client->pan_no) }}">
            </div>

            <div>
                <strong>Client PAN No:</strong> {{ $client->pan_no }}
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" id="email"
                    value="{{ old('email', $client->email ?? '') }}">
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" class="form-control" name="phone" id="phone"
                    value="{{ old('phone', $client->phone ?? '') }}">
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                {{ isset($client) ? 'Update' : 'Create' }}
            </button>
        </form>
    </div>





@endsection
