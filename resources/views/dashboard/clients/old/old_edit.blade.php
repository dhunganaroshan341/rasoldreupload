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
        <div class="d-flex justify-content-between mb-3">
            <h2>{{ isset($client) ? 'Edit Client' : 'Add Client' }}</h2>
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
                <input type="text" class="form-control" name="name" id="name" required
                    value="{{ old('name', $client->name ?? '') }}">
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
                <label for="new_service">New Service</label>
                <input type="text" class="form-control" name="new_service" id="new_service" placeholder="Eg:SEO">
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
                <label for="hosting_service">hosting_service:</label>
                <input type="text" class="form-control" name="hosting_service" id="hosting_service"
                    value="{{ old('hosting_service', $client->hosting_service ?? '') }}">
            </div>
            <div class="form-group">
                <label for="hosting_service">Email Service:</label>
                <input type="text" class="form-control" name="email_service" id="email_service"
                    value="{{ old('email_service', $client->email_service ?? '') }}">
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" name="address" id="address" required
                    value="{{ old('address', $client->address ?? '') }}">
            </div>
            <div class="form-group">
                <label for="pan_no">PAN/VAT No</label>
                <input type="text" class="form-control" name="pan_no" id="pan_no" required
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
                <input type="text" class="form-control" name="phone" id="phone" required
                    value="{{ old('phone', $client->phone ?? '') }}">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Save</button>
        </form>

    </div>

    <!-- JavaScript Links -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        var addedServices = {}; // Track dynamically added services
        var existingServices = {}; // Track existing services

        $(document).ready(function() {
            // Handle change event for service_type select
            $('#service_type').on('change', function() {
                var selectedValue = $(this).val();
                if (selectedValue === 'new') {
                    $('#new_service_container').show();
                } else if (selectedValue) {
                    addExistingServiceToCheckbox(selectedValue);
                } else {
                    $('#new_service_container').hide();
                }
            });

            // Handle adding new service checkboxes dynamically
            $('#new_service').on('keypress', function(e) {
                if (e.which === 13) { // Enter key pressed
                    e.preventDefault(); // Prevent form submission
                    var newServiceName = $(this).val().trim();
                    if (newServiceName) {
                        var isExistingService = $('#service_type option').filter(function() {
                            return $(this).text().trim().toLowerCase() === newServiceName
                                .toLowerCase() &&
                                $(this).val() !== 'new';
                        }).length > 0;

                        if (isExistingService) {
                            alert(
                                'Service already exists in the dropdown. Please select it from the dropdown.'
                            );
                        } else {
                            addNewService(newServiceName);
                            $(this).val(''); // Clear the input field
                        }
                    }
                }
            });

            // Function to add new service
            function addNewService(serviceName) {
                if ($('#new_services_list').find('label').filter(function() {
                        return $(this).text().trim() === serviceName;
                    }).length > 0) {
                    alert('Service already exists');
                    return;
                }

                var checkboxId = 'checkbox-' + new Date().getTime();
                var checkboxHtml = `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="${checkboxId}" value="${serviceName}">
                        <label class="form-check-label" for="${checkboxId}">
                            ${serviceName}
                        </label>
                        <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn">
                            <i class="fa fa-close text-danger"></i>
                        </button>
                    </div>
                `;
                $('#new_services_list').append(checkboxHtml);

                var optionHtml = `<option value="${serviceName}" class="service-option">${serviceName}</option>`;
                $('#service_type').append(optionHtml);
                addedServices[serviceName] = true;
                $('#service_type option[value="' + serviceName + '"]').prop('disabled', true);
            }

            // Handle removing service checkboxes and re-enable select option
            $('#new_services_list').on('click', '.remove-btn', function() {
                var serviceName = $(this).siblings('label').text().trim();
                $(this).parent().remove();

                if (addedServices[serviceName]) {
                    $('#service_type').append(
                        `<option value="${serviceName}" class="service-option">${serviceName}</option>`);
                    delete addedServices[serviceName];
                } else {
                    $('#service_type option').each(function() {
                        if ($(this).text().trim() === serviceName) {
                            $(this).prop('disabled', false);
                        }
                    });
                }
            });

            // Function to add existing service to checkboxes
            function addExistingServiceToCheckbox(serviceId) {
                var serviceName = $('#service_type option[value="' + serviceId + '"]').text().trim();
                var checkboxId = 'checkbox-existing-' + serviceId;

                if ($('#existing_services_list').find('label').filter(function() {
                        return $(this).text().trim() === serviceName;
                    }).length > 0) {
                    return;
                }

                var checkboxHtml = `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="${checkboxId}" checked>
                        <label class="form-check-label" for="${checkboxId}">
                            ${serviceName}
                        </label>
                        <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn">
                            <i class="fa fa-close text-danger"></i>
                        </button>
                    </div>
                `;
                $('#existing_services_list').append(checkboxHtml);
            }

            // Initialize the form state based on existing data
            // Ensure the new service input field is shown if the 'new' option is selected
            if ($('#service_type').val() === 'new') {
                $('#new_service_container').show();
            } else {
                $('#new_service_container').hide();
            }
        });
    </script>

@endsection
