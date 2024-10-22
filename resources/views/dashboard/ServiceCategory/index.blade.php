@extends('layouts.main')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <h2>Income and Expense Records</h2>
            <a href="{{ route('income_expense.create') }}" class="btn btn-primary">Add Record</a>
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">Category</th>
                    <th colspan="3" class="text-center">Income</th>
                    <th colspan="3" class="text-center">Expense</th>
                </tr>
                <tr>
                    <th>Amount</th>
                    <th>Source</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Source</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $record)
                    <tr>
                        <td>{{ $record->type === 'income' ? 'Income' : 'Expense' }}</td>
                        @if ($record->type === 'income')
                            <td>{{ $record->amount }}</td>
                            <td>{{ $record->source }}</td>
                            <td>{{ $record->transaction_date }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ $record->amount }}</td>
                            <td>{{ $record->source }}</td>
                            <td>{{ $record->transaction_date }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
