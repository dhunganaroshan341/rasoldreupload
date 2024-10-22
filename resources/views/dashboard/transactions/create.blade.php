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
            @isset($income)
                @method('PUT')
            @endisset
            <div class="form-group">
                <label for="income_source">Income Source:</label>
                <input type="text" class="form-control" name="income_source" id="income_source"
                    value="{{ old('income_source', $income->income_source ?? '') }}" required>
            </div>
            {{-- <div class="form-group">
                <label for="income_source_id">Income Source ID:</label>
                <input type="number" class="form-control" name="income_source_id" id="income_source_id"
                    value="{{ old('income_source_id', $income->income_source_id ?? '') }}" required>
            </div> --}}
            <div class="form-group">
                <label for="transaction_date">Transaction Date:</label>
                <input type="date" class="form-control" name="transaction_date" id="transaction_date"
                    value="{{ old('transaction_date', $expense->transaction_date ?? date('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" step="0.01" class="form-control" name="amount" id="amount"
                    value="{{ old('amount', $income->amount ?? '') }}" required>
            </div>
            {{-- Additional fields for income or expense specific details --}}
            @yield('extra_fields')
            <button type="submit" class="btn btn-primary mt-3">Save</button>
        </form>
    </div>
@endsection
