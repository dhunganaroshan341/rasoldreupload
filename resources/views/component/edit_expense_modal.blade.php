<div class="modal fade" id="editExpenseModal" tabindex="-1" role="dialog" aria-labelledby="editExpenseModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editExpenseModalLabel">Edit Expense</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="expenseForm">
                    @csrf
                    <input type="hidden" id="expense_id" name="expense_id">
                    <div class="form-group">
                        <label for="expense_source">Expense Source:</label>
                        <input type="text" class="form-control" name="expense_source" id="expense_source" required>
                    </div>
                    <div class="form-group">
                        <label for="expense_transaction_date">Transaction Date:</label>
                        <input type="date" class="form-control" name="transaction_date" id="expense_transaction_date"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="expense_amount">Amount:</label>
                        <input type="number" step="0.01" class="form-control" name="amount" id="expense_amount"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="medium">Transaction Medium:</label>
                        <select class="custom-select" name="medium" id="medium">
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                            <option value="mobile_transfer">Mobile Transfer</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3" id="expenseSubmitButton">Update Expense</button>
                </form>
            </div>
        </div>
    </div>
</div>
