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
                        <label for="medium" class="col-md-3 col-form-label text-md-end">Transaction Medium</label>
                        <div class="col-md-9">
                            <select class="form-control" id="medium" name="medium" required>
                                <option value="cash"
                                    {{ old('medium', $income->medium ?? '') === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option id = "cheque" value="cheque"
                                    {{ old('medium', $income->medium ?? '') === 'cheque' ? 'selected' : '' }}>Cheque
                                </option>
                                <option value="mobile_transfer"
                                    {{ old('medium', $income->medium ?? '') === 'mobile_transfer' ? 'selected' : '' }}>
                                    Mobile Transfer</option>
                                <option value="other"
                                    {{ old('medium', $income->medium ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                    {{-- if they have medium no as well --}}
                    <!-- Medium Number Input (Initially Hidden) -->




                    <div style="display: none" class="row mb-3" id = "">
                        <label for="remarks" class="col-md-3 col-form-label text-md-end">Enter Medium No</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="medium_no" id="medium_no"
                                value="{{ old('remarks', $income->medium_no ?? '') }}"
                                placeholder="medium no, Eg:check no">
                            <small class="form-text text-muted">Enter the cheque number or relevant medium number.</small>
                        </div>
                    </div>
                    {{-- end medium no eg:cheque no mobilebanking transaction no --}}

                    <div class="row mb-3" id = "">
                        <label for="remarks" class="col-md-3 col-form-label text-md-end">Remarks</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="remarks" name="remarks"
                                value="{{ old('remarks', $income->remarks ?? '') }}" placeholder="Additional comments">
                            <small class="form-text text-muted">Provide any additional information.</small>
                        </div>
                    </div>

                    {{-- Additional fields for income-specific details --}}
                    @yield('extra_fields')

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
            // Initialize selectpicker with search capability
            const selectpickers = document.querySelectorAll('.selectpicker');
            selectpickers.forEach(function(selectpicker) {
                $(selectpicker).selectpicker({
                    liveSearch: true
                }); // Ensure jQuery is loaded for this to work
            });
            //    handle if it is cheque
            const mediumDropdown = document.getElementById('medium'); // Select dropdown
            const mediumNoContainer = document.getElementById(
                'medium_no_container'); // Container for medium number input

            const toggleMediumNo = () => {
                // Show or hide based on the selected value
                mediumNoContainer.style.display = mediumDropdown.value === 'cheque' ? 'block' : 'none';
            };

            // Initialize on page load (preserve state if editing)
            toggleMediumNo();

            // Add change event listener to the dropdown
            mediumDropdown.addEventListener('change', toggleMediumNo);
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
