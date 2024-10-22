<table class="table table-bordered">
    <thead class="bg-light">
        <tr>
            <th>Date</th>
            <th>Amount</th>
            <th>Source</th>
            <th>Medium</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($paginatedTransactions as $transaction)
            <tr class="hover-item position-relative">
                <td>{{ $transaction['transaction_date'] }}</td>
                <td>{{ $transaction['amount'] }}</td>
                <td>{{ $transaction['source'] }}</td>
                <td>{{ $transaction['medium'] }}</td>
                <td>{{ $transaction['type'] }}</td>
                <td>
                    <div class="hover-box">
                        <a href="{{ route('transactions.details', $transaction['id']) }}">Details</a>
                        @if ($transaction['type'] === 'income')
                            <a href="{{ route('incomes.edit', $transaction['income_id']) }}">Edit</a>
                        @else
                            <a href="{{ route('expenses.edit', $transaction['expense_id']) }}">Edit</a>
                        @endif
                        <a href="{{ route('transactions.generateInvoice', $transaction['id']) }}">Generate Invoice</a>
                    </div>
                    <button class="btn btn-link text-dark">
                        <i class="fa fa-info-circle"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@section('styles')
    <style>
        /* Custom CSS for hover box */
        .hover-box {
            display: none;
            position: absolute;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            z-index: 1000;
            top: 100%;
            /* Adjust as needed */
            left: 0;
            width: 200px;
            /* Adjust as needed */
        }

        .hover-item:hover .hover-box {
            display: block;
        }

        .hover-box a {
            display: block;
            margin: 5px 0;
            color: #007bff;
            text-decoration: none;
        }

        .hover-box a:hover {
            text-decoration: underline;
        }
    </style>
@endsection
