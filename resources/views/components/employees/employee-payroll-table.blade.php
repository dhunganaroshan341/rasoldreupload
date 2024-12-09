<!-- resources/views/components/employees/employee-payroll-table.blade.php -->


<table id="data-table-default-payroll" width="100%" class="table table-striped table-bordered align-middle ">
    <thead class="bg-light">
        <tr>
            <th>ID</th>
            <th>Month</th>
            <th>Payment Amount</th>
            <th>Status</th>
            <th>Remaining Amount</th>
            <th data-orderable="false">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payrolls as $payroll)
            @php
                if ($payroll->payroll_status == 'paid') {
                    # code...
                    $statusClass = 'success';
                } else {
                    # code...
                    $statusClass = 'warning';
                }
            @endphp
            <tr class="hover-item position-relative">
                <td>{{ $payroll->employee->name }}</td>
                <td>{{ $payroll->month->month_name }}</td>
                <td class="{{ 'text-' . $statusClass }}">{{ $payroll->amount }}</td>
                <td>

                    <span
                        class="{{ 'text-' . $statusClass }}
                    bg-light">{{ $payroll->payroll_status }}</span>
                </td>
                <td class="{{ 'text-' . $statusClass }}">{{ $payroll->remaining_amount }}</td>
                <td class="position-relative">
                    <button type="button" class="btn text-dark btn-sm"
                        onclick="editPayroll({{ json_encode($payroll) }})">
                        <i class="fa fa-pencil text-dark"></i> Edit
                    </button>
                    <form id="delete-payroll-{{ $payroll->id }}"
                        action="{{ route('employee.payroll.delete', $payroll->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn text-danger btn-sm"
                            onclick="confirmDeletePayroll({{ $payroll->id }})">
                            <i class="fa fa-trash text-danger"></i> Delete
                        </button>



                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@push('script-items')
    <script>
        function confirmDeletePayroll(id) {
            if (confirm('Are you sure you want to delete this payroll record?')) {
                $.ajax({
                    url: `/employee/payroll/delete/${id}`,
                    type: 'DELETE', // Use DELETE HTTP method
                    data: {
                        _token: '{{ csrf_token() }}' // Laravel CSRF token
                    },
                    success: function(response) {
                        alert('Payroll record deleted successfully.');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            }
        }
    </script>
@endpush
