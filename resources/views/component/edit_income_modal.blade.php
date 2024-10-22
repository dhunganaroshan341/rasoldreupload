{{-- <div class="modal fade" id="editIncomeModal" tabindex="-1" role="dialog" aria-labelledby="editIncomeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editIncomeModalLabel">Edit Income</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="incomeForm">
                    @csrf
                    <input type="hidden" id="income_id" name="income_id" value="{{ $transaction['income_id'] ?? '' }}">
                    <div class="form-group">
                        <label for="income_source">Income Source:</label>
                        <input type="text" class="form-control" name="income_source" id="income_source"
                            value="{{ $transaction['source'] ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="income_transaction_date">Transaction Date:</label>
                        <input type="date" class="form-control" name="transaction_date" id="income_transaction_date"
                            value="{{ $transaction['transaction_date'] ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="income_amount">Amount:</label>
                        <input type="number" step="0.01" class="form-control" name="amount" id="income_amount"
                            value="{{ $transaction['amount'] ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="medium">Transaction Medium:</label>
                        <select class="custom-select" name="medium" id="medium">
                            <option value="cash" {{ ($transaction['medium'] ?? '') == 'cash' ? 'selected' : '' }}>Cash
                            </option>
                            <option value="cheque" {{ ($transaction['medium'] ?? '') == 'cheque' ? 'selected' : '' }}>
                                Cheque</option>
                            <option value="mobile_transfer"
                                {{ ($transaction['medium'] ?? '') == 'mobile_transfer' ? 'selected' : '' }}>Mobile
                                Transfer</option>
                            <option value="other" {{ ($transaction['medium'] ?? '') == 'other' ? 'selected' : '' }}>
                                Other</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3" id="incomeSubmitButton">Update Income</button>
                </form>
            </div>
        </div>
    </div>
</div> --}}
