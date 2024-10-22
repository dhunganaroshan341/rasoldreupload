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
                                    {{-- value="{{ isset($clientService) ? app\models\ClientService::find($clientService->client_id)->client->name : old('client_id') }}" --}} type="text" class="form-control" id="client_id"
                                    name="client_name" disabled>
                            </div>

                            <div class="form-group">
                                <label for="name">Service Name/specific:</label>
                                <!--
                                                                The input field will display the name of the existing client service if it exists.
                                                                If not, it will show the old input value submitted by the user (useful for validation errors).
                                                            -->
                                <input value="{{ isset($clientService) ? $clientService->name : old('name') }}"
                                    type="text" class="form-control" id="name" name="name" required
                                    placeholder="{{ // If there is an existing client service, show the client and service name as a placeholder.
                                        // Otherwise, provide a generic prompt for the user to enter the specific service name.
                                        isset($clientService)
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
                                            {{ isset($clientService)
                                                ? ($clientService->duration_type !== null
                                                    ? ($clientService->duration_type == 'days'
                                                        ? 'selected'
                                                        : '')
                                                    : ($clientService->service->duration_type == 'days'
                                                        ? 'selected'
                                                        : ''))
                                                : (old('duration_type') == 'days'
                                                    ? 'selected'
                                                    : '') }}>
                                            Days
                                        </option>
                                        <option value="weeks"
                                            {{ isset($clientService)
                                                ? ($clientService->duration_type !== null
                                                    ? ($clientService->duration_type == 'weeks'
                                                        ? 'selected'
                                                        : '')
                                                    : ($clientService->service->duration_type == 'weeks'
                                                        ? 'selected'
                                                        : ''))
                                                : (old('duration_type') == 'weeks'
                                                    ? 'selected'
                                                    : '') }}>
                                            Weeks
                                        </option>
                                        <option value="months"
                                            {{ isset($clientService)
                                                ? ($clientService->duration_type !== null
                                                    ? ($clientService->duration_type == 'months'
                                                        ? 'selected'
                                                        : '')
                                                    : ($clientService->service->duration_type == 'months'
                                                        ? 'selected'
                                                        : ''))
                                                : (old('duration_type') == 'months'
                                                    ? 'selected'
                                                    : '') }}>
                                            Months
                                        </option>
                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email_service">Email Service:</label>
                                <select class="form-control" id="email_service" name="email_service">
                                    <option value="yes"
                                        {{ isset($clientService) && $clientService->email_service == 'yes' ? 'selected' : '' }}>
                                        Yes</option>
                                    <option value="no"
                                        {{ isset($clientService) && $clientService->email_service == 'no' ? 'selected' : '' }}>
                                        No</option>
                                </select>
                                <div id="email_service_input"
                                    style="display: {{ isset($clientService) && $clientService->email_service == 'yes' ? 'block' : 'none' }};">
                                    <input type="text" class="form-control mt-2" id="email_service_value"
                                        name="email_service_value"
                                        value="{{ isset($clientService) ? $clientService->email_service_value : old('email_service_value') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="hosting_service">Hosting Service:</label>
                                <select class="form-control" id="hosting_service" name="hosting_service">
                                    <option value="yes"
                                        {{ isset($clientService) && $clientService->hosting_service == 'yes' ? 'selected' : '' }}>
                                        Yes</option>
                                    <option value="no"
                                        {{ isset($clientService) && $clientService->hosting_service == 'no' ? 'selected' : '' }}>
                                        No</option>
                                </select>
                                <div id="hosting_service_input"
                                    style="display: {{ isset($clientService) && $clientService->client->hosting_service == 'yes' ? 'block' : 'none' }};">
                                    <input type="text" class="form-control mt-2" id="hosting_service_value"
                                        name="hosting_service_value"
                                        value="{{ isset($clientService) ? $clientService->client->hosting_service : old('hosting_service_value') }}">
                                </div>
                            </div>

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
