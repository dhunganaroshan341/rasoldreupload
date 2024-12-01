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
                    <div class="row mb-3">
                        <label for="total_amount" class="col-sm-4 col-form-label">Invoice Generation Amount</label>
                        <div class="col-sm-8">
                            <input value="" min="0" type="number" class="form-control" id="total_amount"
                                name="total_amount" required>
                        </div>
                    </div>

                    <div id="form-container"></div>

                    <button type="submit" class="btn btn-primary" id="submitBtn">Create Invoice</button>
                </form>
                <div id="message"></div> <!-- To show success or error message -->
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // making reusable input fields form
        function createInputField(name, isRequired = false) {
            // Create a form-group div
            const formGroup = document.createElement('div');
            formGroup.className = 'form-group';

            // Create a label
            const inputLabel = document.createElement('label');
            inputLabel.htmlFor = name;
            inputLabel.textContent = name.charAt(0).toUpperCase() + name.slice(1); // Capitalize the name
            formGroup.appendChild(inputLabel);

            // Create the input element
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.name = name;
            input.id = name;
            input.placeholder = name.charAt(0).toUpperCase() + name.slice(1); // Same as label
            if (isRequired) {
                input.required = true;
            }
            formGroup.appendChild(input);

            // Create help text
            if (isRequired) {
                const helpText = document.createElement('small');
                helpText.id = `${name}-help`;
                helpText.className = 'form-text text-muted';
                helpText.textContent = 'Required';
                formGroup.appendChild(helpText);
            }

            return formGroup;
        }
        //  this method /function createinputField is not used ok

        document.getElementById('client_id').addEventListener('change', function() {
            const clientId = this.value;
            const serviceSelect = document.getElementById('service_id');
            const dueDateInput = document.getElementById('due_date');

            // Clear previous options and due_date
            serviceSelect.innerHTML = '<option value="" class="text-dark">Select Service</option>';
            dueDateInput.value = '';

            if (clientId) {
                // Fetch client services
                fetch(`/api/clients/${clientId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.client && Array.isArray(data.client.client_services)) {
                            const services = data.client.client_services;

                            if (services.length > 0) {
                                services.forEach(service => {
                                    const option = document.createElement('option');
                                    option.value = service.id;
                                    option.textContent = service.name;
                                    serviceSelect.appendChild(option);
                                });

                                // // Append an input field
                                // const formContainer = document.getElementById(
                                //     'form-container'); // Ensure container exists
                                // formContainer.appendChild(createInputField('paid_amount', false));
                            } else {
                                const option = document.createElement('option');
                                option.textContent = 'No services found for this client';
                                option.disabled = true;
                                serviceSelect.appendChild(option);
                            }
                        } else {
                            const option = document.createElement('option');
                            option.textContent = 'Failed to load services';
                            option.disabled = true;
                            serviceSelect.appendChild(option);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching services:', error);
                        const option = document.createElement('option');
                        option.textContent = 'Error loading services';
                        option.disabled = true;
                        serviceSelect.appendChild(option);
                    });
            }
        });

        document.getElementById('service_id').addEventListener('change', function() {
            const serviceId = this.value;
            const dueDateInput = document.getElementById('due_date');
            const payableTotalAmount = document.getElementById('total_amount')
            if (serviceId) {
                fetch(`/api/services/${serviceId}/latest-invoice`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.invoice) {
                            const previousDueDate = new Date(data.dueDate);
                            payableTotalAmount.value = data.payableAmount;
                            // previousDueDate.setMonth(previousDueDate.getMonth() + 1);
                            dueDateInput.value = previousDueDate.toISOString().split('T')[0];
                        } else {
                            const today = new Date();
                            today.setMonth(today.getMonth() + 1);
                            dueDateInput.value = today.toISOString().split('T')[0];
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching invoice:', error);
                        const today = new Date();
                        today.setMonth(today.getMonth() + 1);
                        dueDateInput.value = today.toISOString().split('T')[0];
                    });
            }
        });

        document.getElementById('invoiceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

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
                    console.error('Error submitting form:', error);
                    document.getElementById('message').innerHTML =
                        `<div class="alert alert-danger">An error occurred. Please try again.</div>`;
                });
        });
    </script>
@endpush
