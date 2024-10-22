@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Create Contracts</h2>
            <a href="{{ route('contracts.index') }}" class="btn btn-secondary">Back</a>
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

        <form action="{{ route('contracts.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Contract Name:</label>
                <input type="text" class="form-control" name="name" id="name"
                    placeholder="Name of the project/contract" required>
            </div>

            <div class="form-group">
                <label for="client_select">Select Client:</label>
                <select class="form-control" name="client_id" id="client_select" required>
                    <option value="">Select Existing Client</option>
                    <option class ="text-info" value="new_client">Add New Client</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" id="newClient">
                <label for="new_client_name">New Client Name:</label>
                <input type="text" class="form-control" name="new_client_name" id="new_client_name"
                    placeholder="Enter new client name">
            </div>

            <div class="form-group">
                <label for="service_type">Select Service:</label>
                <select class="form-control" name="service_id" id="service_type" required>
                    @foreach ($our_services as $service)
                        <option value="{{ $service->id }}" data-price="{{ $service->price }}"
                            data-duration="{{ $service->duration }}" data-duration-type="{{ $service->duration_type }}">
                            {{ $service->name }} - {{ $service->price }} NPR
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="price">Total Price:</label>
                <input type="number" class="form-control" name="price" id="price"
                    value="{{ old('price', $our_services[0]->price ?? 0) }}" step="0.01" min="0" required
                    readonly>
                <select name="currency" id="currency" class="form-control mt-2">
                    <option value="npr">NPR</option>
                    <option value="inr">INR</option>
                    <option value="usd">USD</option>
                    <option value="euro">Euro</option>
                    <option value="dhiram">DHIRAM</option>
                </select>
            </div>

            <div class="form-group">
                <label for="duration">Duration:</label>
                <input type="number" class="form-control" name="duration" id="duration" min="0" required>
            </div>

            <div class="form-group">
                <label for="duration_type">Duration Type:</label>
                <select name="duration_type" id="duration_type" class="form-control" required>
                    <option value="hours">Hours</option>
                    <option value="days">Days</option>
                    <option value="weeks">Weeks</option>
                    <option value="months">Months</option>
                </select>
            </div>

            <div class="form-group">
                <label for="start_date">Starting Date:</label>
                <input type="date" class="form-control" name="start_date" id="start_date">
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control mt-2">
                    <option value="pending">Pending</option>
                    <option value="progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>


            <div class="form-group">
                <label class="text-success" for="advance_amount">Advance Amount (if paid):</label>
                <input type="number" class="form-control" name="advance_amount" id="advance_amount"
                    placeholder="E.g., 9000 Rs" step="0.01" min="0">
            </div>



            <button type="submit" class="btn btn-primary mt-3">Save</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Update price, duration, and advance amount based on selected service
            $('#service_type').change(function() {
                var selected = $(this).find(':selected');
                var selectedPrice = parseFloat(selected.data('price'));
                var selectedDuration = selected.data('duration');
                var selectedDurationType = selected.data('duration-type');

                $('#price').val(selectedPrice);
                $('#duration').val(selectedDuration);
                $('#duration_type').val(selectedDurationType);
                $('#advance_amount').attr('max', selectedPrice);
            });

            // Trigger the change event on page load to set initial values
            $('#service_type').trigger('change');
            $('#newClient').hide();
            // Show/hide new client input based on client selection
            $('#client_select').change(function() {
                var selectedValue = $(this).val();
                if (selectedValue === 'new_client') {
                    $('#newClient').show();
                } else {
                    $('#newClient').hide();
                    $('#new_client_name').val(""); // Clear the new client name field
                }
            });

            // Trigger the change event on page load to set initial visibility
            $('#client_select').trigger('change');

            // Function to update the status based on start date
            function updateStatus() {
                var startDate = new Date($('#start_date').val());
                var today = new Date();
                var tomorrow = new Date();
                tomorrow.setDate(today.getDate() + 1);

                // Reset time portion for accurate comparison
                today.setHours(0, 0, 0, 0);
                tomorrow.setHours(0, 0, 0, 0);
                startDate.setHours(0, 0, 0, 0);

                // Determine status based on the date comparison
                var status = '';
                if (startDate <= today) {
                    status = 'progress'; // Date is today or before today
                } else if (startDate.getTime() === tomorrow.getTime()) {
                    status = 'pending'; // Date is tomorrow
                } else {
                    status = 'pending'; // Any other future date
                }

                // Set the selected option
                $('#status').val(status);
            }

            // Attach the event handler to the start_date input change event
            $('#start_date').change(function() {
                updateStatus();
            });

            // Trigger the updateStatus function on page load if the start_date is prefilled
            updateStatus();
        });
    </script>


@endsection
