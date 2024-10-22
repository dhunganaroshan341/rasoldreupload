
$(document).ready(function () {
    // Hide all edit buttons initially
    $('.editTransaction').hide();

    // Toggle edit buttons on clicking the toggle button
    $('#toggleEditButton').click(function () {
        $('.editTransaction').toggle();
    });

    // Show/hide the 'Other Expense' input based on the selected expense type
    $('#expense_type').change(function () {
        var otherExpenseInput = $('#otherExpenseInput');
        if ($(this).val() === 'other_expense') {
            otherExpenseInput.collapse('show');
        } else {
            otherExpenseInput.collapse('hide');
        }
    });

    // Handle form submission with AJAX
    $('#expenseForm').submit(function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Clear previous error messages
        $('#errorMessages').addClass('d-none');
        $('#errorList').empty();

        $.ajax({
            url: 'http://127.0.0.1:8000/expenses/create',
            type: 'POST',
            data: $(this).serialize(), // Serialize the form data
            success: function (response) {
                // Handle success (e.g., close the modal, show a success message, etc.)
                $('#expense_form').modal('hide');
                alert('Expense added successfully!');
            },
            error: function (xhr) {
                // Handle error and display error messages
                $('#errorMessages').removeClass('d-none');
                var errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    $('#errorList').append('<li>' + value + '</li>');
                });
            }
        });
    });
});

