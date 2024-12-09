@extends('layouts.main')
@section('header-left-title')
    Edit Income
@endsection
@section('header-right')
    <a href="{{ route('transactions.index') }}" class="badge bg-sidebar text-light mr-3 mt-4">Transactions</a>
@endsection
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
                                    {{ old('source_type', $income->source_type ?? 'existing') === 'existing' ? 'checked' : 'checked' }}>
                                <label class="form-check-label" for="select_existing">Select Existing Service</label>
                            </div>
                        </div>
                    </div>

                    <!-- Existing Service Dropdown -->
                    <div class="row mb-3" id="existing_service_dropdown">
                        <label for="existing_service" class="col-md-2 col-form-label text-md-end">Existing Service</label>
                        <div class="col-md-7">
                            <select class="form-select" id="existing_service" name="existing_service">
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
                        <button type="submit"
                            class="btn bg-sidebar text-light">{{ isset($edit) ? 'Update' : 'Submit' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('script-items')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const existingServiceDropdown = document.getElementById('existing_service_dropdown');
            const sourceTypeRadios = document.querySelectorAll('input[name="source_type"]');

            // Function to toggle visibility (it will only show if 'existing' is selected)
            const toggleDropdownVisibility = () => {
                const selectedValue = document.querySelector('input[name="source_type"]:checked').value;
                existingServiceDropdown.style.display = selectedValue === 'existing' ? 'block' : 'none';
            };

            // Initialize on page load (no need to check for 'new' anymore, as it's removed)
            toggleDropdownVisibility();

            // Add event listeners to radio buttons if any changes occur (not needed now due to removal of 'new' option)
            sourceTypeRadios.forEach(radio => {
                radio.addEventListener('change', toggleDropdownVisibility);
            });
        });
    </script>
@endpush
