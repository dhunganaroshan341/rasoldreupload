@extends('layouts.main')
@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="mb-4 text-center">{{ $formTitle }}</h2>
                <form action="{{ $formAction }}" method="POST" id="income_form">
                    @csrf
                    @isset($income)
                        @method('PUT')
                    @endisset

                    <div class="row mb-3">
                        <label for="source_type" class="col-md-3 col-form-label text-md-end">Source Type</label>
                        <div class="col-md-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="select_existing" name="source_type"
                                    value="existing"
                                    {{ old('source_type', $income->source_type ?? 'existing') === 'existing' ? 'checked' : '' }}>
                                <label class="form-check-label" for="select_existing">Select Existing Service</label>
                            </div>
                            @if (!isset($income))
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="add_new" name="source_type"
                                        value="new"
                                        {{ old('source_type', $income->source_type ?? 'existing') === 'new' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="add_new">Add New Service</label>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Existing Service Dropdown -->
                    <div class="row mb-3" id="existing_service_dropdown" style="display: none;">
                        <label for="existing_service" class="col-md-3 col-form-label text-md-end">Existing Service</label>
                        <div class="col-md-9">
                            <select class="form-control" id="existing_service" name="existing_service">
                                <option value="">-- Select Service --</option>
                                @foreach ($clientServices as $service)
                                    <option value="{{ $service->id }}"
                                        {{ old('existing_service') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} - {{ $service->amount }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="transaction_date" class="col-md-3 col-form-label text-md-end">Transaction Date</label>
                        <div class="col-md-9">
                            <input type="date" class="form-control" id="transaction_date" name="transaction_date"
                                value="{{ old('transaction_date', $income->transaction_date ?? now()->format('Y-m-d')) }}"
                                required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="amount" class="col-md-3 col-form-label text-md-end">Amount</label>
                        <div class="col-md-9">
                            <input type="number" class="form-control" id="amount" name="amount" step="0.01"
                                value="{{ old('amount', $income->amount ?? '') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="remarks" class="col-md-3 col-form-label text-md-end">Remarks</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="remarks" name="remarks"
                                value="{{ old('remarks', $income->remarks ?? '') }}" placeholder="Additional comments">
                            <small class="form-text text-muted">Provide any additional information.</small>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">{{ isset($edit) ? 'Update' : 'Submit' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('footer_file')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const existingServiceDropdown = document.getElementById('existing_service_dropdown');
            const sourceTypeRadios = document.querySelectorAll('input[name="source_type"]');

            // Function to toggle visibility
            const toggleDropdownVisibility = () => {
                const selectedValue = document.querySelector('input[name="source_type"]:checked').value;
                existingServiceDropdown.style.display = selectedValue === 'existing' ? 'block' : 'none';
            };

            // Initialize on page load
            toggleDropdownVisibility();

            // Add event listeners to radio buttons
            sourceTypeRadios.forEach(radio => {
                radio.addEventListener('change', toggleDropdownVisibility);
            });
        });
    </script>
@endsection
