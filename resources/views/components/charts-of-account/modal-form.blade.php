<!-- Modal Structure -->
<div class="modal fade" id="chartOfAccountsModal" tabindex="-1" role="dialog" aria-labelledby="chartOfAccountsModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="chartOfAccountsForm">
                <div class="modal-header">
                    <h5 class="modal-title">Chart of Accounts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="account_id" id="account_id">

                    <!-- Name Field -->
                    <div class="form-group">
                        <label for="name">Account Name</label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="e.g., Outsourcing Expense" required>
                    </div>

                    <!-- Type Field with Toggle -->
                    <div class="form-group">
                        <label>Account Type</label>
                        <select class="form-control mt-2" id="existing_type" name="existing_type" required>
                            <option value="">Select Account Type</option>
                            @foreach ($uniqueAccountTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="new_type_toggle" id="type_toggle"
                                onchange="toggleTypeInput()">
                            <label class="form-check-label" for="type_toggle">Add New Type</label>
                        </div>
                        <input type="text" class="form-control mt-2" id="new_type"
                            placeholder="Enter new account type" style="display: none;">
                    </div>

                    <!-- Description Field -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Brief description of the account"></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle between existing type and new type input
    function toggleTypeInput() {
        const existingType = document.getElementById('existing_type');
        const newType = document.getElementById('new_type');
        const typeToggle = document.getElementById('type_toggle').checked;

        if (typeToggle) {
            existingType.style.display = 'none';
            newType.style.display = 'block';
            newType.setAttribute('required', 'required');
        } else {
            newType.style.display = 'none';
            existingType.style.display = 'block';
            newType.removeAttribute('required');
        }
    }

    // Edit button click event handler
    function editAccount(id) {
        $('#account_id').val(id);

        $.ajax({
            url: `/coa/${id}/edit`,
            type: "GET",
            success: function(response) {
                if (response.success) {
                    const accountData = response.data;

                    // Populate modal fields with data
                    $('#account_id').val(accountData.id);
                    $('#name').val(accountData.name);
                    $('#description').val(accountData.description);

                    // Set account type field based on existing or new type
                    if (accountData.type && $('#existing_type option[value="' + accountData.type + '"]')
                        .length) {
                        $('#existing_type').val(accountData.type).show();
                        $('#type_toggle').prop('checked', false);
                        $('#new_type').hide().val('');
                    } else {
                        $('#new_type').val(accountData.type).show();
                        $('#type_toggle').prop('checked', true);
                        $('#existing_type').hide();
                    }

                    // Show the modal
                    $('#chartOfAccountsModal').modal('show');
                } else {
                    alert('Failed to open edit modal.');
                }
            },
            error: function() {
                alert('Failed to fetch account details. Please try again.');
            }
        });
    }

    // Form submission handler
    $(document).ready(function() {
        $("#chartOfAccountsForm").submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            const accountId = $("#account_id").val();
            const url = accountId ? `/coa/${accountId}` : "{{ route('coa.store') }}";

            if (accountId) {
                formData.append('_method', 'PUT');
            }

            // Determine type based on toggle state
            const typeValue = $("#type_toggle").is(":checked") ? $("#new_type").val() : $(
                "#existing_type").val();
            formData.set("type", typeValue);

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response.message);
                    $('#chartOfAccountsModal').modal('hide');
                    location.reload();
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>
