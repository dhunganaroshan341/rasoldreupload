<?php 
namespace App\Http\Controllers;

use App\Models\ChartsOfAccount;
use Illuminate\Http\Request;

class ChartsOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chartsOfAccount = ChartsOfAccount::all();
        $uniqueAccountTypes = $chartsOfAccount->pluck('type')->unique();

        return view('dashboard.ChartsOfAccount.index', compact('chartsOfAccount', 'uniqueAccountTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|string|max:255',
        ]);

        // Determine if we are using an existing type or a new type
        $type = $request->input('type'); // This will come from the frontend (either new or existing type)

        try {
            $chartOfAccount = new ChartsOfAccount();
            $chartOfAccount->name = $validatedData['name'];
            $chartOfAccount->type = $type;  // Save the type
            $chartOfAccount->description = $validatedData['description'] ?? null;

            $chartOfAccount->save();

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully!',
                'data' => $chartOfAccount,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save account.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $chartOfAccount = ChartsOfAccount::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|string|max:255',
        ]);

        $type = $request->input('type'); // Get the selected type (either existing or new type)

        try {
            // Update the model with the new data
            $chartOfAccount->name = $validatedData['name'];
            $chartOfAccount->type = $type;  // Update the type
            $chartOfAccount->description = $validatedData['description'] ?? null;

            $chartOfAccount->save();

            return response()->json([
                'success' => true,
                'message' => 'Account updated successfully!',
                'data' => $chartOfAccount,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update account.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
