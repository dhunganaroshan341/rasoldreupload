<!-- Modal -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">
            <form id="employeePayrollForm">
                <div class="modal-header">
                    <h5 class="modal-title">Employee Salary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        @csrf
                        <input type="hidden" name="payroll_id" id="payroll_id">
                        <div class="form-group">
                            <label for="Employee">Employee</label>
                            <select class="form-control" name="employee_id" id="employee_id" required>
                                <option value="">Select The Employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Month">Month</label>
                            <select class="form-control" name="month_id" id="month_id" required>
                                <option value="">Select The Month</option>
                                @foreach ($months as $id => $month)
                                    <option value="{{ $id }}">{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Payment Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('script-items')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#employeePayrollForm").submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let url = "{{ route('employee.payroll.store') }}"; // Default to store route

                // If updating, set the update URL
                if ($("#payroll_id").val() !== "") {
                    url = "{{ route('employee.payroll.update') }}";
                }

                $.ajax({
                    method: "POST",
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success == true) {
                            alert(response.message);
                            $('#modelId').modal('hide');
                            location.reload(); // Reload the page to see updated data

                        } else if (response.sucess == false) {
                            alert(response.message);
                            location.reload(); // Reload the page to see updated data
                        }

                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '';
                        $.each(errors, function(key, value) {
                            errorMsg += value + '\n';
                        });
                        alert(errorMsg);
                    }

                });
            });
        });

        // Function to open modal for editing
        function editPayroll(payroll) {
            $('#payroll_id').val(payroll.id);
            $('#employee_id').val(payroll.employee_id);
            $('#month_id').val(payroll.month_id);
            $('#amount').val(payroll.amount);
            $('#modelId').modal('show');
        }
    </script>
@endpush
