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
                <form id="invoiceForm">
                    @csrf
                    @method('POST')
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
                    <!-- Other fields -->
                    <button type="submit" class="btn btn-primary" id="submitBtn">Create Invoice</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Load services when a client is selected
    document.getElementById('client_id').addEventListener('change', function() {
        const clientId = this.value;
        const serviceSelect = document.getElementById('service_id');
        const dueDateInput = document.getElementById('due_date');

        // Clear previous options and due_date
        serviceSelect.innerHTML = '<option value="" class "text-dark">Select Service</option>';
        dueDateInput.value = '';

        if (clientId) {
            // Fetch client services
            fetch(`/api/clients/${clientId}/services`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        data.client.clientServices.forEach(service => {
                            const option = document.createElement('option');
                            option.value = service.id;
                            option.textContent = service.name;
                            serviceSelect.appendChild(option);
                        });
                    }
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
</script>
