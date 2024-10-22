{{-- Date Filter for transactions/ index.blade  table which is also  at transactions_table at component --}}
<form method="GET" action="{{ route('transactions.index') }}" class="mt-4">
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" class="form-control"
                value="{{ request()->has('start_date') ? request('start_date') : $startDate }}">
        </div>
        <div class="form-group col-md-3">
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" class="form-control"
                value="{{ request()->has('end_date') ? request('end_date') : $endDate }}">
        </div>

        <div class="form-group col-md-2 align-self-end">
            <button type="submit" class="btn btn-outline-dark">
                <i class="dw dw-filter"></i> Filter
            </button>
        </div>
        <div class="form-group col-md-4 align-self-end">
            <a href="{{ route('transactions.export') }}?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}"
                class="btn btn-success">
                <i class="dw dw-export"></i> Export to Excel
            </a>
        </div>
    </div>
</form>
