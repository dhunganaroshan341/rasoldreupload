<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <h2>Expense Form</h2>
        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Back</a>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="form-group">
            <label for="expense_type">Select Expense Type:</label>
            <select wire:model="expense_type" class="form-control" id="expense_type">
                <option value="">Select Expense Type</option>
                <option value="utility">Utility Expense</option>
                <option value="salary">Salary</option>
                <option value="outsourcing">Outsourcing Expense</option>
                <option value="custom">Other/Custom Expense</option>
            </select>
        </div>

        <!-- Dropdown for Outsourcing Expense -->
        <div class="form-group" id="outsourcing_expense_group" style="display:none;">
            <label for="outsourcing_expense">OutSourcing Expense:</label>
            <select wire:model="outsourcing_expense" class="form-control" id="outsourcing_expense">
                <option value="">Select an Option</option>
                @foreach ($clientServices as $clientService)
                    <option value="{{ $clientService->id }}">
                        {{ $clientService->client->name }} - {{ $clientService->service->name }} (Amount:
                        {{ $clientService->service_amount }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Custom Expense Input -->
        <div class="form-group" id="custom_expense_group" style="display:none;">
            <label for="custom_expense">Custom Expense:</label>
            <input type="text" wire:model="custom_expense" id="custom_expense" class="form-control"
                placeholder="Enter custom expense">
        </div>

        <div class="form-group">
            <label for="transaction_date">Transaction Date:</label>
            <input type="date" wire:model="transaction_date" class="form-control" id="transaction_date" required>
        </div>

        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" step="0.01" wire:model="amount" class="form-control" id="amount" required>
        </div>

        <div class="form-group">
            <label for="medium">Transaction Medium:</label>
            <select wire:model="medium" class="form-control" id="medium" required>
                <option value="cash">Cash</option>
                <option value="cheque">Cheque</option>
                <option value="mobile_transfer">Mobile Transfer</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="remarks">Remarks:</label>
            <input type="text" wire:model="remarks" class="form-control" id="remarks" placeholder="Remarks">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>


@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('show-outsourcing-expense', () => {
                document.getElementById('outsourcing_expense_group').style.display = 'block';
                document.getElementById('custom_expense_group').style.display = 'none';
            });

            Livewire.on('show-custom-expense', () => {
                document.getElementById('custom_expense_group').style.display = 'block';
                document.getElementById('outsourcing_expense_group').style.display = 'none';
            });

            Livewire.on('hide-all', () => {
                document.getElementById('outsourcing_expense_group').style.display = 'none';
                document.getElementById('custom_expense_group').style.display = 'none';
            });
        });
    </script>
@endpush
