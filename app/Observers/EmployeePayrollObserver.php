<?php

namespace App\Observers;

use App\Models\EmployeePayroll;
use App\Models\Expense;

class EmployeePayrollObserver
{
    /**
     * Handle the Expense "created" event.
     */
    public function created(EmployeePayroll $employeePayroll): void
    {
        // Logic to create or update the Expense when EmployeePayroll is created
        $this->updateExpense($employeePayroll);
    }

    /**
     * Handle the Expense "updated" event.
     */
    public function updated(EmployeePayroll $employeePayroll): void
    {
        // Logic to create or update the Expense when EmployeePayroll is updated
        $this->updateExpense($employeePayroll);
    }

    /**
     * Handle the Expense "deleted" event.
     */
    public function deleted(EmployeePayroll $employeePayroll): void
    {
        // Optionally, handle the deletion of expense or related records here.
    }

    /**
     * Handle the Expense "restored" event.
     */
    public function restored(EmployeePayroll $employeePayroll): void
    {
        // Optionally, handle the restoration of expense or related records here.
    }

    /**
     * Handle the Expense "force deleted" event.
     */
    public function forceDeleted(EmployeePayroll $employeePayroll): void
    {
        // Optionally, handle the force deletion of expense or related records here.
    }

    /**
     * Method to update or create an Expense based on the EmployeePayroll data.
     */
    protected function updateExpense(EmployeePayroll $employeePayroll)
    {
        // Fetch the amount from EmployeePayroll
        $amount = $employeePayroll->amount;

        // Check if an expense already exists for this employee payroll
        $expense = Expense::where('expense_source', $employeePayroll->id)
            ->where('transaction_date', $employeePayroll->created_at) // Or use a specific date
            ->first();

        // Prepare expense data
        $expenseData = [
            'expense_source' => $employeePayroll->id, // Link this expense to the employee payroll
            'source_type' => 'payroll', // Assuming payroll is the source type
            'transaction_date' => $employeePayroll->created_at, // Or use a custom date
            'amount' => $amount, // Amount from the EmployeePayroll model
            'medium' => 'bank', // Assuming medium is bank or based on your logic
            'remarks' => 'Payroll expense', // Remarks can be customized
            'coa_id' => 2, // Assuming COA ID for payroll expenses is 2
        ];

        // If the expense exists, update it; otherwise, create a new one
        if ($expense) {
            // Update the existing expense
            $expense->update($expenseData);
        } else {
            // Create a new expense record
            Expense::create($expenseData);
        }
    }
}
