@extends('layouts.main')
@section('header-right')
    @php
        $transactionRoute = route('transactions.index');
        $transactionRouteName = 'View Transaction';
        $expenseRoute = route('expenses.create');
        $expenseRouteName = 'Create Expense';
    @endphp
    <x-goto-button :route='$transactionRoute' :name='$transactionRouteName' />
    <x-goto-button :route='$expenseRoute' :name='$expenseRouteName' />
    {{-- <x-goto-button :route='$expeneRoute' :name='Create Expense' /> --}}
@endsection
@section('script')
    <script src="{{ asset('assets/plugins/select-picker/dist/picker.min.js') }}"></script>
@endsection
@section('header-left')
    @php
        $routeIdVariableForClient = 'client_service_id'; // This is a string
        $aHrefLabelForClientServiceEdit = 'Edit Client Service';
    @endphp

    {{-- Adding client's edit button for this client --}}
    <x-edit-this-button :label="$aHrefLabelForClientServiceEdit" :route="'ClientServices.edit'" :routeIdVariable="$routeIdVariableForClient" :routeId="$currentClientService->id" />
@endsection
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route($backRoute) }}" class="btn btn-outline-secondary">Back</a>
            {{-- Edit button logic --}}
            @if (isset($income))
                <!-- Add condition if $income is defined -->
                <x-edit-this-button :label="$aHrefLabelForClientServiceEdit" :route="'ClientServices.edit'" :routeIdVariable="'client_service_id'" :routeId="$currentClientService->id" />
            @endif
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="card-title">{{ $formTitle }}</h2>
                    <h4 class="text-info">
                        {{ isset($client_service_total_amount) && isset($client_service_remaining_amount) ? 'Total: ' . $client_service_total_amount . ' | Remaining: ' . $client_service_remaining_amount : '' }}
                    </h4>
                </div>
                {{-- Remainder amount section --}}
                <div class="mt-3">
                    <p class="text-muted">
                        @if (isset($client_service_remaining_amount))
                            Remaining amount: <strong>{{ $client_service_remaining_amount }}</strong>
                        @else
                            No remaining amount available.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ $formAction }}" method="POST" id="income_form">
            @csrf
            @isset($income)
                @method('PUT')
            @endisset

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="source_type">Source Type</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group form-check-inline">
                                <input type="radio" id="select_existing" name="source_type" value="existing"
                                    class="form-check-input"
                                    {{ old('source_type', $income->source_type ?? 'existing') === 'existing' ? 'checked' : '' }}>
                                <label for="select_existing" class="form-check-label">Select Existing Service</label>

                                @if (!isset($income))
                                    <!-- Only show this option if not in edit mode -->
                                    <input type="radio" id="add_new" name="source_type" value="new"
                                        class="form-check-input"
                                        {{ old('source_type', $income->source_type ?? 'existing') === 'new' ? 'checked' : '' }}>
                                    <label for="add_new" class="form-check-label">Add New Service</label>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown for existing client services -->
                    <div>
                        {{ isset($selectedClientServices) && $selectedClientService !== null ? $selectedClientService->service->name . ' - ' . $selectedClientService->amount . ' - ' . $selectedClientService->client->name : '' }}
                    </div>

                    <!-- Existing service dropdown -->
                    <div class="form-group" id="existing_service_dropdown"
                        style="display: {{ old('source_type', $income->source_type ?? 'existing') === 'existing' ? 'block' : 'none' }}">
                        <select class="form-control" name="income_source" id="income_source">
                            <option value="" disabled
                                {{ old('income_source_id', $income->income_source_id ?? '') ? '' : 'selected' }}>Select an
                                existing service</option>
                            @foreach ($clientServices as $clientService)
                                @if ($clientService->remaining_amount >= 0)
                                    <option value="{{ $clientService->id }}"
                                        {{ old('income_source_id', $income->income_source_id ?? '') == $clientService->id ? 'selected' : '' }}>
                                        {{ $clientService->client->name }} - {{ $clientService->service->name }} -
                                        {{ $clientService->amount ?? $clientService->service->price }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- New client and service fields -->
                    <div class="form-group" id="new_client_input"
                        style="display: {{ old('source_type', $income->source_type ?? 'existing') === 'new' ? 'block' : 'none' }}">
                        <label for="clients">Clients</label>
                        <select class="form-control" name="new_client_id" id="client_name">
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if (!isset($income))
                        <div class="form-group" id="service_select_input"
                            style="display: {{ old('source_type', $income->source_type ?? 'existing') === 'new' ? 'block' : 'none' }}">
                            <label for="service_select">Select Service</label>
                            <select class="form-control" name="new_service_id" id="service_select">
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}"
                                        {{ old('new_service_id', '') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} - {{ $service->price }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="new_service_input"
                            style="display: {{ old('source_type', $income->source_type ?? 'existing') === 'new' ? 'block' : 'none' }}">
                            <label for="new_service_name">Specify New Service</label>
                            <input type="text" class="form-control" name="new_service_name" id="new_service_name"
                                value="{{ old('new_service_name', '') }}" placeholder="Specify new client service">
                        </div>
                    @endif

                    <!-- Common Fields -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="transaction_date">Transaction Date</label>
                        </div>
                        <div class="col-md-9">
                            <input type="date" class="form-control" name="transaction_date" id="transaction_date"
                                value="{{ old('transaction_date', $income->transaction_date ?? now()->format('Y-m-d')) }}"
                                required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="amount">Amount</label>
                        </div>
                        <div class="col-md-9">
                            <input type="number" step="0.01" class="form-control" name="amount" id="amount"
                                value="{{ old('amount', $income->amount ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="medium">Transaction Medium</label>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" name="medium" id="medium" required>
                                <option value="cash"
                                    {{ old('medium', $income->medium ?? '') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="cheque"
                                    {{ old('medium', $income->medium ?? '') == 'cheque' ? 'selected' : '' }}>Cheque
                                </option>
                                <option value="mobile_transfer"
                                    {{ old('medium', $income->medium ?? '') == 'mobile_transfer' ? 'selected' : '' }}>
                                    Mobile Transfer</option>
                                <option value="other"
                                    {{ old('medium', $income->medium ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="remarks">Remarks</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="remarks" id="remarks"
                                placeholder="Remarks" value="{{ old('remarks', $income->remarks ?? '') }}">
                            <small id="helpId" class="form-text text-muted">Additional comments or information</small>
                        </div>
                    </div>

                    {{-- Additional fields for income-specific details --}}
                    @yield('extra_fields')

                    <button type="submit" class="btn btn-primary mt-3">{{ isset($edit) ? 'Update' : 'Submit' }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('footer_file')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize selectpicker with search capability
            const selectpickers = document.querySelectorAll('.selectpicker');
            selectpickers.forEach(function(selectpicker) {
                $(selectpicker).selectpicker({
                    liveSearch: true
                }); // Ensure jQuery is loaded for this to work
            });

            // Handle visibility based on selected source type
            const sourceTypeInputs = document.querySelectorAll('input[name="source_type"]');
            sourceTypeInputs.forEach(function(input) {
                input.addEventListener('change', function() {
                    const selectedValue = this.value;
                    document.getElementById('existing_service_dropdown').style.display =
                        selectedValue === 'existing' ? 'block' : 'none';
                    document.getElementById('new_client_input').style.display = selectedValue ===
                        'new' ? 'block' : 'none';
                    document.getElementById('service_select_input').style.display =
                        selectedValue === 'new' ? 'block' : 'none';
                    document.getElementById('new_service_input').style.display = selectedValue ===
                        'new' ? 'block' : 'none';
                });
            });
        });
    </script>
@endsection
