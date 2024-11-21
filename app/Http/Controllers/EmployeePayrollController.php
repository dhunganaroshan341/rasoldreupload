<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeePayroll;
use App\Services\EmployeePayrollService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeePayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $payrollService;

    public function __construct(EmployeePayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index()
    {
        $employees = Employee::all();

        return view('dashboard.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('dashboard.employees.create');
    }

    // Display form to edit an existing employee
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate input data
            $data = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'month_id' => 'required|exists:months,id',
                'amount' => 'required|numeric',
            ]);

            // Use service to handle payroll logic and restrictions
            $this->payrollService->processPayroll($request->employee_id, $request->month_id, $request->amount);

            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Update an existing employee
    public function update(Request $request, Employee $employee)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,'.$employee->id,
            'position' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'salary' => 'required|numeric|min:0',
        ]);

        // Update employee details
        $employee->update($validated);

        // Redirect or return a response
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the payroll record by ID
            $payroll = EmployeePayroll::find($id);

            if (! $payroll) {
                // If payroll record is not found, return a 404 response
                return response()->json(['error' => 'Payroll record not found'], 404);
            }

            // Delete the payroll record
            $payroll->delete();

            // Return a success response
            return response()->json(['success' => 'Payroll record deleted successfully']);
        } catch (\Throwable $th) {
            // Handle any errors and return a 500 response with the error message
            return response()->json(['error' => 'An error occurred while deleting the payroll record', 'message' => $th->getMessage()], 500);
        }
    }

    // Store or update the employee payroll
    public function storePayroll(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month_id' => 'required|exists:months,id',
            'amount' => 'required|numeric|min:0',
        ]);

        // Extract data from the request
        $employeeId = $request->input('employee_id');
        $monthId = $request->input('month_id');
        $amount = $request->input('amount');

        try {
            // Process the payroll via the service
            $payroll = $this->payrollService->processPayroll($employeeId, $monthId, $amount);

            return response()->json([
                'success' => true,
                'message' => 'Employee payroll saved successfully',
                'data' => $payroll,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function updatePayroll(Request $request)
    {
        $request->validate([
            'payroll_id' => 'required|exists:employee_payrolls,id',
            'employee_id' => 'required|exists:employees,id',
            'month_id' => 'required|exists:months,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $payroll = EmployeePayroll::find($request->input('payroll_id'));
        $payroll->employee_id = $request->input('employee_id');
        $payroll->month_id = $request->input('month_id');
        $payroll->amount = $request->input('amount');
        $payroll->payroll_status = ($payroll->amount >= $payroll->employee->salary) ? 'Paid' : 'Remaining';
        $payroll->remaining_amount = $payroll->employee->salary - $payroll->amount;
        $payroll->save();

        return response()->json([
            'success' => true,
            'message' => 'Employee payroll updated successfully',
            'data' => $payroll,
        ]);
    }
}
