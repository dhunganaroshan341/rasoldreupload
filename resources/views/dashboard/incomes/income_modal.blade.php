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
                <!-- Error Messages -->
                <div id="incomeErrorMessages" class="alert alert-danger d-none">
                    <ul id="incomeErrorList"></ul>
                </div>
                <!-- Income Form -->
                <form id="incomeForm">
                    @csrf
                    <input type="hidden" id="income_id" name="income_id">

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
                                <option value="{{ $clientService->id }}"
                                    {{ old('income_source', $income->income_source ?? '') == $clientService->id ? 'selected' : '' }}>
                                    {{ $clientService->client->name }} - {{ $clientService->service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <!-- Source Type -->
                    <div class="form-group">
                        <label for="source_type">Source Type</label>
                        <select class="form-control" name="source_type" id="source_type" required>
                            <option value="existing">Existing</option>
                            <option value="new">New</option>
                        </select>
                    </div> --}}

                    <!-- Transaction Date -->
                    <div class="form-group">
                        <label for="transaction_date">Transaction Date</label>
                        <input type="date" class="form-control" name="transaction_date" id="transaction_date"
                            required>
                    </div>

                    <!-- Amount -->
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" step="0.01" class="form-control" name="amount" id="amount"
                            required>
                    </div>

                    <!-- Transaction Medium -->
                    <div class="form-group">
                        <label for="medium">Transaction Medium</label>
                        <select class="form-control" name="medium" id="medium" required>
                            <option value="">Select Medium</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                            <option value="mobile_transfer">Mobile Transfer</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary mt-3">Add Income</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script -->
<script>
    $(document).ready(function() {
        // Handle form submission
        $('#incomeForm').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            // Serialize form data
            var formData = $(this).serialize();

            // Clear previous errors
            $('#incomeErrorMessages').addClass('d-none');
            $('#incomeErrorList').empty();

            // Send AJAX request
            $.ajax({
                url: '{{ route('incomes.store') }}', // Adjust the URL based on your route
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#incomeModal').modal('hide');
                    alert('Income added successfully!');
                    // Optionally, refresh the income list or update the UI
                },
                error: function(xhr) {
                    if (xhr.status === 422) { // Validation error
                        var errors = xhr.responseJSON.errors;
                        $('#incomeErrorMessages').removeClass('d-none');
                        $.each(errors, function(key, messages) {
                            $('#incomeErrorList').append('<li>' + messages[0] +
                                '</li>');
                        });
                    } else {
                        alert('Error adding income: ' + xhr.responseText);
                    }
                }
            });
        });

        // Reset modal when closed
        $('#incomeModal').on('hidden.bs.modal', function() {
            $('#incomeForm')[0].reset();
            $('#incomeErrorMessages').addClass('d-none');
            $('#incomeErrorList').empty();
        });
    });
</script>
