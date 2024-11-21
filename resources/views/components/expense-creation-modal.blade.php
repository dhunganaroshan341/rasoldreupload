<div class="modal fade" id="expenseCreateModal" tabindex="-1" role="dialog" aria-labelledby="expenseCreateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="expenseForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="expenseCreateModalLabel">Add expense</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    @csrf
                    <div class="form-group">
                        <label for="client_service_id">Select Service</label>
                        <select class="form-control" id="client_service_id" name="client_service_id" required>
                            <option value="">Select a service</option>
                            @foreach ($clientServices as $service)
                                <option value="{{ $service->id }}">
                                    {{ $service->name ?? $service->service->name . '--' . $service->client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
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
                    <div class="form-group">
                        <label for="transaction_date">Transaction Date</label>
                        <input type="date" class="form-control" id="transaction_date" name="transaction_date"
                            required>
                    </div>
                    <input type="hidden" name="client_id" value="{{ $clientId }}">
                    <input type="hidden" name="source_type" value="existing" disabled> <!-- Default value -->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Inside your modal -->
                    <button type="submit" class="btn btn-primary" id="submitexpense">Add expense</button>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- ajax store --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@push('script-items')
    <script>
        $(document).on("click", "#submitexpense", function() {
            console.log("test");
        })
        $(document).ready(function() {
            $('#expenseForm').submit(function(event) {
                event.preventDefault(); // Prevent default form submission
                $("#submitexpense").text("Loading....");

                Gather form data
                var formData = {
                    _token: '{{ csrf_token() }}' // Include CSRF token
                    client_service_id: $('#client_service_id').val(),
                    amount: $('#amount').val(),
                    transaction_date: $('#transaction_date').val(),
                    client_id: $('input[name="client_id"]').val(),
                    // source_type: $('input[name="source_type"]').val(),
                    medium: $('#medium').val(),
                    source_type: 'existing',




                };
                let formdata = new FormData(this);
                console.log(formdata);


                // Make the AJAX request
                $.ajax({
                    type: 'POST',
                    url: '{{ route('clientExpenseModal.store') }}', // Ensure this route is correct
                    data: formdata,
                    // dataType: 'json',
                    contentType: false,
                    processData: false,

                    success: function(response) {
                        console.log(response);

                        if (response.success) {
                            // Optionally close the modal
                            $('#expenseCreateModal').modal('hide');
                            $("#expenseForm").trigger("reset");
                            // Show success message
                            alert(response.message);

                            // Optionally refresh the table or update UI here
                            // For example, you could append the new ledger entry to a table.
                        }
                    },
                    error: function(xhr) {
                        // Handle errors
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';

                        // Loop through errors and create a message
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] +
                                "\n"; // Get the first error message for each field
                        });

                        alert(errorMessage || 'An error occurred while adding expense.');
                    }
                });
            });
        });
    </script>
@endpush
