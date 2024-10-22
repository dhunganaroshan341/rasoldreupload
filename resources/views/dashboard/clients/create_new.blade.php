@extends('layouts.main')

@section('script')
    <!-- CSS Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 0.5rem;
            margin-left: 0.5rem;
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
            <h2>Add Client</h2>
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

        <form action="{{ route('clients.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Client Name:</label>
                <input type="text" class="form-control" name="name" id="name" required
                    placeholder="Enter client's full name" value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label for="service_type">Services Used:</label>
                <select name="service_type" id="service_type" class="form-control">
                    <option value="">Select Service Type</option>
                    <option class="text-info" value="new">Add New Service</option>
                    @foreach ($existingServiceTypes as $serviceType)
                        <option value="{{ $serviceType->id }}" class="service-option">
                            {{ $serviceType->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-3" id="new_service_container" style="display: none;">
                <label for="new_service">New Service:</label>
                <input type="text" class="form-control" name="new_service" id="new_service"
                    placeholder="e.g., SEO, Social Media Management">
            </div>

            <div id="new_services_list" class="mt-3 checkbox-container">
                <!-- Dynamic new service checkboxes will be added here -->
            </div>

            <div class="form-group mt-3" id="existing_services_container">
                <label>Existing Services:</label>
                <div id="existing_services_list" class="checkbox-container">
                    <!-- Existing services checkboxes will be dynamically added here -->
                </div>
            </div>

            <div class="form-group">
                <label for="address">Office Address:</label>
                <input type="text" class="form-control" name="address" id="address" required
                    placeholder="Enter office address" value="{{ old('address') }}">
            </div>

            <div class="form-group">
                <label for="pan">PAN/VAT Number:</label>
                <input type="text" class="form-control" name="pan_no" id="pan_no" required
                    placeholder="e.g., 1234567890" value="{{ old('pan_no', $client->pan_no ?? '') }}">
            </div>

            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" class="form-control" name="email" id="email" required
                    placeholder="e.g., example@domain.com" value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" class="form-control" name="phone" id="phone" required
                    placeholder="e.g., +1234567890" value="{{ old('phone') }}">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Save Client</button>
        </form>
    </div>

    <!-- JavaScript Links -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
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

            $('#new_service').on('keypress', function(e) {
                if (e.which === 13) { // Enter key pressed
                    e.preventDefault(); // Prevent form submission
                    var newServiceName = $(this).val().trim();
                    if (newServiceName) {
                        addNewService(newServiceName);
                        $(this).val(''); // Clear the input field
                    }
                }
            });

            function addNewService(serviceName) {
                if ($('#new_services_list').find('label').filter(function() {
                        return $(this).text().trim() === serviceName;
                    }).length > 0) {
                    alert('Service already exists');
                    return;
                }

                var checkboxId = 'checkbox-' + new Date().getTime();
                var checkboxHtml = `
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="${checkboxId}" name="services[]" value="${serviceName}">
                        <label class="form-check-label" for="${checkboxId}">
                            ${serviceName}
                        </label>
                        <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn">
                            <i class="fa fa-close text-danger"></i>
                        </button>
                        <div class="duration-dropdown mt-2 p-2 border rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="duration-${checkboxId}" class="mb-0">Duration:</label>
                                <button type="button" class="btn btn-sm btn-outline-secondary close-btn">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                            <select id="duration-${checkboxId}" name="service_duration[${serviceName}]" class="form-control mb-2">
                                <option value="">Select Duration</option>
                                <option value="day">Day</option>
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                                <option value="year">Year</option>
                            </select>
                            <input type="number" name="service_duration_quantity[${serviceName}]" min="1" placeholder="Quantity" class="form-control">
                        </div>
                    </div>
                `;
                $('#new_services_list').append(checkboxHtml);
            }

            $('#new_services_list').on('click', '.remove-btn', function() {
                $(this).parent().remove();
            });

            function addExistingServiceToCheckbox(serviceId) {
                var serviceName = $('#service_type option[value="' + serviceId + '"]').text().trim();
                var checkboxId = 'checkbox-existing-' + serviceId;

                if ($('#existing_services_list').find('label').filter(function() {
                        return $(this).text().trim() === serviceName;
                    }).length > 0) {
                    return;
                }

                var checkboxHtml = `
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="${checkboxId}" name="services[]" value="${serviceName}">
                        <label class="form-check-label" for="${checkboxId}">
                            ${serviceName}
                        </label>
                        <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn">
                            <i class="fa fa-close text-danger"></i>
                        </button>
                        <div class="duration-dropdown mt-2 p-2 border rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="duration-${checkboxId}" class="mb-0">Duration:</label>
                                <button type="button" class="btn btn-sm btn-outline-secondary close-btn">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                            <select id="duration-${checkboxId}" name="service_duration[${serviceName}]" class="form-control mb-2">
                                <option value="">Select Duration</option>
                                <option value="day">Day</option>
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                                <option value="year">Year</option>
                            </select>
                            <input type="number" name="service_duration_quantity[${serviceName}]" min="1" placeholder="Quantity" class="form-control">
                        </div>
                    </div>
                `;
                $('#existing_services_list').append(checkboxHtml);
            }

            $('#existing_services_list').on('click', '.remove-btn', function() {
                $(this).parent().remove();
            });
        });
    </script>
@endsection
