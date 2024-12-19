<!-- Modal -->
<div style="z-index: 10000" class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Create Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Invoice Form -->
                <form id="invoiceForm">
                    @csrf
                    <!-- Client Selection -->
                    <div class="row mb-3">
                        <label for="client_id" class="col-sm-4 col-form-label">Client</label>
                        <div class="col-sm-8">
                            <select class="form-select" id="client_id" name="client_id" required>
                                <option value="">Select Client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Service Selection -->
                    <div class="row mb-3">
                        <label for="service_id" class="col-sm-4 col-form-label">Client Service</label>
                        <div class="col-sm-8">
                            <select class="form-select" id="service_id" name="client_service_id" required>
                                <option value="">Select Service</option>
                                <!-- Dynamically populated options -->
                            </select>
                        </div>
                    </div>
                    <!-- Due Date -->
                    <div class="row mb-3">
                        <label for="due_date" class="col-sm-4 col-form-label">Due Date</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="due_date" name="due_date" required>
                        </div>
                    </div>
                    <!-- Invoice Amount -->
                    <div class="row mb-3">
                        <label for="total_amount" class="col-sm-4 col-form-label">Invoice Generation Amount</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="total_amount" name="total_amount" required
                                min="0">
                        </div>
                    </div>
                    <!-- Dynamic Form Container -->
                    <div id="form-container"></div>
                    <!-- Submit Button -->
                    <button type="submit" class="btn bg-sidebar text-light float-right" id="submitBtn">Create
                        Invoice</button>
                </form>
                <!-- Message Display -->
                <div id="message"></div>
            </div>
        </div>
    </div>
</div>

@push('script-items')
    <script>
        $(document).ready(function() {
            // Handle client selection change and fetch services
            $('#client_id').change(function() {
                const clientId = $(this).val();
                fetchClientServices(clientId);
            });

            // Fetch services for a specific client
            function fetchClientServices(clientId) {
                const $serviceSelect = $('#service_id');
                const $dueDateInput = $('#due_date');
                const $totalAmountInput = $('#total_amount');

                // Clear existing options and reset fields
                $serviceSelect.html('<option value="" selected>Select Service</option>');
                $dueDateInput.val('');
                $totalAmountInput.val('');

                if (clientId) {
                    $.ajax({
                        url: `/api/clients/${clientId}`,
                        method: 'GET',
                        success: function(data) {
                            if (data.success && Array.isArray(data.client.client_services)) {
                                $.each(data.client.client_services, function(index, service) {
                                    $serviceSelect.append(
                                        `<option value="${service.id}">${service.name}</option>`
                                    );
                                });
                            } else {
                                addErrorOption($serviceSelect, 'No services found for this client');
                            }
                        },
                        error: function() {
                            addErrorOption($serviceSelect, 'Error loading services');
                        },
                    });
                }
            }

            // Add error option to select dropdown
            function addErrorOption($selectElement, message) {
                $selectElement.append(
                    `<option disabled>${message}</option>`
                );
            }

            // Fetch additional details for selected service
            $('#service_id').change(function() {
                const serviceId = $(this).val();
                const $dueDateInput = $('#due_date');
                const $totalAmountInput = $('#total_amount');

                if (serviceId) {
                    $.ajax({
                        url: `/api/services/${serviceId}/latest-invoice`,
                        method: 'GET',
                        success: function(data) {
                            if (data.success) {
                                $totalAmountInput.val(data.payableAmount || 0);
                                $dueDateInput.val(data.dueDate ? formatDate(data.dueDate) : '');
                            }
                        },
                        error: function() {
                            console.error('Error fetching service details');
                        },
                    });
                }
            });

            // Format date as YYYY-MM-DD
            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toISOString().split('T')[0];
            }

            // Reset form when modal is closed
            $('#invoiceModal').on('hidden.bs.modal', function() {
                $('#invoiceForm')[0].reset();
                $('#service_id').html('<option value="">Select Service</option>');
            });

            // Handle form submission with AJAX
            $('#invoiceForm').submit(function(e) {
                e.preventDefault();

                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true); // Disable the button

                let formData = new FormData(this);

                $.ajax({
                    url: '/api/outstanding-invoices',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        const messageContainer = $('#message');
                        if (data.success) {
                            messageContainer.html(
                                `<div class="alert alert-success">${data.message}</div>`
                            );
                            $('#invoiceModal').modal('hide');
                            // setTimeout(() => {
                            //     location.reload();
                            // }, 10000);


                        } else {
                            $messageContainer.html(
                                `<div class="alert alert-danger">${data.message}</div>`
                            );
                        }
                    },
                    error: function() {
                        $('#message').html(
                            `<div class="alert alert-danger">An error occurred. Please try again.</div>`
                        );
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false); // Re-enable the button
                    },
                });
            });
        });
    </script>
@endpush
