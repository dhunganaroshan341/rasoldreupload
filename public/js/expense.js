$(document).ready(function () {
    $('#expenseForm').on('submit', function (event) {
        event.preventDefault();


        let formData = $(this).serialize();
        let url = '/expenses';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);
                $('#expenseModal').modal('hide');
                location.reload();
                // Refresh list or update UI as needed
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMessages = '';
                $.each(errors, function (key, value) {
                    errorMessages += `<li>${value}</li>`;
                });
                $('#expenseErrorMessages').removeClass('d-none').html(errorMessages);
            }
        });
    });
});
