<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\OurServices; // Assuming you have categories for services
use App\Services\ClientServiceTransactionProvider;
use Illuminate\Http\Request;

class ClientServiceController extends Controller
{
    /**
     * Display a listing of the client services.
     */
    public function index($client_id)
    {
        // Retrieve the client along with their services
        $client = Client::with('clientServices')->find($client_id);

        // Check if the client exists
        if (! $client) {
            return redirect()->back()->with('error', 'Client not found.');
        }

        // Return the view with client services data and client information
        return view('dashboard.clientservices.index', compact('client'));
    }

    /**
     * Show the details of a specific client service.
     */
    public function show($id)
    {
        $clientService = ClientService::findOrFail($id);

        return view('dashboard.clientservices.show', compact('clientService'));
    }

    /**
     * Show the form for creating a new client service.
     */
    public function create()
    {
        // Retrieve all categories for the dropdown
        $categories = OurServices::all();

        // Return the create form view
        return view('dashboard.clientservices.form', compact('categories'));
    }

    /**
     * Store a newly created client service in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'client_id' => 'required|integer',
            'service_id' => 'required|integer',
            'duration' => 'nullable|integer',
            'duration_type' => 'nullable|string',
            'hosting_service' => 'nullable|string|max:255',
            'email_service' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'nullable|integer|min:0',
        ]);
        // to store remiang default and the  oursourced to zero when first creatig this
        $validatedData['remaining_amount'] = $request->input('amount');
        // $validatedData['outsourced_amount'] = 0;

        // Create a new client service
        ClientService::create($validatedData);

        // Redirect back to the index with a success message
        return redirect()->route('ClientServices.index', ['client_id' => $validatedData['client_id']])->with('success', 'Client service created successfully.');
    }

    /**
     * Show the form for editing the specified client service.
     */
    public function edit($client_service_id)
    {
        // Retrieve the client service by ID
        $clientService = ClientService::find($client_service_id);

        // Check if the client service exists
        if (! $clientService) {
            return redirect()->back()->with('error', 'Client service not found.');
        }

        // Retrieve categories for dropdown
        $categories = OurServices::all();

        // dd($clientService);
        // Return the edit form view with the client service data
        return view('dashboard.clientservices.form', compact('clientService', 'categories'));
    }

    /**
     * Update the specified client service in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'client_id' => 'required|integer',
            'service_id' => 'required|integer',
            'duration' => 'nullable|integer',
            'duration_type' => 'string|nullable',
            'hosting_service' => 'nullable|string|max:255',
            'email_service' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'nullable|integer|min:0',
        ]);

        // Find the existing client service by ID
        $clientService = ClientService::find($id);

        // Check if the client service exists
        if (! $clientService) {
            return redirect()->route('ClientServices.index', ['client_id' => $validatedData['client_id']])
                ->with('error', 'Client service not found.');
        }

        // Store the old amount before updating
        $oldAmount = $clientService->amount;

        // Update the client service with validated data
        $clientService->fill($validatedData); // Fill the model with new data

        // Check if the amount has changed
        if ($clientService->isDirty('amount')) {
            // Calculate the new remaining amount if the amount has changed
            $remainingAmount = ClientServiceTransactionProvider::getRemainingAmount($clientService);

            // Update the remaining amount in the client service
            $clientService->remaining_amount = $remainingAmount;
        }

        // Save the updated client service
        $clientService->save();

        // Redirect back to the index with a success message
        return redirect()->route('ClientServices.index', ['client_id' => $validatedData['client_id']])
            ->with('success', 'Client service updated successfully.');
    }

    /**
     * Remove the specified client service from storage.
     */
    public function destroy($id)
    {
        try {
            // Find and delete the client service by ID
            ClientService::findOrFail($id)->delete();

            return redirect()->route('ClientServices.index')->with('success', 'Client service deleted.');
        } catch (\Exception $e) {
            return redirect()->route('ClientServices.index')->with('error', 'Error deleting client service.');
        }
    }
}
