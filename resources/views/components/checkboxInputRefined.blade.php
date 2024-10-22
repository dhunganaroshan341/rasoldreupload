<div class="form-group">
    <label for="service_type">Services Used:</label>
    <select name="service_type" id="service_type" class="form-control">
        <option value="">Select Services</option>
        <option class="text-info" value="new">Enter New Service</option>
        @foreach ($existingServiceTypes as $serviceType)
            <option value="{{ $serviceType->id }}" class="service-option">
                {{ $serviceType->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group mt-3" id="new_service_container" style="display: none;">
    <label for="new_service">New Service</label>
    <input type="text" class="form-control" name="new_service" id="new_service" placeholder="Enter new service">
</div>

<div id="new_services_list" class="mt-3 checkbox-container">
    <!-- Initial checkboxes are not present -->
</div>
<div class="form-group mt-3" id="existing_services_container">
    <label>Existing Services:</label>
    <div id="existing_services_list" class="checkbox-container">
        <!-- Existing services will be added dynamically -->
    </div>
</div>

<script>
    var addedServices = {}; // Track dynamically added services
    var existingServices = {}; // Track existing services

    $(document).ready(function() {
        // Handle change event for service_type select
        $('#service_type').on('change', function() {
            var selectedValue = $(this).val();
            if (selectedValue === 'new') {
                $('#new_service_container').show();
            } else if (selectedValue) {
                addExistingServiceToCheckbox(selectedValue);
            } else {
                $('#new_service_container').hide();
            }
        });

        // Handle adding new service checkboxes dynamically
        $('#new_service').on('keypress', function(e) {
            if (e.which === 13) { // Enter key pressed
                e.preventDefault(); // Prevent form submission
                var newServiceName = $(this).val().trim();
                if (newServiceName) {
                    // Check if the new service name is already an existing service
                    var isExistingService = $('#service_type option').filter(function() {
                        // Convert both the existing service name and new service name to lowercase
                        return $(this).text().trim().toLowerCase() === newServiceName
                            .toLowerCase() &&
                            $(this).val() !== 'new';
                    }).length > 0;


                    if (isExistingService) {
                        alert(
                            'Service already exists in the dropdown. Please select it from the dropdown.'
                        );
                    } else {
                        addNewService(newServiceName);
                        $(this).val(''); // Clear the input field
                    }
                }
            }
        });

        // Function to add new service
        function addNewService(serviceName) {
            // Check if service already exists
            if ($('#new_services_list').find('label').filter(function() {
                    return $(this).text().trim() === serviceName;
                }).length > 0) {
                alert('Service already exists');
                return;
            }

            // Create a new checkbox element
            var checkboxId = 'checkbox-' + new Date().getTime();
            var checkboxHtml = `
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="${checkboxId}">
            <label class="form-check-label" for="${checkboxId}">
                ${serviceName}
            </label>
            <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn"><i class="fa fa-close text-danger"></i></button>
        </div>
    `;
            $('#new_services_list').append(checkboxHtml);

            // Add the new service to the select options
            var optionHtml = '<option value="' + serviceName + '" class="service-option">' + serviceName +
                '</option>';
            $('#service_type').append(optionHtml);
            // Mark the service as added
            addedServices[serviceName] = true;
            // Disable the new option in the select dropdown
            $('#service_type option[value="' + serviceName + '"]').prop('disabled', true);
        }

        // Handle removing service checkboxes and re-enable select option
        $('#new_services_list').on('click', '.remove-btn', function() {
            var serviceName = $(this).siblings('label').text();
            $(this).parent().remove();

            // Check if the service was dynamically added
            if (addedServices[serviceName]) {
                // Re-enable the select option for the removed service
                $('#service_type').append('<option value="' + serviceName +
                    '" class="service-option">' +
                    serviceName + '</option>');
                // Mark the service as removed
                delete addedServices[serviceName];
            } else {
                // Re-enable the select option for the existing service
                $('#service_type option').each(function() {
                    if ($(this).text().trim() === serviceName) {
                        $(this).prop('disabled', false);
                    }
                });
            }
        });

        // Function to add existing service to checkboxes
        function addExistingServiceToCheckbox(serviceId) {
            var serviceName = $('#service_type option[value="' + serviceId + '"]').text().trim();
            var checkboxId = 'checkbox-existing-' + serviceId;

            // Check if the service already exists in the checkboxes
            if ($('#existing_services_list').find('label').filter(function() {
                    return $(this).text().trim() === serviceName;
                }).length > 0) {
                return; // Exit if the service is already added
            }

            var checkboxHtml = `
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="${checkboxId}" checked>
            <label class="form-check-label" for="${checkboxId}">
                ${serviceName}
            </label>
            <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn"><i class="fa fa-close text-danger"></i></button>
        </div>
    `;
            $('#existing_services_list').append(checkboxHtml);

            // Disable the option in the select dropdown
            $('#service_type option[value="' + serviceId + '"]').prop('disabled', true);
        }

        // Handle removing existing services and re-enable select option
        $('#existing_services_list').on('click', '.remove-btn', function() {
            var serviceName = $(this).siblings('label').text().trim();
            var serviceId = $('#service_type option').filter(function() {
                return $(this).text().trim() === serviceName;
            }).val();

            $(this).parent().remove();

            // Re-enable the corresponding select option
            $('#service_type option[value="' + serviceId + '"]').prop('disabled', false);
        });

        // Fetch existing services from the server when the document is ready
        $.ajax({
            url: '/path/to/your/api/for/existing-services', // Replace with the actual API endpoint
            method: 'GET',
            success: function(data) {
                data.services.forEach(function(service) {
                    addExistingServiceToCheckbox(service
                        .id); // Adjust based on your API response structure
                });
            }
        });
    });
</script>
