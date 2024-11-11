<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeePayroll;
use App\Models\Month;
use Exception;

class EmployeePayrollService
{
    /**
     * Process payroll for the employee and month.
     *
     * @return EmployeePayroll
     *
     * @throws Exception
     */
    public function processPayroll(int $employeeId, int $monthId, float $amount)
    {
        // Validate the month exists
        $month = Month::find($monthId);

        if (! $month) {
            throw new Exception('Invalid month.');
        }

        // Find the employee and ensure they exist
        $employee = Employee::findOrFail($employeeId);

        // Fixed salary for the employee (90k)
        $salaryAmount = 90000;

        // Get the total paid amount for this employee for the given month
        $totalPaid = EmployeePayroll::where('employee_id', $employeeId)
            ->where('month_id', $monthId)
            ->sum('amount');

        // Check if the total amount exceeds the fixed salary for the month
        if (($totalPaid + $amount) > $salaryAmount) {
            throw new Exception('Total salary paid for this month exceeds the employee\'s fixed salary of 90k.');
        }

        // Find or create the payroll record for this employee and month
        $payroll = EmployeePayroll::firstOrNew([
            'employee_id' => $employeeId,
            'month_id' => $monthId,
        ]);

        // Update the payroll details
        $payroll->amount = $amount;
        $payroll->payroll_status = ($totalPaid + $amount >= $salaryAmount) ? 'Paid' : 'Remaining';
        $payroll->remaining_amount = $salaryAmount - ($totalPaid + $amount);
        $payroll->save();

        return $payroll;
    }
}
