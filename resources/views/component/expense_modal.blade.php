<div class="modal fade" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="expenseModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expenseModalLabel">Add Expense</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="expenseErrorMessages" class="alert alert-danger d-none">
                    <ul id="expenseErrorList"></ul>
                </div>
                <form id="expenseForm">
                    @csrf
                    @isset($expense)
                        {{-- Check if $expense is set, indicating edit mode --}}
                        @method('PUT') {{-- Use PUT method for update --}}
                        <input type="hidden" name="expense_id" value="{{ $expense->id }}">
                        <div class="form-group">
                            <label for="expense_source">Expense Source:</label>
                            <input type="text" class="form-control" name="expense_source" id="expense_source"
                                value="{{ $expense->source }}" required>
                        </div>
                        <div class="form-group">
                            <label for="expense_type">Type of Expense:</label>
                            <select class="form-control" name="expense_type" id="expense_type">
                                <option value="utility_expense" @if ($expense->type == 'utility_expense') selected @endif>Utility
                                    Expense</option>
                                <option value="Individual" @if ($expense->type == 'Individual') selected @endif>Individuals
                                </option>
                                <option value="payroll" @if ($expense->type == 'payroll') selected @endif>Payroll</option>
                                <option value="interests" @if ($expense->type == 'interests') selected @endif>Interest
                                    Payments</option>
                                <option value="other_expense" @if ($expense->type == 'other_expense') selected @endif>Other
                                    Expense</option>
                            </select>
                        </div>
                        <div class="form-group @if ($expense->type != 'other_expense') d-none @endif" id="otherExpenseInput">
                            <label for="otherInputText">Specify more:</label>
                            <input type="text" class="form-control" name="otherInputText" id="otherInputText"
                                value="{{ $expense->other_field }}" placeholder="Other expenses">
                        </div>
                        <div class="form-group">
                            <label for="expense_transaction_date">Transaction Date:</label>
                            <input type="date" class="form-control" name="transaction_date" id="expense_transaction_date"
                                value="{{ $expense->transaction_date }}" required>
                        </div>
                        <div class="form-group">
                            <label for="expense_amount">Amount:</label>
                            <input type="number" step="0.01" class="form-control" name="amount" id="expense_amount"
                                value="{{ $expense->amount }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Update Expense</button>
                    @else
                        {{-- Default to add mode --}}
                        <div class="form-group">
                            <label for="expense_source">Expense Source:</label>
                            <input type="text" class="form-control" name="expense_source" id="expense_source" required>
                        </div>
                        <div class="form-group">
                            <label for="expense_type">Type of Expense:</label>
                            <select class="form-control" name="expense_type" id="expense_type">
                                <option value="utility_expense">Utility Expense</option>
                                <option value="Individual">Individuals</option>
                                <option value="payroll">Payroll</option>
                                <option value="interests">Interest Payments</option>
                                <option value="other_expense">Other Expense</option>
                            </select>
                        </div>
                        <div class="form-group collapse" id="otherExpenseInput">
                            <label for="otherInputText">Specify more:</label>
                            <input type="text" class="form-control" name="otherInputText" id="otherInputText"
                                placeholder="Other expenses">
                        </div>
                        <div class="form-group">
                            <label for="expense_transaction_date">Transaction Date:</label>
                            <input type="date" class="form-control" name="transaction_date" id="expense_transaction_date"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="expense_amount">Amount:</label>
                            <input type="number" step="0.01" class="form-control" name="amount"
                                id="expense_amount" required>
                        </div>
                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <input type="text" class="form-control" name="remarks" id="remarks"
                                aria-describedby="helpId" placeholder="remarks">
                            <small id="helpId" class="form-text text-muted">Add expense remarks here</small>
                        </div>
                        @include('component.transaction_medium_select');
                        <button type="submit" class="btn btn-dark mt-3">Add Expense</button>
                    @endisset
                </form>
            </div>
        </div>
    </div>
</div>
