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
                    <div class="card-header">Create Client Service</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('createSingleClientService.store') }}">
                            @csrf

                            <!-- Client Selection -->
                            <div class="form-group">
                                <label for="client_id">Client</label>
                                <select class="form-control" id="client_id" name="client_id" required>
                                    <option value="">Select Client</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Service Selection -->
                            <div class="form-group">
                                <label for="service_id">Service</label>
                                <select class="form-control" id="service_id" name="service_id" required>
                                    <option value="">Select Service</option>
                                    @foreach ($ourServices as $service)
                                        <option value="{{ $service->id }}">
                                            {{ $service->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Service Name/specific -->
                            <div class="form-group">
                                <label for="name">Service Name/specific:</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    placeholder="Enter specific name for client/service">
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>

                            <!-- Amount -->
                            <div class="form-group">
                                <label for="amount">Amount:</label>
                                <input min="0" inputmode="numeric" type="number" class="form-control" id="amount"
                                    name="amount" required>
                            </div>

                            <!-- Duration -->
                            <div class="form-group">
                                <label for="duration">Duration:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="duration" name="duration" required>
                                    <select class="custom-select" id="duration_type" name="duration_type">
                                        <option value="days">Days</option>
                                        <option value="weeks">Weeks</option>
                                        <option value="months">Months</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Billing Period Frequency Field -->
                            <div class="form-group">
                                <label for="billing_period_frequency">Billing Period Frequency:</label>
                                <select class="custom-select" id="billing_period_frequency" name="billing_period_frequency"
                                    required>
                                    <option value="one-time">One-Time</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="semi-annually">Semi-Annually</option>
                                    <option value="annually">Annually</option>
                                </select>
                            </div>
                            <!-- Add Billing Start Date -->
                            <div class="form-group">
                                <label for="billing_start_date">Billing Start:</label>
                                <input type="date" id="billing_start_date" name="billing_start_date"
                                    class="form-control">
                            </div>
                            <!-- Advance Paid Field -->
                            <div class="form-group">
                                <label for="advance_paid">Advance Paid:</label>
                                <input value="0" type="number" step="0.01" class="form-control" id="advance_paid"
                                    name="advance_paid" placeholder="Enter advance paid amount" required>
                            </div>

                            <!-- Optional Service Fields (Email and Hosting) -->
                            <div class="form-group">
                                <label for="email_service">Email Service:</label>
                                <select class="form-control" id="email_service" name="email_service">
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                                <div id="email_service_input" style="display: none;">
                                    <input type="text" class="form-control mt-2" id="email_service_value"
                                        name="email_service_value">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="hosting_service">Hosting Service:</label>
                                <select class="form-control" id="hosting_service" name="hosting_service">
                                    <option value="no">No</option>
                                    <option value="yes">Yes</option>
                                </select>
                                <div id="hosting_service_input" style="display: none;">
                                    <input type="text" class="form-control mt-2" id="hosting_service_value"
                                        name="hosting_service_value">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Create Client Service</button>
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

            emailServiceSelect.addEventListener('change', function() {
                emailServiceInput.style.display = this.value === 'yes' ? 'block' : 'none';
            });

            hostingServiceSelect.addEventListener('change', function() {
                hostingServiceInput.style.display = this.value === 'yes' ? 'block' : 'none';
            });
        });
    </script>
@endsection