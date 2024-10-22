@push('style')
    <style>
        .checkbox-container {
            margin-bottom: 10px;
        }

        .remove-btn {
            cursor: pointer;
            color: red;
            margin-left: 10px;
        }

        #service_select,
        #new_service {
            margin-bottom: 10px;
        }
    </style>
@endpush
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Dynamic Checkboxes</h2>

        <form>
            <div class="form-group">
                <label>Select Input Mode</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="input_mode" id="text_mode" checked>
                    <label class="form-check-label" for="text_mode">Text Input</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="input_mode" id="select_mode">
                    <label class="form-check-label" for="select_mode">Select Option</label>
                </div>
            </div>

            <div class="form-group">
                <label for="new_service">Enter Service</label>
                <input type="text" class="form-control" id="new_service" placeholder="Enter service">
            </div>

            <div class="form-group">
                <label for="service_select">Select a Service</label>
                <select id="service_select" class="form-control">
                    <option value="" disabled selected>Select a service</option>
                    <option value="Service 1">Service 1</option>
                    <option value="Service 2">Service 2</option>
                    <option value="Service 3">Service 3</option>
                </select>
            </div>
        </form>

        <div id="services_list">
            <!-- Dynamically added checkboxes will appear here -->
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function addService(serviceName) {
                var normalizedServiceName = serviceName.toLowerCase();

                // Check if service name already exists (case-insensitive)
                var exists = false;
                $('#services_list label').each(function() {
                    if ($(this).text().toLowerCase() === normalizedServiceName) {
                        exists = true;
                        return false; // Exit loop
                    }
                });

                if (exists) {
                    alert('This service already exists.');
                    return;
                }

                var checkboxId = 'checkbox-' + new Date().getTime();
                var checkboxHtml = `
                    <div class="checkbox-container">
                        <input type="checkbox" id="${checkboxId}" checked>
                        <label for="${checkboxId}">${serviceName}</label>
                        <span class="remove-btn">
                            <i class="fas fa-times"></i>
                        </span>
                    </div>
                `;
                $('#services_list').append(checkboxHtml);

                // Disable the selected option
                $('#service_select option').each(function() {
                    if ($(this).text() === serviceName) {
                        $(this).prop('disabled', true);
                        return false; // Exit loop
                    }
                });
            }

            // Toggle between text input and select dropdown
            $('input[name="input_mode"]').on('change', function() {
                if ($('#select_mode').is(':checked')) {
                    $('#new_service').hide();
                    $('#service_select').show();
                } else {
                    $('#service_select').hide();
                    $('#new_service').show();
                }
            });

            // Add service on select change
            $('#service_select').on('change', function() {
                var serviceName = $(this).val();
                if (serviceName) {
                    addService(serviceName);
                    $(this).val(''); // Clear the dropdown selection
                }
            });

            // Add service on Enter key press
            $('#new_service').on('keypress', function(e) {
                if (e.which === 13) { // Enter key pressed
                    e.preventDefault(); // Prevent form submission
                    var serviceName = $(this).val().trim();
                    if (serviceName) {
                        addService(serviceName);
                    }
                }
            });

            // Remove dynamically added checkbox
            $('#services_list').on('click', '.remove-btn', function() {
                var serviceName = $(this).siblings('label').text();
                $(this).parent().remove();

                // Enable the select option if it was removed
                $('#service_select option').each(function() {
                    if ($(this).text() === serviceName) {
                        $(this).prop('disabled', false);
                        return false; // Exit loop
                    }
                });
            });

            // Initial setup to hide/show elements
            $('#select_mode').trigger('change');
        });
    </script>
