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
                <form id="invoiceForm" method="POST" action="/api/outstanding-invoices">
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
                    <button type="submit" class="btn btn-primary" id="submitBtn">Create Invoice</button>
                </form>
                <!-- Message Display -->
                <div id="message"></div>
            </div>
        </div>
    </div>
</div>

@push('script-items')
    <script>
        // Handle client selection change and populate services
        document.getElementById('client_id').addEventListener('change', function() {
            const clientId = this.value;
            fetchClientServices(clientId);
        });

        // Fetch services for a specific client
        function fetchClientServices(clientId) {
            const serviceSelect = document.getElementById('service_id');
            const dueDateInput = document.getElementById('due_date');
            const totalAmountInput = document.getElementById('total_amount');

            // Clear existing options and reset fields
            serviceSelect.innerHTML = '<option value="">Select Service</option>';
            dueDateInput.value = '';
            totalAmountInput.value = '';

            if (clientId) {
                fetch(`/api/clients/${clientId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && Array.isArray(data.client.client_services)) {
                            data.client.client_services.forEach(service => {
                                const option = document.createElement('option');
                                option.value = service.id;
                                option.textContent = service.name;
                                serviceSelect.appendChild(option);
                            });
                        } else {
                            addErrorOption(serviceSelect, 'No services found for this client');
                        }
                    })
                    .catch(() => addErrorOption(serviceSelect, 'Error loading services'));
            }
        }

        // Add error option to select dropdown
        function addErrorOption(selectElement, message) {
            const option = document.createElement('option');
            option.textContent = message;
            option.disabled = true;
            selectElement.appendChild(option);
        }

        // Fetch additional details for selected service
        document.getElementById('service_id').addEventListener('change', function() {
            const serviceId = this.value;
            const dueDateInput = document.getElementById('due_date');
            const totalAmountInput = document.getElementById('total_amount');

            if (serviceId) {
                fetch(`/api/services/${serviceId}/latest-invoice`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            totalAmountInput.value = data.payableAmount || 0;
                            dueDateInput.value = data.dueDate ? new Date(data.dueDate).toISOString().split('T')[
                                0] : '';
                        }
                    })
                    .catch(() => console.error('Error fetching service details'));
            }
        });

        // Reset form when modal is closed
        $('#invoiceModal').on('hidden.bs.modal', function() {
            document.getElementById('invoiceForm').reset();
            document.getElementById('service_id').innerHTML = '<option value="">Select Service</option>';
        });

        // Handle form submission with AJAX
        // Handle form submission with AJAX
        document.getElementById('invoiceForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn'); // Get the submit button
            submitBtn.disabled = true; // Disable the submit button to prevent multiple submissions

            const formData = new FormData(this);
            console.log('Form Data:', formData);

            fetch(this.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    const messageContainer = document.getElementById('message');
                    if (data.success) {
                        messageContainer.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        $('#invoiceModal').modal('hide');
                    } else {
                        messageContainer.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                    }
                })
                .catch(() => {
                    document.getElementById('message').innerHTML =
                        `<div class="alert alert-danger">An error occurred. Please try again.</div>`;
                })
                .finally(() => {
                    submitBtn.disabled = false; // Re-enable the submit button after the request is complete
                });
        });
    </script>
@endpush
