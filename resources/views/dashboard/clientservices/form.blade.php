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

                            <div class="row mb-3">
                                <!-- Service -->
                                <div class="col-md-6">
                                    <label for="service_name" class="form-label">Service</label>
                                    <input
                                        value="{{ isset($clientService) ? $clientService->service->name : old('service_name') }}"
                                        type="text" class="form-control" id="service_id_display"
                                        name="service_id_display" disabled>
                                    <input type="hidden" id="service_id" name="service_id"
                                        value="{{ isset($clientService) ? $clientService->service->id : old('service_id') }}">
                                </div>
                                <!-- Client -->
                                <div class="col-md-6">
                                    <label for="client_id" class="form-label">Client</label>
                                    <input value="{{ isset($clientService) ? $clientService->client->name : '' }}"
                                        type="text" class="form-control" id="client_id_display" name="client_id_display"
                                        disabled>
                                    <input type="hidden" id="client_id" name="client_id"
                                        value="{{ isset($clientService) ? $clientService->client->id : old('client_id') }}">
                                </div>
                            </div>



                            <div class="row mb-3">
                                <!-- Custom Name -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Custom Name</label>
                                    <input value="{{ isset($clientService) ? $clientService->name : old('name') }}"
                                        type="text" class="form-control" id="name" name="name" required
                                        placeholder="{{ isset($clientService)
                                            ? $clientService->client->name . ' - ' . $clientService->service->name . ' premium'
                                            : 'Eg: Enter specific name for client/service' }}">
                                </div>
                                <!-- Billing Start -->
                                <div class="col-md-6">
                                    <label for="billing_start_date" class="form-label">Billing Start</label>
                                    <input type="date" id="billing_start_date" name="billing_start_date"
                                        class="form-control"
                                        value="{{ old('billing_start_date', isset($clientService) ? $clientService->billing_start_date : '') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <!-- Amount -->
                                <div class="col-md-6">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input min="0" inputmode="numeric"
                                        value="{{ isset($clientService) ? $clientService->amount ?? $clientService->service->price : old('amount') }}"
                                        type="number" class="form-control" id="amount" name="amount" required>
                                </div>
                                <!-- Duration -->
                                <div class="col-md-6">
                                    <label for="duration" class="form-label">Duration</label>
                                    <div class="input-group">
                                        <input
                                            value="{{ isset($clientService) ? $clientService->duration ?? $clientService->service->duration : old('duration') }}"
                                            type="number" class="form-control" id="duration" name="duration" required>
                                        <select class="form-select" id="duration_type" name="duration_type">
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
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <!-- Billing Period Frequency -->
                                    <div class="row mb-3">
                                        <label for="billing_period_frequency"
                                            class="col-md-4 col-form-label text-md-right">Billing Cycle</label>
                                        <div class="col-md-8">
                                            <select class="form-select" id="billing_period_frequency"
                                                name="billing_period_frequency" required>
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
                                    </div>
                                    <!-- Hosting Service -->
                                    <div class="row mb-3">
                                        <label for="hosting_service" class="col-md-4 col-form-label text-md-right">Hosting
                                            Service</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="hosting_service"
                                                name="hosting_service"
                                                value="{{ old('hosting_service', isset($clientService) ? $clientService->hosting_service : '') }}"
                                                placeholder="Eg: Hosting details">
                                        </div>
                                    </div>
                                    <!-- Email Service -->
                                    <div class="row mb-3">
                                        <label for="email_service" class="col-md-4 col-form-label text-md-right">Email
                                            Service</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="email_service"
                                                name="email_service"
                                                value="{{ old('email_service', isset($clientService) ? $clientService->email_service : '') }}"
                                                placeholder="Eg: Email service details">
                                        </div>
                                    </div>
                                </div>
                                <!-- Description -->
                                <div class="col-md-6">
                                    <label for="description" class="col-form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="9">{{ isset($clientService) ? $clientService->description : old('description') }}</textarea>
                                </div>
                            </div>
                            {{-- status switch  --}}
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="flexSwitchCheckChecked" checked>
                                <label class="form-check-label" for="flexSwitchCheckChecked">status</label>
                            </div>
                            <div class="mb-3 row justify-content-end">
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-primary">
                                        {{ isset($clientService) ? 'Update' : 'Create' }} Client Service
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




@push('script-items')
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
@endpush
