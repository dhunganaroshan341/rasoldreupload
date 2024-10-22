<!-- Income Modal -->
<div class="modal fade" id="incomeModal" tabindex="-1" role="dialog" aria-labelledby="incomeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="incomeModalLabel">Add Income</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="incomeErrorMessages" class="alert alert-danger d-none">
                    <ul id="incomeErrorList"></ul>
                </div>
                <form id="incomeForm">
                    @csrf
                    <input type="hidden" id="income_id" name="income_id">
                    {{-- <div class="form-group">


                    </div> --}}
                    <!-- Radio Buttons for Source Type -->
                    <div class="form-group">
                        <input type="radio" id="select_existing" name="source_type" value="existing"
                            {{ old('source_type', $income->source_type ?? 'existing') === 'existing' ? 'checked' : '' }}>
                        <label for="select_existing">Select Existing Service</label>
                        <input type="radio" id="add_new" name="source_type" value="new"
                            {{ old('source_type', $income->source_type ?? 'existing') === 'new' ? 'checked' : '' }}>
                        <label for="add_new">Add New Service</label>
                    </div>

                    <!-- Dropdown for existing client services -->
                    <div class="form-group" id="existing_service_dropdown"
                        style="display: {{ old('source_type', $income->source_type ?? 'existing') === 'existing' ? 'block' : 'none' }}">
                        <select class="form-control selectpicker" name="income_source" id="income_source">
                            @foreach ($clientServices as $clientService)
                                <option value="{{ $clientService->client_id }}|{{ $clientService->service_id }}"
                                    {{ old('income_source', $income->income_source ?? '') == $clientService->client_id . '|' . $clientService->service_id ? 'selected' : '' }}>
                                    {{ $clientService->client->name }} - {{ $clientService->service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input field for new client service -->
                    <div class="form-group" id="new_service_input"
                        style="display: {{ old('source_type', $income->source_type ?? 'existing') === 'new' ? 'block' : 'none' }}">
                        <input type="text" class="form-control" name="new_service_name" id="new_service_name"
                            value="{{ old('new_service_name', $income->income_source ?? '') }}"
                            placeholder="Specify new client service">
                    </div>
                    <div class="form-group">
                        <label for="income_transaction_date">Transaction Date:</label>
                        <input type="date" class="form-control" name="transaction_date" id="income_transaction_date"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="income_amount">Amount:</label>
                        <input type="number" step="0.01" class="form-control" name="amount" id="income_amount"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="Tranasaction  Medium">Tranasaction Medium</label>
                        <select class="custom-select" name="medium" id="medium">
                            <option selected>Select one</option>
                            <option value="cash">cash</option>
                            <option value="cheque">cheque</option>
                            <option value="mobile_transfer">mobile transfer</option>
                            <option id = "other"value="other"> other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <input type="text" class="form-control" name="remarks" id="remarks"
                            aria-describedby="helpId" placeholder="remarks">
                        <small id="helpId" class="form-text text-muted">add income remarks here</small>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3" id="incomeSubmitButton">Add Income</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Script to handle the selectpicker initialization and form submission -->
<script>
    $(document).ready(function() {
        // Initialize selectpicker
        $('.selectpicker').selectpicker({
            liveSearch: true
        });

        // Handle visibility based on selected source type
        $('input[name="source_type"]').change(function() {
            const selectedValue = $(this).val();
            $('#existing_service_dropdown').toggle(selectedValue === 'existing');
            $('#new_service_input').toggle(selectedValue === 'new');

            // Update the required attributes based on visibility
            if (selectedValue === 'existing') {
                $('#income_source').prop('required', true);
                $('#new_service_name').prop('required', false);
            } else {
                $('#income_source').prop('required', false);
                $('#new_service_name').prop('required', true);
            }
        }).trigger('change'); // Trigger change on page load to set initial state

        // Before submitting the form, ensure only the visible input is required
        $('#income_form').submit(function() {
            if ($('#select_existing').is(':checked')) {
                $('#income_source').prop('required', true);
                $('#new_service_name').prop('required', false);
            } else if ($('#add_new').is(':checked')) {
                $('#income_source').prop('required', false);
                $('#new_service_name').prop('required', true);
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        var isEdit = false;
        var expenseId = null;

        $('#mediumSelect').on('change', function() {
            if ($(this).val() === 'other') {
                $('#other_source').removeClass('d-none');
            } else {
                $('#other_source').addClass('d-none');
                $('#other_source').val('');
            }
        });

        $('#saveExpense').on('click', function() {
            var selectedMedium = $('#mediumSelect').val();
            var mediumValue = selectedMedium === 'other' ? $('#other_source').val() : selectedMedium;

            var formData = {
                expense_source: $('#expense_source').val(),
                source_type: $('#source_type').val(),
                transaction_date: $('#transaction_date').val(),
                amount: $('#amount').val(),
                medium: mediumValue,
                _token: '{{ csrf_token() }}' // Ensure CSRF token is included
            };

            var ajaxType = isEdit ? 'PUT' : 'POST';
            var ajaxUrl = isEdit ? '{{ url('expenses') }}/' + expenseId :
                '{{ route('expenses.store') }}';

            $.ajax({
                type: ajaxType,
                url: ajaxUrl,
                data: formData,
                success: function(response) {
                    $('#expenseModal').modal('hide');
                    alert('Expense ' + (isEdit ? 'updated' : 'saved') + ' successfully!');
                    // Optionally, refresh the expenses list or update the UI
                },
                error: function(xhr) {
                    // Clear previous errors
                    $('#expenseForm').find('.form-control').removeClass('is-invalid');
                    $('#expenseForm').find('.invalid-feedback').text('');

                    if (xhr.status === 422) { // Validation error
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, messages) {
                            var input = $('#' + key);
                            input.addClass('is-invalid');
                            input.siblings('.invalid-feedback').text(messages[0]);
                        });
                    } else {
                        alert('Error saving expense: ' + xhr.responseText);
                    }
                }
            });
        });

        // Handle edit button click
        $('.edit-expense').on('click', function() {
            expenseId = $(this).data('id'); // Get the ID from the button
            isEdit = true;

            $.ajax({
                type: 'GET',
                url: '/expenses/edit/' + expenseId, // This should match the route

                success: function(response) {
                    console.log(response); // Check if the response is coming
                    $('#expenseModalLabel').text(response
                        .formTitle); // Use the formTitle from the response
                    $('#saveExpense').text('Update Expense');

                    // Populate form fields with data from response
                    $('#expense_source').val(response.expense.expense_source);
                    $('#source_type').val(response.expense.source_type);
                    $('#transaction_date').val(response.expense.transaction_date);
                    $('#amount').val(response.expense.amount);
                    $('#mediumSelect').val(response.expense.medium).trigger('change');

                    if (response.expense.medium === 'other') {
                        $('#other_source').val(response.expense.other_source);
                    }

                    // Show modal
                    $('#expenseModal').modal('show');
                },
                error: function(xhr) {
                    alert('Error fetching expense data: ' + xhr.responseText);
                }
            });
        });


        // Reset modal when closed
        $('#expenseModal').on('hidden.bs.modal', function() {
            $('#expenseForm')[0].reset();
            $('#expenseForm').find('.form-control').removeClass('is-invalid');
            $('#expenseForm').find('.invalid-feedback').text('');
            $('#other_source').addClass('d-none');
            $('#expenseModalLabel').text('Add Expense');
            $('#saveExpense').text('Save Expense');
            isEdit = false;
            expenseId = null;
        });
    });
</script>
