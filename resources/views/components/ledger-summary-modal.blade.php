<div class="modal fade" id="ledgerSummaryModal" tabindex="-1" aria-labelledby="ledgerSummaryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ledgerSummaryModalLabel">Ledger Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Summary Information -->
                <div class="mb-3">
                    <h6>Total Income: <span class="text-success">{{ number_format($totalIncome, 2) }}</span></h6>
                    <h6>Total Expense: <span class="text-danger">{{ number_format($totalExpense, 2) }}</span></h6>
                    <h6>Balance: <span
                            class="{{ $balance >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($balance, 2) }}</span>
                    </h6>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
