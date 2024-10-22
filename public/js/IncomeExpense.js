$(document).ready(function () {
    // Function to open the modal for creating a new income
    function openCreateIncomeModal() {
        $('#incomeModalLabel').text('Add Income');
        $('#incomeSubmitButton').text('Add Income');
        $('#incomeForm')[0].reset();
        $('#income_id').val('');
        $('#incomeModal').modal('show');
    }

    // Function to open the modal for editing an existing income
    function openEditIncomeModal(income) {
        $('#incomeModalLabel').text('Edit Income');
        $('#incomeSubmitButton').text('Update Income');
        $('#income_id').val(income.id);
        $('#income_source').val(income.source);
        $('#income_transaction_date').val(income.date);
        $('#income_amount').val(income.amount);
        $('#incomeModal').modal('show');
    }

    // Clear previous error messages
    function clearErrorMessages() {
        $('#incomeErrorMessages').addClass('d-none');
        $('#incomeErrorList').empty();
    }

    // Handle form submission with AJAX
    $('#incomeForm').submit(function (event) {
        event.preventDefault(); // Prevent the default form submission

        clearErrorMessages();

        let incomeId = $('#income_id').val();
        let url = incomeId ? `{{ url('incomes') }}/${incomeId}` : '{{ route('incomes.store') }}';
        let method = incomeId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(), // Serialize the form data
            success: function (response) {
                // Handle success (e.g., close the modal, show a success message, etc.)
                $('#incomeModal').modal('hide');
                alert(incomeId ? 'Income updated successfully!' :
                    'Income added successfully!');
                setTimeout(() => {
                    location.reload();
                }, 3000); // Delay of 3 seconds before refreshing the page
            },
            error: function (xhr) {
                // Handle error and display error messages
                $('#incomeErrorMessages').removeClass('d-none');
                var errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    $('#incomeErrorList').append('<li>' + value[0] + '</li>');
                });
            }
        });
    });

    // Example usage for opening modals
    // Replace the following with actual event handlers for your buttons or links
    $('#openCreateIncomeModalButton').on('click', function () {
        openCreateIncomeModal();
    });

    $('.openEditIncomeModalButton').on('click', function () {
        // Example income object, replace with actual data
        let income = {
            id: 1,
            source: 'Example Source',
            date: '2023-07-15',
            amount: 100.00
        };
        openEditIncomeModal(income);
    });
});
