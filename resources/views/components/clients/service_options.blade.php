<script>
    $(document).ready(function() {
        var addedServices = {};

        // Show/hide new service input
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

        // Add new service on Enter key press
        $('#new_service').on('keypress', function(e) {
            if (e.which === 13) { // Enter key pressed
                e.preventDefault(); // Prevent form submission
                var newServiceName = $(this).val().trim();
                if (newServiceName) {
                    addNewService(newServiceName);
                    $(this).val(''); // Clear the input field
                }
            }
        });

        // Function to add a new service
        function addNewService(serviceName) {
            if ($('#new_services_list').find('label').filter(function() {
                    return $(this).text().trim() === serviceName;
                }).length > 0) {
                alert('Service already exists');
                return;
            }

            var checkboxId = 'checkbox-' + new Date().getTime();
            var checkboxHtml = `
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="${checkboxId}" name="services[]" value="${serviceName}">
                    <label class="form-check-label" for="${checkboxId}">${serviceName}</label>
                    <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn">
                        <i class="fa fa-close text-danger"></i>
                    </button>
                    <div class="service-options mt-2 p-2 border rounded" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="duration-${checkboxId}" class="mb-0">Duration:</label>
                            <button type="button" class="btn btn-sm btn-outline-secondary close-btn">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        <input type="number" name="service_duration_quantity[${serviceName}]" min="1" placeholder="" class="form-control">
                        <select id="duration-${checkboxId}" name="service_duration[${serviceName}]" class="form-control mb-2">
                            <option value="">Select Duration</option>
                            <option value="day">Day</option>
                            <option value="week">Week</option>
                            <option value="month">Month</option>
                            <option value="year">Year</option>
                        </select>
                    </div>
                </div>
            `;
            $('#new_services_list').append(checkboxHtml);
            addedServices[serviceName] = true;
        }

        // Remove new service
        $('#new_services_list').on('click', '.remove-btn', function() {
            var serviceName = $(this).siblings('label').text().trim();
            $(this).parent().remove();
            delete addedServices[serviceName];
        });

        // Add existing service
        function addExistingServiceToCheckbox(serviceId) {
            var serviceName = $('#service_type option[value="' + serviceId + '"]').text().trim();
            var checkboxId = 'checkbox-existing-' + serviceId;

            if ($('#existing_services_list').find('label').filter(function() {
                    return $(this).text().trim() === serviceName;
                }).length > 0) {
                return;
            }

            var checkboxHtml = `
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="${checkboxId}" name="services[]" value="${serviceId}">
                    <label class="form-check-label" for="${checkboxId}">${serviceName}</label>
                    <button type="button" class="btn btn-danger btn-sm ml-2 remove-btn">
                        <i class="fa fa-close text-danger"></i>
                    </button>
                    <div class="service-options mt-2 p-2 border rounded" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="duration-${checkboxId}" class="mb-0">Duration:</label>
                            <button type="button" class="btn btn-sm btn-outline-secondary close-btn">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        <input type="number" name="duration[${serviceId}]" value="0" placeholder="" class="form-control">
                        <select id="duration-${checkboxId}" name="duration_type[${serviceId}]" class="form-control mb-2">
                            <option value="">Select Duration</option>
                            <option value="day">Day</option>
                            <option value="week">Week</option>
                            <option value="month">Month</option>
                            <option value="year">Year</option>
                        </select>
                    </div>
                </div>
            `;
            $('#existing_services_list').append(checkboxHtml);

            // Check the checkbox if it's part of existing client data
            $('#existing_services_list').find('input[type="checkbox"]').each(function() {
                if ($(this).val() === serviceId) {
                    $(this).prop('checked', true).trigger('change');
                }
            });
        }

        // Remove existing service
        $('#existing_services_list').on('click', '.remove-btn', function() {
            $(this).parent().remove();
        });

        // Show/hide service options on checkbox click
        function toggleServiceOptions() {
            var checkbox = $(this);
            var serviceOptions = checkbox.siblings('.service-options');
            serviceOptions.toggle(checkbox.is(':checked'));
        }

        $('#new_services_list').on('click', 'input[type="checkbox"]', toggleServiceOptions);
        $('#existing_services_list').on('click', 'input[type="checkbox"]', toggleServiceOptions);

        // Close service options
        $('#new_services_list, #existing_services_list').on('click', '.close-btn', function() {
            var serviceOptions = $(this).closest('.service-options');
            var checkbox = $(this).closest('.form-check').find('input[type="checkbox"]');
            serviceOptions.hide();
            checkbox.prop('checked', false);
        });

        // On page load, pre-select existing services if in edit mode
        @if (isset($client))
            @foreach ($client->services as $service)
                addExistingServiceToCheckbox('{{ $service->id }}');
            @endforeach
        @endif
    });
</script>
