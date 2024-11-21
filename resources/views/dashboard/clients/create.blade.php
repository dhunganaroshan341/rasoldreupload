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

        <form action="{{ isset($client) ? route('clients.update', $client->id) : route('clients.store') }}" method="POST">
            @csrf
            @if (isset($client))
                @method('PUT')
            @endif
            <div class="form-group">
                <label for="name">Client Name:</label>
                <input type="text" class="form-control" name="name" id="name" required
                    placeholder="Enter client's full name" value="{{ old('name', $client->name ?? '') }}">
            </div>

            <div class="form-group">
                <label for="service_type">Services Used:</label>
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

            <div class="form-group mt-3" id="existing_services_container">
                <label>Existing Services:</label>
                <div id="existing_services_list" class="checkbox-container">
                    @if (isset($client) && $client->services)
                        @foreach ($client->services as $service)
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="checkbox-existing-{{ $service->id }}"
                                    name="services[]" value="{{ $service->id }}" checked>
                                <label class="form-check-label" for="checkbox-existing-{{ $service->id }}">
                                    {{ $service->name }}
                                </label>
                                {{-- <button type="button" class=" btn btn-primary btn-sm ml-2" data-toggle="modal"
                                    data-target="#moreDetails">
                                    More
                                </button> --}}
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>


            <div class="form-group">
                <label for="hosting_service">Hosting Service</label>
                <input type="text" class="form-control" id="hosting_service" name="hosting_service"
                    placeholder="host.hosting.com" value="{{ old('hosting_service', $client->hosting_service ?? '') }}">
            </div>

            <div class="form-group">
                <label for="email_service">Email Service</label>
                <input type="text" class="form-control" id="email_service" name="email_service"
                    placeholder="email@domain.com" value="{{ old('email_service', $client->email_service ?? '') }}">
            </div>

            <div class="form-group">
                <label for="address">Office Address:</label>
                <input type="text" class="form-control" name="address" id="address" placeholder="Enter office address"
                    value="{{ old('address', $client->address ?? '') }}">
            </div>

            <div class="form-group">
                <label for="pan">PAN/VAT Number:</label>
                <input type="text" class="form-control" name="pan_no" id="pan_no" required
                    placeholder="e.g., 1234567890" value="{{ old('pan_no', $client->pan_no ?? '') }}">
            </div>

            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" class="form-control" name="email" id="email" required
                    placeholder="e.g., example@domain.com" value="{{ old('email', $client->email ?? '') }}">
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" class="form-control" name="phone" id="phone" required
                    placeholder="e.g., +1234567890" value="{{ old('phone', $client->phone ?? '') }}">
            </div>

            <button type="submit"
                class="btn btn-primary mt-3">{{ isset($client) ? 'Update Client' : 'Save Client' }}</button>
        </form>

        {{-- testing livewire form<livewire:client-form /> --}}

        <!-- Modal -->
        <div class="modal fade" id="moreDetails" tabindex="-1" role="dialog" aria-labelledby="moreDetailsLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="moreDetailsLabel">More Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="service_duration_quantity">Service Duration</label>
                            <input type="number" class="form-control" id="service_duration_quantity"
                                name="service_duration_quantity" min="1">
                        </div>
                        <div class="form-group">
                            <label for="service_duration"></label>
                            <select id="service_duration" name="service_duration" class="form-control">
                                <option value="">Select Duration</option>
                                <option value="day">Day</option>
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                                <option value="year">Year</option>
                            </select>
                        </div>
                        <button id="save_more_details" type="button" class="btn btn-primary">Save</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
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
