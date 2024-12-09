@extends('layouts.main')

@push('style-items')
    <!-- CSS Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="../assets/plugins/select-picker/dist/picker.min.css" rel="stylesheet" />
    <!-- Custom CSS for checkbox layout -->
    <style>
        .checkbox-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .checkbox-container .form-check {
            margin-right: 1rem;
            position: relative;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            box-shadow: 0 0 0.125rem rgba(0, 0, 0, 0.075);
        }

        .remove-btn {
            border: none;
            background: none;
            cursor: pointer;
        }

        .duration-dropdown {
            display: block;
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 0.5rem;
            margin-left: 0.5rem;
            margin-right: 1rem;
            width: 250px;
            border: 1px solid #ccc;
            padding: 0.5rem;
            background-color: #fff;
            box-shadow: 5px 10px 11px 1px silver;
            z-index: 10;
            border-radius: 0.25rem;
        }

        .duration-dropdown .close-btn {
            border: none;
            background: none;
            cursor: pointer;
            float: right;
        }

        .duration-dropdown input {
            width: 80px;
            margin-left: 0.5rem;
        }

        .form-check .form-check-input {
            margin-right: 0.5rem;
        }
    </style>
@endpush

@section('header-left-title', isset($client) ? 'Edit Client: ' . $client->name : 'Create Client')

@section('content')
    <div class="container mt-4">
        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Cancel Button -->
        <div class="d-flex justify-content-between mb-3">
            <h4>{{ isset($client) ? 'Edit Client Details' : 'Add New Client' }}</h4>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Cancel</a>
        </div>

        <!-- Form Card -->
        <div class="card shadow">
            <div class="card-body">
                <form action="{{ isset($client) ? route('clients.update', $client->id) : route('clients.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($client))
                        @method('PUT')
                    @endif

                    <!-- Name and Service -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Client Name:</label>
                            <input type="text" class="form-control" name="name" id="name" required
                                placeholder="Enter client's full name" value="{{ old('name', $client->name ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="service_type" class="form-label">Select Services:</label>
                            <select id="service_type" class="form-select">
                                <option value="">Select Service</option>
                                @foreach ($existingServiceTypes as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Selected Services -->
                    <div class="mb-3">
                        <label class="form-label">Selected Services:</label>
                        <div id="existing_services_list" class="row">
                            @if (isset($client) && $client->services)
                                @foreach ($client->services as $service)
                                    <div class="col-md-3 service-item" id="service-item-{{ $service->id }}">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                id="checkbox-{{ $service->id }}" name="services[]"
                                                value="{{ $service->id }}" checked>
                                            <label class="form-check-label" for="checkbox-{{ $service->id }}">
                                                {{ $service->name }}
                                            </label>
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-2 remove-btn"
                                                onclick="removeService({{ $service->id }})">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">No existing services selected.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Hosting and Email Service -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hosting_service" class="form-label">Hosting Service:</label>
                            <input type="text" class="form-control" id="hosting_service" name="hosting_service"
                                placeholder="Enter hosting service details"
                                value="{{ old('hosting_service', $client->hosting_service ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email_service" class="form-label">Email Service:</label>
                            <input type="text" class="form-control" id="email_service" name="email_service"
                                placeholder="Enter email service details"
                                value="{{ old('email_service', $client->email_service ?? '') }}">
                        </div>
                    </div>

                    <!-- Address and PAN/VAT -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Office Address:</label>
                            <input type="text" class="form-control" id="address" name="address"
                                placeholder="Enter office address" value="{{ old('address', $client->address ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pan_no" class="form-label">PAN/VAT Number:</label>
                            <input type="text" class="form-control" id="pan_no" name="pan_no"
                                placeholder="Enter PAN/VAT number" value="{{ old('pan_no', $client->pan_no ?? '') }}">
                        </div>
                    </div>

                    <!-- Email and Phone -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address:</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter email address" value="{{ old('email', $client->email ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number:</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                placeholder="Enter phone number" value="{{ old('phone', $client->phone ?? '') }}">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($client) ? 'Update Client' : 'Save Client' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script-items')
    <script>
        $(document).ready(function() {
            $('#service_type').on('change', function() {
                var serviceId = $(this).val();
                var serviceName = $(this).find('option:selected').text();

                if (serviceId && !$('#checkbox-' + serviceId).length) {
                    $('#existing_services_list').append(`
                        <div class="col-md-3 service-item" id="service-item-${serviceId}">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkbox-${serviceId}" name="services[]" value="${serviceId}" checked>
                                <label class="form-check-label" for="checkbox-${serviceId}">${serviceName}</label>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2 remove-btn" onclick="removeService(${serviceId})">Remove</button>
                            </div>
                        </div>
                    `);
                }
            });
        });

        function removeService(serviceId) {
            $('#service-item-' + serviceId).remove();
        }
    </script>
@endpush
