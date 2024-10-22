@extends('layouts.main')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <h2>{{ $formTitle }}</h2>
            <a href="{{ route($backRoute) }}" class="btn btn-secondary">Back</a>
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

        <form action="{{ $formAction }}" method="POST">
            @csrf
            @isset($expense)
                @method('PUT')
            @endisset

            <div class="form-group">
                <label for="expense_type">Select Expense Type:</label>
                <select class="form-control select2" name="expense_type" id="expense_type" required>
                    <option value="">Select Expense Type</option>
                    <option value="utility">Utility Expense</option>
                    <option value="salary">Salary</option>
                    <option value="outsourcing"
                        {{ old('expense_type', $expense->expense_type ?? '') == 'outsourcing' ? 'selected' : '' }}>
                        Outsourcing Expense</option>
                    <option value="custom"
                        {{ old('expense_type', $expense->expense_type ?? '') == 'custom' ? 'selected' : '' }}>Other/Custom
                        Expense</option>
                </select>
            </div>

            <!-- Dropdown for Outsourcing Expense -->
            <div class="form-group" id="outsourcing_expense_group" style="display:none;">
                <label for="outsourcing_expense">OutSourcing Expense:</label>
                <select class="form-control select2" name="outsourcing_expense" id="outsourcing_expense">
                    @foreach ($clientServices as $clientService)
                        <option value="{{ $clientService->id }}">
                            {{ $clientService->client->name }} - {{ $clientService->service->name }} (Amount:
                            {{ $clientService->service_amount }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Custom Expense Input -->
            <div class="form-group" id="custom_expense_group" style="display:none;">
                <label for="custom_expense">Custom Expense:</label>
                <input type="text" name="custom_expense" id="custom_expense" class="form-control"
                    placeholder="Enter custom expense" value="{{ old('custom_expense', $expense->custom_expense ?? '') }}">
            </div>

            <div class="form-group">
                <label for="transaction_date">Transaction Date:</label>
                <input type="date" class="form-control" name="transaction_date" id="transaction_date"
                    value="{{ old('transaction_date', $expense->transaction_date ?? now()->format('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" step="0.01" class="form-control" name="amount" id="amount"
                    value="{{ old('amount', $expense->amount ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="medium">Transaction Medium:</label>
                <select class="form-control select2" name="medium" id="medium" required>
                    <option value="cash" {{ old('medium', $expense->medium ?? '') == 'cash' ? 'selected' : '' }}>Cash
                    </option>
                    <option value="cheque" {{ old('medium', $expense->medium ?? '') == 'cheque' ? 'selected' : '' }}>Cheque
                    </option>
                    <option value="mobile_transfer"
                        {{ old('medium', $expense->medium ?? '') == 'mobile_transfer' ? 'selected' : '' }}>Mobile Transfer
                    </option>
                    <option value="other" {{ old('medium', $expense->medium ?? '') == 'other' ? 'selected' : '' }}>Other
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="remarks">Remarks:</label>
                <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Remarks"
                    value="{{ old('remarks', $expense->remarks ?? '') }}">
            </div>

            <button type="submit" class="btn btn-primary mt-3">{{ isset($edit) ? 'Update' : 'Submit' }}</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for the dropdowns
            $('.select2').select2();

            // Show/Hide sections based on expense type
            function toggleExpenseFields() {
                var selectedType = $('#expense_type').val();

                if (selectedType === 'outsourcing') {
                    $('#outsourcing_expense_group').show();
                    $('#custom_expense_group').hide();
                } else if (selectedType === 'custom') {
                    $('#custom_expense_group').show();
                    $('#outsourcing_expense_group').hide();
                } else {
                    $('#outsourcing_expense_group').hide();
                    $('#custom_expense_group').hide();
                }
            }

            // Call the function on page load to handle old inputs or pre-selected values
            toggleExpenseFields();

            // Call the function on expense type change
            $('#expense_type').on('change', toggleExpenseFields);
        });
    </script>
@endsection
