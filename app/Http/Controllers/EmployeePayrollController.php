<?php

namespace App\Http\Controllers;

use App\Models\EmployeePayroll;
use App\Services\EmployeePayrollService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeePayrollController extends Controller
{
    protected $payrollService;

    public function __construct(EmployeePayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    /**
     * Store payroll for an employee.
     */
    public function storePayroll(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month_id' => 'required|exists:months,id',
            'amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $result = $this->payrollService->processPayroll(
                $validated['employee_id'],
                $validated['month_id'],
                $validated['amount']
            );

            if ($result['status']) {
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => $result['data'],
                ]);
            }

            // Rollback if any validation from the service fails
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error processing payroll: '.$e->getMessage(),
            ], 400);
        }
    }

    /**
     * Update an existing payroll record.
     */
    public function updatePayroll(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'payroll_id' => 'required|exists:employee_payrolls,id',
            'employee_id' => 'required|exists:employees,id',
            'month_id' => 'required|exists:months,id',
            'amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $payroll = EmployeePayroll::findOrFail($validated['payroll_id']);
            $payroll->update([
                'employee_id' => $validated['employee_id'],
                'month_id' => $validated['month_id'],
                'amount' => $validated['amount'],
                'payroll_status' => ($validated['amount'] >= $payroll->employee->salary) ? 'Paid' : 'Remaining',
                'remaining_amount' => $payroll->employee->salary - $validated['amount'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payroll updated successfully.',
                'data' => $payroll,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error updating payroll: '.$e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete an existing payroll record.
     */
    public function destroyPayroll($id)
    {
        // Validate incoming request to ensure payroll_id is provided and exists

        DB::beginTransaction();
        try {
            $payroll = EmployeePayroll::findOrFail($id);

            // Delete the payroll record
            $payroll->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payroll record deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error deleting payroll: '.$e->getMessage(),
            ], 400);
        }
    }
}
