@extends('layouts.main')
@section('script')
    <!-- CSS Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
@endsection
@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <h2>{{ isset($client) ? 'Edit Client' : 'Add Client' }}</h2>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Cancel</a>
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

        <div class="card shadow p-4">
            <form action="{{ isset($client) ? route('clients.update', $client->id) : route('clients.store') }}"
                method="POST">
                @csrf
                @if (isset($client))
                    @method('PUT')
                @endif

                <div class="row mb-3">
                    <label for="name" class="col-md-3 col-form-label text-md-end">Client Name:</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="name" id="name" required
                            placeholder="Enter client's full name" value="{{ old('name', $client->name ?? '') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="service_type" class="col-md-3 col-form-label text-md-end">Services Used:</label>
                    <div class="col-md-9">
                        <select name="service_type" id="service_type" class="form-control">
                            <option value="">Select Service Type</option>
                            @foreach ($existingServiceTypes as $serviceType)
                                <option value="{{ $serviceType->id }}" class="service-option"
                                    {{ isset($client) && $client->service_type_id == $serviceType->id ? 'selected' : '' }}>
                                    {{ $serviceType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-3 col-form-label text-md-end">Existing Services:</label>
                    <div class="col-md-9">
                        <div id="existing_services_list" class="checkbox-container">
                            @if (isset($client) && $client->services)
                                @foreach ($client->services as $service)
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="checkbox-existing-{{ $service->id }}" name="services[]"
                                            value="{{ $service->id }}" checked>
                                        <label class="form-check-label" for="checkbox-existing-{{ $service->id }}">
                                            {{ $service->name }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Other form fields follow the same row/label/input format -->
                @foreach ([
            'hosting_service' => 'Hosting Service',
            'email_service' => 'Email Service',
            'address' => 'Office Address',
            'pan_no' => 'PAN/VAT Number',
            'email' => 'Email Address',
            'phone' => 'Phone Number',
        ] as $field => $label)
                    <div class="row mb-3">
                        <label for="{{ $field }}"
                            class="col-md-3 col-form-label text-md-end">{{ $label }}:</label>
                        <div class="col-md-9">
                            <input type="{{ $field === 'email' ? 'email' : 'text' }}" class="form-control"
                                id="{{ $field }}" name="{{ $field }}"
                                placeholder="Enter {{ strtolower($label) }}"
                                value="{{ old($field, $client->$field ?? '') }}">
                        </div>
                    </div>
                @endforeach

                <div class="row mb-3">
                    <div class="col-md-9 offset-md-3">
                        <button type="submit"
                            class="btn btn-primary">{{ isset($client) ? 'Update Client' : 'Save Client' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script-items')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.service-option').on('click', function() {
                var serviceId = $(this).val();
                var serviceName = $(this).text();

                if (!$('#checkbox-' + serviceId).length) {
                    $('#existing_services_list').append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkbox-${serviceId}" name="services[]" value="${serviceId}" checked>
                                <label class="form-check-label" for="checkbox-${serviceId}">
                                    ${serviceName}
                                </label>
                                <button type="button" class="remove-btn" onclick="removeItem(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `);
                }
            });

            // Remove service item
            function removeItem(element) {
                $(element).parent().remove();
            }

            // More Details Modal
            $('#save_more_details').on('click', function() {
                var durationQuantity = $('#service_duration_quantity').val();
                var duration = $('#service_duration').val();
                var serviceId = $('#service_id').val();

                // Logic to save duration details
                // Close modal
                $('#moreDetails').modal('hide');
            });
        });
    </script>
@endpush
