
$(document).ready(function () {


    $('[data-toggle="collapse"]').on('click', function () {
        var icon = $(this).find('#toggleIcon');
        if ($('#minimizableContainer').hasClass('show')) {
            icon.html('&#x25B2;'); // Up arrow
        } else {
            icon.html('&#x25BC;'); // Down arrow
        }
    });

    function openCreateIncomeModal() {
        $('#incomeModalLabel').text('Add Income');
        $('#incomeSubmitButton').text('Add Income');
        $('#income_id').val(''); // Clear hidden input
        $('#income_source').val('');
        $('#income_transaction_date').val('');
        $('#income_amount').val('');
        $('#incomeForm').attr('action', '/incomes/create'); // Set action for create
        $('#incomeForm').find('input[name="_method"]').remove(); // Remove method field if present
        $('#incomeModal').modal('show');
    }

    // Example of triggering the modals
    // $(document).on('click', '.edit-button', function() {
    //     var income = $(this).data('income'); // Assuming you have the income data as a JSON object
    //     openEditIncomeModal(income);
    // });

    // $(document).on('click', '#addIncomeButton', function() {
    //     openCreateIncomeModal();
    // });

    // $(document).on('submit', '#incomeForm', function(event) {
    //     event.preventDefault();
    //     console.log('income created');
    // });

    // Function to open Create Expense Modal
    function openCreateExpenseModal() {
        $('#expenseModalLabel').text('Add Expense');
        $('#expenseSubmitButton').text('Add Expense');
        $('#expenseForm')[0].reset();
        $('#expenseModal').modal('show');
        setTimeout(function () {
            // Refresh the page after form submission
            location.reload();
        }, 100);
    }


    // Clear error messages
    function clearErrorMessages() {
        $('#incomeErrorMessages').addClass('d-none').empty();
        $('#expenseErrorMessages').addClass('d-none').empty();
    }

    // Handle click on Create Income button
    $('#openCreateIncomeModalButton').on('click', function () {
        openCreateIncomeModal();
    });

    // Handle click on Create Expense button
    $('#openCreateExpenseModalButton').on('click', function () {
        openCreateExpenseModal();
    });
});
