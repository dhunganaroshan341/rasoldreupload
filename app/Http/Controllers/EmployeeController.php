<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeIndividualPayroll;
use App\Models\EmployeePayroll;
use App\Models\Month;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::all();
        $months = Month::pluck('month_name', 'id');
        $payrolls = EmployeePayroll::all();

        return view('dashboard.employees.index', compact('payrolls', 'employees', 'months'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('dashboard.employees.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'position' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:16',
            'salary' => 'required|numeric|min:0',
        ]);

        // Create a new employee
        Employee::create($validated);

        // Redirect or return a response
        return redirect()->route('employees.index')->with('success', 'Employee created successfully');

    }

    // Display form to edit an existing employee
    public function edit(Employee $employee)
    {
        $editMessage = 'edit '.$employee->name;

        return view('dashboard.employees.edit', compact('employee', 'editMessage'));
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
        //
    }
    /**
     * Store a newly created resource in storage.
     */
    // storign for modal
    // public function store(Request $request)
    // {
    //     // Validate input
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'salary' => 'required|numeric',
    //         'address' => 'required|string',
    //         'phone' => 'required|string',
    //         'position' => 'required|string',
    //     ]);

    //     // Check if the request has an 'id' (for update)
    //     if ($request->has('id') && ! empty($request->id)) {
    //         // Find the existing employee
    //         $employee = Employee::find($request->id);
    //         if ($employee) {
    //             // Update the employee with validated data
    //             $employee->update($validated);

    //             // Return a success response with updated employee data
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Employee updated successfully!',
    //                 'employee' => $employee,
    //             ]);
    //         } else {
    //             // Return an error response if employee is not found
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Employee not found.',
    //             ]);
    //         }
    //     } else {
    //         // Create a new employee
    //         $employee = Employee::create($validated);

    //         // Return a success response with new employee data
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Employee created successfully!',
    //             'employee' => $employee,
    //         ]);
    //     }
    // }
    // Store a newly created employee in storage

    public function storePayroll(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validate([
                'employee_id' => 'required',
                'month_id' => 'required',
            ]);
            $salary = $request->employee_id;
            $id = Employee::find($salary);
            $salary_amount = $id->salary;

            // dd($salary_amount);
            $data['default_salary_amount'] = $salary_amount;
            $data['remaining_amount'] = $data['default_salary_amount'] - $request->amount;
            if ($data['remaining_amount'] == $request->amount) {
                $data['payroll_status'] = 'paid';
            } elseif ($data['remaining_amount'] > $request->amount) {
                $data['payroll_status'] = 'paid';
            } else {
                $data['payroll_status'] = 'remaining';
            }
            // dd($data);
            $payroll = EmployeePayroll::updateOrCreate($data);
            //    $payroll['amount']=$request->amount;
            EmployeeIndividualPayroll::create([
                'employee_payroll_id' => $payroll,
                'amount' => $request->amount,
            ]);
            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
