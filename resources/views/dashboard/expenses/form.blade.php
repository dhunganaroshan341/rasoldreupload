@extends('layouts.main')
@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <h2>Add {{ $formTitle }}</h2>
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
                <label for="expense_source">Expense Source:</label>
                <input type="text" class="form-control" name="expense_source" id="expense_source"
                    value="{{ old('expense_source', $expense->expense_source ?? '') }}" required>
            </div>
            {{-- <div class="form-group">
                <label for="expense_source_id">Expense Source ID:</label>
                <input type="number" class="form-control" name="expense_source_id" id="expense_source_id"
                    value="{{ old('expense_source_id', $expense->expense_source_id ?? '') }}" required>
            </div> --}}
            <div class="form-group">
                <label for="transaction_date">Transaction Date:</label>
                <input type="date" class="form-control" name="transaction_date" id="transaction_date"
                    value="{{ old('transaction_date', $expense->transaction_date ?? date('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" step="0.01" class="form-control" name="amount" id="amount"
                    value="{{ old('amount', $expense->amount ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="remarks">Remarks</label>
                <input type="text" class="form-control" name="remarks" id="remarks" aria-describedby="helpId"
                    placeholder="remarks">
                <small id="helpId" class="form-text text-muted">Help text</small>
            </div>
            {{-- Additional fields for expense or expense specific details --}}
            {{-- @yield('extra_fields') --}}
            <button type="submit" class="btn btn-primary mt-3">Save</button>
        </form>
    </div>
    {{-- @extends('layouts.main')

@section('content') --}}
    <!-- Modal Structure -->
@endsection
