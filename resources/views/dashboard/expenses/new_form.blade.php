@extends('layouts.main')
@section('header-right')
    @php
        $route = route('transactions.index');
        $routeName = ' transactions';
    @endphp
    <x-goto-button :route='$route' :name='$routeName' />
@endsection
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
            <!-- Expense Type -->
            <div class="form-group">
                <label for="source_type">Select Expense Type:</label>
                <select class="form-control select2" name="source_type" id="source_type" required>
                    <option value="">Select Expense Type</option>
                    <option value="utility"
                        {{ old('source_type', $expense->source_type ?? '') == 'utility' ? 'selected' : '' }}>Utility
                        Expense</option>
                    <option value="salary"
                        {{ old('source_type', $expense->source_type ?? '') == 'salary' ? 'selected' : '' }}>Salary
                    </option>
                    <option value="outsourcing"
                        {{ old('source_type', $expense->source_type ?? '') == 'outsourcing' ? 'selected' : '' }}>
                        Outsourcing Expense</option>
                    <option value="custom"
                        {{ old('source_type', $expense->source_type ?? '') == 'custom' ? 'selected' : '' }}>Other/Custom
                        Expense</option>
                </select>
            </div>
            <!-- Dropdown for Outsourcing Expense -->
            <div class="form-group fade" id="outsourcing_expense_group">
                <label for="client_service_id">Outsourcing Expense:</label>
                <select class="form-control select2" name="client_service_id" id="client_service_id">
                    <option value="">Select Client Service</option>
                    @foreach ($clientServices as $clientService)
                        <option value="{{ $clientService->id }}"
                            {{ old('client_service_id', $expense->client_service_id ?? '') == $clientService->id ? 'selected' : '' }}>
                            {{ $clientService->client->name }} - {{ $clientService->service->name }} (Amount:
                            {{ $clientService->service_amount }})
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Common Expense Source Input -->
            <div class="form-group">
                <label for="expense_source">Expense Source:</label>
                <input type="text" name="expense_source" id="expense_source" class="form-control"
                    placeholder="Enter expense source" value="{{ old('expense_source', $expense->expense_source ?? '') }}">
            </div>
            <!-- Transaction Date -->
            <div class="form-group">
                <label for="transaction_date">Transaction Date:</label>
                <input type="date" class="form-control" name="transaction_date" id="transaction_date"
                    value="{{ old('transaction_date', $expense->transaction_date ?? now()->format('Y-m-d')) }}" required>
            </div>
            <!-- Amount -->
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" step="0.01" class="form-control" name="amount" id="amount"
                    value="{{ old('amount', $expense->amount ?? '') }}" required>
            </div>
            <!-- Transaction Medium -->
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

            <!-- Remarks -->
            <div class="mb-3 row">
                <label for="remarks" class="col-sm-3 col-form-label">Remarks:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Remarks"
                        value="{{ old('remarks', $expense->remarks ?? '') }}">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mb-3 row">
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary">{{ isset($edit) ? 'Update' : 'Submit' }}</button>
                </div>
            </div>
    </div>
    </form>
    </div>





    <script>
        function toggleExpenseFields() {
            const selectedType = document.getElementById('source_type').value;
            const outsourcingGroup = document.getElementById('outsourcing_expense_group');

            // Add or remove the 'show' class based on selected expense type
            if (selectedType === 'outsourcing') {
                outsourcingGroup.classList.add('show');
                outsourcingGroup.classList.remove('fade');
            } else {
                outsourcingGroup.classList.remove('show');
                outsourcingGroup.classList.add('fade');
            }
        }

        // Run toggleExpenseFields on page load

        // Run toggleExpenseFields on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleExpenseFields();
        });

        // Run toggleExpenseFields on change of the expense type dropdown
        document.getElementById('source_type').addEventListener('change', toggleExpenseFields);
    </script>
@endsection
