<!-- Income Modal -->
<div class="modal fade" id="incomeModal" tabindex="-1" role="dialog" aria-labelledby="incomeModalLabel" aria-hidden="true">
    <div class="modal-dialog pd-4" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="incomeModalLabel">Add Income</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Success/Error messages -->
                <div id="incomeMessages" class="alert d-none">
                    <span id="incomeMessageContent"></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Error messages -->
                <div id="incomeErrorMessages" class="alert alert-danger d-none">
                    <ul id="incomeErrorList"></ul>
                </div>

                <!-- Income Form -->
                <form id="incomeForm">
                    @csrf

                    <!-- Hidden Field for Source Type -->
                    <input type="hidden" name="source_type" value="existing">

                    <!-- Existing ClientService Dropdown -->
                    <div class="form-group row">
                        <label for="existing_client_service_id" class="col-sm-4 col-form-label">Select Existing
                            Service:</label>
                        <div class="col-sm-8">
                            <select class="form-control selectpicker" name="income_source"
                                id="existing_client_service_id" data-live-search="true" required>
                                <option value="" disabled selected>Select a service</option>
                                @foreach ($clientServices as $clientService)
                                    <option value="{{ $clientService->id }}">
                                        {{ $clientService->name ?? $clientService->client->name . '-' . $clientService->service->name }}
                                        -${{ $clientService->amount }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Transaction Date, Amount, Medium, Remarks -->
                    <div class="form-group row">
                        <label for="income_transaction_date" class="col-sm-4 col-form-label">Transaction Date:</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="transaction_date"
                                id="income_transaction_date" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="income_amount" class="col-sm-4 col-form-label">Amount:</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" class="form-control" name="amount" id="income_amount"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="medium" class="col-sm-4 col-form-label">Transaction Medium:</label>
                        <div class="col-sm-8">
                            <select class="custom-select" name="medium" id="medium">
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
                            <input type="text" class="form-control" name="remarks" id="remarks"
                                placeholder="Add income remarks">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3" id="incomeSubmitButton">Add Income</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Form submission via AJAX
        $('#incomeForm').on('submit', function(event) {
            event.preventDefault();

            // Hide previous error messages
            $('#incomeErrorMessages').addClass('d-none');

            // Collect form data
            let formData = {
                _token: $('input[name="_token"]').val(),
                transaction_date: $('#income_transaction_date').val(),
                source_type: 'existing',
                amount: $('#income_amount').val(),
                medium: $('#medium').val(),
                remarks: $('#remarks').val(),
                existing_client_service_id: $('#existing_client_service_id').val(),
            };

            // Create income
            createIncome(formData);
        });

        // Function to create income
        function createIncome(formData) {
            $.ajax({
                url: '{{ route('incomeModal.store') }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#incomeModal').modal('hide');
                    $('#incomeForm')[0].reset();
                    showAlert(response.message, 'success');
                },
                error: function(xhr) {
                    console.log(xhr); // Debugging line
                    if (xhr.responseJSON.errors) {
                        handleFormErrors(xhr);
                    } else {
                        showAlert(xhr.responseJSON.message || 'An unexpected error occurred.',
                            'error');
                    }
                }
            });
        }

        // Function to handle form errors
        function handleFormErrors(xhr) {
            const errors = xhr.responseJSON.errors;
            $('#incomeErrorMessages').removeClass('d-none'); // Show the error message container
            $('#incomeErrorList').html(''); // Clear previous error messages

            // Append error messages to the list
            $.each(errors, function(key, value) {
                $('#incomeErrorList').append('<li>' + value + '</li>');
            });

            // Optionally scroll to the top of the error messages
            $('html, body').animate({
                scrollTop: $("#incomeErrorMessages").offset().top
            }, 500);
        }

        // Function to show success or error alerts
        function showAlert(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            $('#incomeMessages').removeClass('d-none').addClass(alertClass);
            $('#incomeMessageContent').text(message);
        }
    });
</script>
