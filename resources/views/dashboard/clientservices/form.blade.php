@extends('layouts.main')

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ isset($clientService) ? 'Edit' : 'Create' }} Client Service</div>

                    <div class="card-body">
                        <form method="POST"
                            action="{{ isset($clientService) ? route('ClientServices.update', $clientService->id) : route('ClientServices.store') }}">
                            @csrf
                            @if (isset($clientService))
                                @method('PUT')
                            @endif

                            <div class="form-group">
                                <label for="service_id">Service</label>
                                <input
                                    value="{{ isset($clientService) ? $clientService->service->name : old('service_name') }}"
                                    type="text" class="form-control" id="service_name" name="service_name" disabled>
                            </div>
                            <input type="hidden" name="client_id" value="{{ $clientService->client_id }}">
                            <input type="hidden" name="service_id" value="{{ $clientService->service_id }}">
                            <div class="form-group">
                                <label for="client_id">Client</label>
                                <input value="{{ isset($clientService) ? $clientService->client->name : '' }}"
                                    type="text" class="form-control" id="client_id" name="client_name" disabled>
                            </div>

                            <div class="form-group">
                                <label for="name">Service Name/specific:</label>
                                <input value="{{ isset($clientService) ? $clientService->name : old('name') }}"
                                    type="text" class="form-control" id="name" name="name" required
                                    placeholder="{{ isset($clientService)
                                        ? $clientService->client->name . ' - ' . $clientService->service->name . ' premium'
                                        : 'Eg: Enter specific name for client/service' }}">
                            </div>

                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description">{{ isset($clientService) ? $clientService->description : old('description') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="amount">Amount:</label>
                                <input min="0" inputmode="numeric"
                                    value="{{ isset($clientService) ? ($clientService->amount !== null ? $clientService->amount : $clientService->service->price) : old('amount') }}"
                                    type="number" class="form-control" id="amount" name="amount" required>
                            </div>

                            <div class="form-group">
                                <label for="duration">Duration:</label>
                                <div class="input-group">
                                    <input
                                        value="{{ isset($clientService) ? ($clientService->duration !== null ? $clientService->duration : $clientService->service->duration) : old('duration') }}"
                                        type="number" class="form-control" id="duration" name="duration" required>

                                    <select class="custom-select" id="duration_type" name="duration_type">
                                        <option value="days"
                                            {{ isset($clientService) && $clientService->duration_type == 'days' ? 'selected' : '' }}>
                                            Days</option>
                                        <option value="weeks"
                                            {{ isset($clientService) && $clientService->duration_type == 'weeks' ? 'selected' : '' }}>
                                            Weeks</option>
                                        <option value="months"
                                            {{ isset($clientService) && $clientService->duration_type == 'months' ? 'selected' : '' }}>
                                            Months</option>
                                    </select>

                                </div>
                            </div>

                            <!-- Billing Period Frequency Field -->
                            <div class="form-group">
                                <label for="billing_period_frequency">Billing Period Frequency:</label>
                                <select class="custom-select" id="billing_period_frequency" name="billing_period_frequency"
                                    required>
                                    <option value="one-time"
                                        {{ isset($clientService) && $clientService->billing_period_frequency == 'one-time' ? 'selected' : '' }}>
                                        One-Time</option>
                                    <option value="monthly"
                                        {{ isset($clientService) && $clientService->billing_period_frequency == 'monthly' ? 'selected' : '' }}>
                                        Monthly</option>
                                    <option value="quarterly"
                                        {{ isset($clientService) && $clientService->billing_period_frequency == 'quarterly' ? 'selected' : '' }}>
                                        Quarterly</option>
                                    <option value="semi-annually"
                                        {{ isset($clientService) && $clientService->billing_period_frequency == 'semi-annually' ? 'selected' : '' }}>
                                        Semi-Annually</option>
                                    <option value="annually"
                                        {{ isset($clientService) && $clientService->billing_period_frequency == 'annually' ? 'selected' : '' }}>
                                        Annually</option>
                                </select>
                            </div>

                            <!-- Advance Paid Field -->
                            <div class="form-group">
                                <label for="advance_paid">Advance Paid:</label>
                                <input
                                    value="{{ isset($clientService) ? $clientService->advance_paid : old('advance_paid', 0) }}"
                                    type="number" step="0.01" class="form-control" id="advance_paid" name="advance_paid"
                                    placeholder="Enter advance paid amount" required>
                            </div>
                            <!-- Email Service -->
                            @if (
                                $clientService->service->name == 'Web Development' ||
                                    $clientService->service->name == 'web developement' ||
                                    $clientService->service->name == 'Digital Marketing')
                                <div class="form-group">
                                    <label for="email_service">Email Service:</label>
                                    <select class="form-control" id="email_service" name="email_service">
                                        <option value="no"
                                            {{ isset($clientService) && $clientService->email_service == 'no' ? 'selected' : '' }}>
                                            No
                                        </option>
                                        <option value="yes"
                                            {{ isset($clientService) && $clientService->email_service == 'yes' ? 'selected' : '' }}>
                                            Yes
                                        </option>
                                    </select>
                                    <div id="email_service_input"
                                        style="display: {{ isset($clientService) && $clientService->email_service == 'yes' ? 'block' : 'none' }};">
                                        <input type="text" class="form-control mt-2" id="email_service_value"
                                            name="email_service_value"
                                            value="{{ isset($clientService) ? $clientService->email_service_value : old('email_service_value') }}">
                                    </div>
                                </div>
                            @endif

                            <!-- Hosting Service -->
                            @if (
                                $clientService->service->name == 'Web Development' ||
                                    $clientService->service->name == 'web developement' ||
                                    $clientService->service->name == 'Digital Marketing')
                                <div class="form-group">
                                    <label for="hosting_service">Hosting Service:</label>
                                    <select class="form-control" id="hosting_service" name="hosting_service">
                                        <option value="no"
                                            {{ isset($clientService) && $clientService->hosting_service == 'no' ? 'selected' : '' }}>
                                            No
                                        </option>
                                        <option value="yes"
                                            {{ isset($clientService) && $clientService->hosting_service == 'yes' ? 'selected' : '' }}>
                                            Yes
                                        </option>
                                    </select>
                                    <div id="hosting_service_input"
                                        style="display: {{ isset($clientService) && $clientService->hosting_service == 'yes' ? 'block' : 'none' }};">
                                        <input type="text" class="form-control mt-2" id="hosting_service_value"
                                            name="hosting_service_value"
                                            value="{{ isset($clientService) ? $clientService->hosting_service_value : old('hosting_service_value') }}">
                                    </div>
                                </div>
                            @endif


                            <button type="submit"
                                class="btn btn-primary">{{ isset($clientService) ? 'Update' : 'Create' }} Client
                                Service</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailServiceSelect = document.getElementById('email_service');
            const hostingServiceSelect = document.getElementById('hosting_service');
            const emailServiceInput = document.getElementById('email_service_input');
            const hostingServiceInput = document.getElementById('hosting_service_input');

            function toggleServiceFields() {
                emailServiceInput.style.display = emailServiceSelect.value === 'yes' ? 'block' : 'none';
                hostingServiceInput.style.display = hostingServiceSelect.value === 'yes' ? 'block' : 'none';
            }

            // Initial check
            toggleServiceFields();

            // Add event listeners to the select fields
            emailServiceSelect.addEventListener('change', toggleServiceFields);
            hostingServiceSelect.addEventListener('change', toggleServiceFields);
        });
    </script>
@endsection
