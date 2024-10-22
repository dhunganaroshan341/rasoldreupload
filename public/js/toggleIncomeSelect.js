
$(document).ready(function () {
    function toggleFields() {
        if ($('input[name="source_type"]:checked').val() === 'contracts') {
            $('#contract_dropdown').show();
            $('#other_source_input').hide();
        } else {
            $('#contract_dropdown').hide();
            $('#other_source_input').show();
        }
    }

    // Initial check on page load
    toggleFields();

    // Check on change event of the radio buttons
    $('input[name="source_type"]').on('change', function () {
        toggleFields();
    });
});

