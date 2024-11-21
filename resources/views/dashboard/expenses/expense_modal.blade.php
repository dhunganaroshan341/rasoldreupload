<div class="modal fade" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="expenseModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expenseModalLabel">Expense Form</h5>
            </div>
            <div class="modal-body">
                <div id="error-message" class="alert alert-danger d-none"></div>
                <form id="expenseForm">
                    @csrf
                    <input type="hidden" name="id" id="expenseId">

                    <div class="form-group row">
                        <label for="source_type" class="col-sm-4 col-form-label">Select Expense Type:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="source_type" id="source_type" required>
                                <option value="">Select Expense Type</option>
                                <option value="utility">Utility Expense</option>
                                <option value="salary">Salary</option>
                                <option value="outsourcing">Outsourcing Expense</option>
                                <option value="custom">Other/Custom Expense</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row fade" id="outsourcing_expense_group">
                        <label for="client_service_id" class="col-sm-4 col-form-label">Outsourcing Expense:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="client_service_id" id="client_service_id">
                                <option value="">Select Client Service</option>
                                @foreach ($clientServices as $clientService)
                                    <option value="{{ $clientService->id }}">
                                        {{ $clientService->name ?? $clientService->client->name . '-' . $clientService->service->name }}
                                        -${{ $clientService->amount }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="transaction_date" class="col-sm-4 col-form-label">Transaction Date:</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="transaction_date" id="transaction_date"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="amount" class="col-sm-4 col-form-label">Amount:</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" class="form-control" name="amount" id="amount"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="medium" class="col-sm-4 col-form-label">Transaction Medium:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="medium" id="medium" required>
                                <option value="cash">Cash</option>
                                <option value="cheque">Cheque</option>
                                <option value="mobile_transfer">Mobile Transfer</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="remarks" class="col-sm-4 col-form-label">Remarks:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="remarks" id="remarks">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        function toggleExpenseFields() {
            const selectedType = document.getElementById('source_type').value;
            const outsourcingGroup = document.getElementById('outsourcing_expense_group');

            if (selectedType === 'outsourcing') {
                outsourcingGroup.classList.add('show');
                outsourcingGroup.classList.remove('fade');
            } else {
                outsourcingGroup.classList.remove('show');
                outsourcingGroup.classList.add('fade');
            }
        }

        document.getElementById('source_type').addEventListener('change', toggleExpenseFields);

        // Handle form submission
        document.getElementById('expenseForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let formData = new FormData(this);
            let expenseId = document.getElementById('expenseId').value;
            let url = expenseId ?
                `{{ route('expenses.updateOnModal', ['id' => '__ID__']) }}`.replace('__ID__',
                    expenseId) :
                '{{ route('expenses.store') }}';
            let method = expenseId ? 'PUT' : 'POST';

            fetch(url, {
                    method: method,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        $('#expenseModal').modal('hide');
                        location.reload();
                    } else {
                        document.getElementById('error-message').textContent = data.error;
                        document.getElementById('error-message').classList.remove('d-none');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

        // Initialize modal with data
        window.showExpenseModal = function(expenseId) {
            fetch(`{{ route('expenses.editInModal', ['id' => '__ID__']) }}`.replace('__ID__', expenseId))
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        document.getElementById('expenseModalLabel').textContent = data.formTitle;
                        document.getElementById('expenseForm').action = data.formAction;
                        document.getElementById('expenseId').value = expenseId;

                        // Populate form fields
                        document.getElementById('source_type').value = data.expense.source_type;
                        document.getElementById('expense_source').value = data.expense.expense_source ||
                            '';
                        document.getElementById('transaction_date').value = data.expense
                            .transaction_date;
                        document.getElementById('amount').value = data.expense.amount;
                        document.getElementById('medium').value = data.expense.medium;
                        document.getElementById('remarks').value = data.expense.remarks || '';

                        toggleExpenseFields();
                        $('#expenseModal').modal('show');
                    }
                })
                .catch(error => console.error('Error:', error));
        };
    });
</script>
