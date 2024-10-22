$(document).ready(function () {
    $('#incomeForm').on('submit', function (event) {
        event.preventDefault();

        let formData = $(this).serialize();
        let url = 'incomes';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);
                // Optionally, you can display a success message
                // Reload the page
                location.reload();
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMessages = '';
                $.each(errors, function (key, value) {
                    errorMessages += `<li>${value}</li>`;
                });
                $('#incomeErrorMessages').removeClass('d-none').html(errorMessages);
            }
        });
    });

    // Ensure the modal opens if there's a specific condition (e.g., URL parameter or flag)
    if (window.location.search.includes('open-income-modal=true')) {
        $('#incomeModal').modal('show');
    }
});
