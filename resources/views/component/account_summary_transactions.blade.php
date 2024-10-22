<div class="container my-4">
    <div class="row">
        <!-- Account Summary -->

        <div class="col-md-4">

            <div class="d-flex flex-column border p-2 mb-2 bg-light" style="max-width: 300px;">
                <!-- Display Opening Balance -->
                <div class="d-flex align-items-center mb-2">
                    <div class="text-success" style="flex: 1; font-size: 0.875rem;">Opening Balance:</div>
                    <div style="flex: 2; font-size: 1rem;">
                        {{ number_format($startingAmount['totalBalanceUpTo'], 2) }}/-
                    </div>
                </div>

                <!-- Button to Trigger Modal -->
                <button type="button" class="btn btn-dark btn-sm mt-2" data-toggle="modal"
                    data-target="#accountSummaryModal"
                    style="padding: 0.25rem 0.5rem; font-size: 0.75rem; border-radius: 0.2rem;">
                    View Details
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="accountSummaryModal" tabindex="-1" role="dialog"
    aria-labelledby="accountSummaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="accountSummaryModalLabel">Account Summary Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted">As of: {{ $startDate }}</p>
                <hr>
                <div class="mb-3">
                    <p><strong>Opening Balance:</strong>
                        {{ number_format($startingAmount['totalBalanceUpTo'], 2) }}/-
                    </p>
                    <p><strong>Lifetime Income:</strong>
                        {{ number_format($startingAmount['totalIncomeUpTo'], 2) }}/-
                    </p>
                    <p><strong>Lifetime Expense:</strong>
                        {{ number_format($startingAmount['totalExpenseUpTo'], 2) }}/-
                    </p>
                </div>
                <hr>
                <p class="text-muted">After: {{ $endDate }}</p>
                <div class="mb-3">
                    <p><strong>Total Income:</strong> {{ number_format($totalIncome, 2) }}/-</p>
                    <p><strong>Total Expense:</strong> {{ number_format($totalExpense, 2) }}/-</p>
                    <p><strong>Total Balance:</strong> {{ number_format($filteredBalance, 2) }}/-</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
