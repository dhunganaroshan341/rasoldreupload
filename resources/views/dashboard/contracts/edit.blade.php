@extends('layouts.main')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>{{ isset($contract) ? 'Edit Contract' : 'Create Contract' }}</h2>
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

        <form action="{{ isset($contract) ? route('contracts.update', $contract->id) : route('contracts.store') }}"
            method="POST">
            @csrf
            @if (isset($contract))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="contract_name">Contract Name:</label>
                <input type="text" class="form-control" name="contract_name" id="contract_name"
                    placeholder="Name of the project/contract"
                    value="{{ old('contract_name', isset($contract) ? $contract->name : '') }}" required>
            </div>
            <div class="form-group">
                <label for="client_select">Select Client:</label>
                <select class="form-control" name="client_id" id="client_select" required>
                    <option value="new_client">Add New Client</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}"
                            {{ old('client_id', isset($contract) ? $contract->client_id : '') == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="service_type">Select Service:</label>
                <select class="form-control" name="service_type" id="service_type" required>
                    @foreach ($our_services as $service)
                        <option value="{{ $service->id }}" data-price="{{ $service->price }}"
                            data-duration="{{ $service->duration }}" data-duration-type="{{ $service->duration_type }}"
                            {{ old('service_type', isset($contract) ? $contract->service_type : '') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} - {{ $service->price }} NPR
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" id="new_client_type_input" style="display: none;">
                <label for="new_client_name">New Client Name:</label>
                <input type="text" class="form-control" name="new_client_name" id="new_client_name"
                    placeholder="Enter new client name"
                    value="{{ old('new_client_name', isset($contract) ? $contract->new_client_name : '') }}">
            </div>

            <div class="form-group">
                <label for="price">Total Price:</label>
                <input type="number" class="form-control" name="price" id="price"
                    value="{{ old('price', isset($contract) ? $contract->price : $our_services[0]->price) }}"
                    min="0" required>
                <select name="currency" id="currency" class="form-control mt-2">
                    <option value="npr"
                        {{ old('currency', isset($contract) ? $contract->currency : '') == 'npr' ? 'selected' : '' }}>NPR
                    </option>
                    <option value="inr"
                        {{ old('currency', isset($contract) ? $contract->currency : '') == 'inr' ? 'selected' : '' }}>INR
                    </option>
                    <option value="dollar"
                        {{ old('currency', isset($contract) ? $contract->currency : '') == 'dollar' ? 'selected' : '' }}>$
                    </option>
                    <option value="euro"
                        {{ old('currency', isset($contract) ? $contract->currency : '') == 'euro' ? 'selected' : '' }}>Euro
                    </option>
                    <option value="dhiram"
                        {{ old('currency', isset($contract) ? $contract->currency : '') == 'dhiram' ? 'selected' : '' }}>
                        DHIRAM</option>
                </select>
            </div>

            <div class="form-group">
                <label class="text-success" for="advance_amount">Advance Amount (if paid):</label>
                <input type="number" placeholder="E.g., 9000 Rs" name="advance_amount" id="advance_amount"
                    class="form-control" step="0.01" min="1"
                    value="{{ old('advance_amount', isset($contract) ? $contract->advance_amount : '') }}" required>
            </div>

            <div class="form-group">
                <label for="start_date">Starting Date:</label>
                <input type="date" class="form-control" name="start_date" id="start_date"
                    value="{{ old('start_date', isset($contract) ? $contract->start_date : '') }}">
            </div>

            <div class="form-group">
                <label for="duration">Duration:</label>
                <input type="number" class="form-control" name="duration" id="duration"
                    value="{{ old('duration', isset($contract) ? $contract->duration : '') }}" required>
                <select name="duration_type" id="duration_type" class="form-control mt-2">
                    <option value="hours"
                        {{ old('duration_type', isset($contract) ? $contract->duration_type : '') == 'hours' ? 'selected' : '' }}>
                        Hours</option>
                    <option value="days"
                        {{ old('duration_type', isset($contract) ? $contract->duration_type : '') == 'days' ? 'selected' : '' }}>
                        Days</option>
                    <option value="weeks"
                        {{ old('duration_type', isset($contract) ? $contract->duration_type : '') == 'weeks' ? 'selected' : '' }}>
                        Weeks</option>
                    <option value="months"
                        {{ old('duration_type', isset($contract) ? $contract->duration_type : '') == 'months' ? 'selected' : '' }}>
                        Months</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">{{ isset($contract) ? 'Update' : 'Save' }}</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to update price, duration, and duration type based on selected service
            $('#service_type').change(function() {
                var selectedPrice = parseFloat($(this).find(':selected').data('price'));
                var selectedDuration = $(this).find(':selected').data('duration');
                var selectedDurationType = $(this).find(':selected').data('duration-type');

                $('#price').val(selectedPrice); // Update price input field
                $('#duration').val(selectedDuration); // Update duration input field
                $('#duration_type').val(selectedDurationType); // Update duration type select field

                $('#advance_amount').attr('max',
                    selectedPrice); // Update max attribute for advance amount if needed
            });

            // Trigger change event on page load to initialize price, duration, and duration type
            $('#service_type').trigger('change');

            // Toggle visibility of new client input based on selection
            $('#client_select').change(function() {
                if ($(this).val() === 'new_client') {
                    $('#new_client_type_input').show();
                    $('#new_client').val("select from Existing Clients"); // Adjust as needed
                } else {
                    $('#new_client_type_input').hide();
                }
            });

            // Trigger change event to set initial visibility of new client input
            $('#client_select').trigger('change');
        });
    </script>
@endsection
