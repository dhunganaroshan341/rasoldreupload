<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeePayroll;
use App\Models\Month;

class EmployeePayrollService
{
    /**
     * Process payroll for an employee for a given month.
     */
    public function processPayroll(int $employeeId, int $monthId, float $amount): array
    {
        try {
            // Validate the month exists
            $month = Month::findOrFail($monthId);

            // Find the employee
            $employee = Employee::findOrFail($employeeId);

            // Calculate total salary and validate constraints
            $totalPaid = EmployeePayroll::where('employee_id', $employeeId)
                ->where('month_id', $monthId)
                ->sum('amount');

            $newTotal = $totalPaid + $amount;

            if ($newTotal > $employee->salary) {
                return [
                    'status' => false,
                    'message' => "Total salary paid for this month exceeds the fixed salary of {$employee->salary}.",
                ];
            }

            $payrollStatus = ($newTotal >= $employee->salary) ? 'Paid' : 'Remaining';
            $remainingAmount = $employee->salary - $newTotal;

            // Create payroll record
            $payroll = EmployeePayroll::create([
                'employee_id' => $employeeId,
                'month_id' => $monthId,
                'amount' => $amount,
                'payroll_status' => $payrollStatus,
                'remaining_amount' => $remainingAmount,
            ]);

            return [
                'status' => true,
                'message' => 'Payroll processed successfully.',
                'data' => $payroll,
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Error processing payroll: '.$e->getMessage(),
            ];
        }
    }
}
