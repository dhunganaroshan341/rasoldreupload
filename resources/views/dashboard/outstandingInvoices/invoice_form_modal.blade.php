<!-- Modal -->
<div style="z-index: 10000" class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Create Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="invoiceForm" method="POST" action="/api/outstanding-invoices">
                    @csrf
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
                    <div class="row mb-3">
                        <label for="service_id" class="col-sm-4 col-form-label">Client Service</label>
                        <div class="col-sm-8">
                            <select class="form-select" id="service_id" name="service_id" required>
                                <option value="">Select Service</option>
                                <!-- Options will be loaded dynamically -->
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="due_date" class="col-sm-4 col-form-label">Due Date</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="due_date" name="due_date" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Create Invoice</button>
                </form>
                <div id="message"></div> <!-- To show success or error message -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Load services when a client is selected
        document.getElementById('client_id').addEventListener('change', function() {
            const clientId = this.value;
            const serviceSelect = document.getElementById('service_id');
            const dueDateInput = document.getElementById('due_date');

            // Clear previous options and due_date
            serviceSelect.innerHTML = '<option value="" class="text-dark">Select Service</option>';
            dueDateInput.value = '';

            if (clientId) {
                let client = clientId;
                // Fetch client services
                fetch(`/api/clients/${client}`) // Updated endpoint to fetch client details
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const services = data.client.clientServices;
                            if (services && services.length > 0) {
                                services.forEach(service => {
                                    const option = document.createElement('option');
                                    option.value = service.id;
                                    option.textContent = service.name;
                                    serviceSelect.appendChild(option);
                                });
                            } else {
                                // If no services are found, show a message in the select dropdown
                                const option = document.createElement('option');
                                option.textContent = 'No services found for this client';
                                serviceSelect.appendChild(option);
                            }
                        } else {
                            // Handle failure response
                            const option = document.createElement('option');
                            option.textContent = 'Failed to load services';
                            serviceSelect.appendChild(option);
                        }
                    })
                    .catch(error => {
                        // Handle network error
                        console.error('Error fetching services:', error);
                        const option = document.createElement('option');
                        option.textContent = 'Error loading services';
                        serviceSelect.appendChild(option);
                    });
            }
        });


        // Load latest invoice and calculate due_date when a service is selected
        document.getElementById('service_id').addEventListener('change', function() {
            const serviceId = this.value;
            const dueDateInput = document.getElementById('due_date');

            if (serviceId) {
                // Fetch latest invoice for the service
                fetch(`/api/services/${serviceId}/latest-invoice`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.invoice) {
                            const previousDueDate = new Date(data.invoice.due_date);
                            // Add one month to the due date
                            previousDueDate.setMonth(previousDueDate.getMonth() + 1);
                            const newDueDate = previousDueDate.toISOString().split('T')[
                                0]; // Format as YYYY-MM-DD
                            dueDateInput.value = newDueDate;
                        } else {
                            // If no previous invoice, set today's date + 1 month
                            const today = new Date();
                            today.setMonth(today.getMonth() + 1);
                            dueDateInput.value = today.toISOString().split('T')[0];
                        }
                    });
            }
        });

        // Handle the form submission using AJAX
        document.getElementById('invoiceForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting normally
            const formData = new FormData(this); // Use FormData to get form data

            fetch(this.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('message').innerHTML =
                            `<div class="alert alert-success">${data.message}</div>`;
                        $('#invoiceModal').modal('hide');
                    } else {
                        document.getElementById('message').innerHTML =
                            `<div class="alert alert-danger">${data.message}</div>`;
                    }
                })
                .catch(error => {
                    document.getElementById('message').innerHTML =
                        `<div class="alert alert-danger">An error occurred. Please try again.</div>`;
                });
        });
    </script>
@endpush
