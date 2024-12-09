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

        <form action="{{ $formAction }}" method="POST" class="p-4 shadow rounded bg-white">
            @csrf
            @isset($expense)
                @method('PUT')
            @endisset

            <!-- Expense Type -->
            <div class="mb-3 row">
                <label for="source_type" class="col-sm-3 col-form-label">Expense Type:</label>
                <div class="col-sm-9">
                    <select class="form-control select2" name="source_type" id="source_type" required>
                        <option value="">Select Expense Type</option>
                        <option value="utility"
                            {{ old('source_type', $expense->source_type ?? '') == 'utility' ? 'selected' : '' }}>
                            Utility Expense
                        </option>
                        <option value="salary"
                            {{ old('source_type', $expense->source_type ?? '') == 'salary' ? 'selected' : '' }}>Salary
                        </option>
                        <option value="outsourcing"
                            {{ old('source_type', $expense->source_type ?? '') == 'outsourcing' ? 'selected' : '' }}>
                            Outsourcing Expense</option>
                        <option value="custom"
                            {{ old('source_type', $expense->source_type ?? '') == 'custom' ? 'selected' : '' }}>
                            Other/Custom Expense</option>
                    </select>
                </div>
            </div>

            <!-- Dropdown for Outsourcing Expense -->
            <div class="mb-3 row" id="outsourcing_expense_group" style="display: none;">
                <label for="client_service_id" class="col-sm-3 col-form-label">Outsourcing Expense:</label>
                <div class="col-sm-9">
                    <select class="form-control select2" name="client_service_id" id="client_service_id">
                        <option value="">Select Client Service</option>
                        @foreach ($clientServices as $clientService)
                            <option value="{{ $clientService->id }}"
                                {{ old('client_service_id', $expense->client_service_id ?? '') == $clientService->id ? 'selected' : '' }}>
                                {{ $clientService->client->name }} - {{ $clientService->service->name }} (Amount:
                                {{ $clientService->amount }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Expense Source -->
            <div class="mb-3 row">
                <label for="expense_source" class="col-sm-3 col-form-label">Expense Source:</label>
                <div class="col-sm-9">
                    <input type="text" name="expense_source" id="expense_source" class="form-control"
                        placeholder="Enter expense source"
                        value="{{ old('expense_source', $expense->expense_source ?? '') }}">
                </div>
            </div>

            <!-- Transaction Date -->
            <div class="mb-3 row">
                <label for="transaction_date" class="col-sm-3 col-form-label">Transaction Date:</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" name="transaction_date" id="transaction_date"
                        value="{{ old('transaction_date', $expense->transaction_date ?? now()->format('Y-m-d')) }}"
                        required>
                </div>
            </div>

            <!-- Amount -->
            <div class="mb-3 row">
                <label for="amount" class="col-sm-3 col-form-label">Amount:</label>
                <div class="col-sm-9">
                    <input type="number" step="0.01" class="form-control" name="amount" id="amount"
                        value="{{ old('amount', $expense->amount ?? '') }}" required>
                </div>
            </div>

            <!-- Transaction Medium -->
            <div class="mb-3 row">
                <label for="medium" class="col-sm-3 col-form-label">Transaction Medium:</label>
                <div class="col-sm-9">
                    <select class="form-control select2" name="medium" id="medium" required>
                        <option value="cash" {{ old('medium', $expense->medium ?? '') == 'cash' ? 'selected' : '' }}>
                            Cash</option>
                        <option value="cheque" {{ old('medium', $expense->medium ?? '') == 'cheque' ? 'selected' : '' }}>
                            Cheque
                        </option>
                        <option value="mobile_transfer"
                            {{ old('medium', $expense->medium ?? '') == 'mobile_transfer' ? 'selected' : '' }}>Mobile
                            Transfer</option>
                        <option value="other" {{ old('medium', $expense->medium ?? '') == 'other' ? 'selected' : '' }}>
                            Other</option>
                    </select>
                </div>
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
            <div class="row">
                <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary">{{ isset($edit) ? 'Update' : 'Submit' }}</button>
                </div>
            </div>
        </form>
    </div>


@endsection
@push('script-items')
    <script>
        function toggleExpenseFields() {
            const selectedType = document.getElementById('source_type').value;
            const outsourcingGroup = document.getElementById('outsourcing_expense_group');
            outsourcingGroup.style.display = (selectedType === 'outsourcing') ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleExpenseFields(); // Ensure the correct field is shown on load
            document.getElementById('source_type').addEventListener('change', toggleExpenseFields);
        });
    </script>
@endpush
