<div class="d-flex justify-content-between align-items-center bg-light border-top p-3 mt-4">
    <div class="text-success">
        <h4 class="{{ $totalIncome < 0 ? 'text-danger' : 'text-success' }}">Total Income:
            {{ number_format($totalIncome, 2) }}/-</h4>
    </div>
    <div class="text-danger">
        <h4 class="{{ $totalExpense > $totalIncome ? 'text-danger' : 'text-success' }}">Total Expense:
            {{ number_format($totalExpense, 2) }}/-</h4>
    </div>
    <div class="text-center">
        <h4 class="{{ $filteredBalance < 0 ? 'text-danger' : 'text-success' }}">
            Balance: {{ number_format($filteredBalance) }}/-
        </h4>
    </div>
</div>
