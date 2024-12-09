<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientServiceRequest;
use App\Http\Requests\UpdateClientServiceRequest;
use App\Models\Client;
use App\Models\ClientService;
use App\Models\OurServices; // Assuming you have categories for services
use App\Services\BillingService;
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

    public function createSingleClientService()
    {

        // Retrieve all categories for the dropdown
        $categories = OurServices::all();
        $clients = Client::all();
        $ourServices = OurServices::all();

        // Return the create form view
        return view('dashboard.clientservices.create', compact('categories', 'clients', 'ourServices'));

    }

    public function storeSingleClientService(StoreClientServiceRequest $request)
    {
        try {
            // Retrieve validated data
            $validatedData = $request->validated();

            // Check if duration and duration_type are provided to calculate the billing dates
            if (isset($validatedData['duration']) && isset($validatedData['duration_type'])) {
                // Calculate billing end date
                $billingDates = BillingService::calculateBillingDates(
                    $validatedData['billing_start_date'],
                    $validatedData['duration'],
                    $validatedData['duration_type']
                );

                // Add calculated billing end date to data
                $validatedData['billing_end_date'] = $billingDates['billing_end_date'];
            }
            // dd($validatedData);
            // Store the client service in the database
            $clientService = ClientService::create($validatedData);

            // Return success response
            return redirect()->route('createSingleClientService.create')
                ->with('success', 'Client service created successfully.');
        } catch (\Exception $e) {
            // Handle unexpected errors
            return redirect()->back()->withErrors([
                'error' => 'An error occurred while creating the client service: '.$e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created client service in storage.
     */
    // public function storeSingleClientService(Request $request)
    // {
    //     dd($request);
    // }

    public function store(Request $request)
    {
        // Validate input data
        $validatedData = $this->validateClientService($request);

        // Calculate the billing end date using the BillingService
        try {
            $billingDates = BillingService::calculateBillingDates(
                $validatedData['billing_start_date'],
                $validatedData['duration'],
                $validatedData['duration_type']
            );
            // Add the billing end date to the validated data
            $validatedData['billing_end_date'] = $billingDates['billing_end_date'];
        } catch (\Exception $e) {
            // Handle the error gracefully (you can redirect or throw an error)
            return back()->withErrors(['error' => 'Invalid duration or duration type.']);
        }

        // Set remaining_amount (assumed default behavior)
        $validatedData['remaining_amount'] = $request->input('amount');
        $validatedData['status'] = 'active';
        // Store the client service in the database
        ClientService::create($validatedData);

        // Redirect to the client service index page with a success message
        return redirect()->route('ClientServices.index', ['client_id' => $validatedData['client_id']])
            ->with('success', 'Client service created successfully.');
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
    public function update(UpdateClientServiceRequest $request, $id)
    {
        // Validate input data
        $validatedData = $this->validateClientService($request);

        // Calculate the billing end date using the BillingService
        try {
            $billingDates = BillingService::calculateBillingDates(
                $validatedData['billing_start_date'],
                $validatedData['duration'],
                $validatedData['duration_type']
            );
            // Add the billing end date to the validated data
            $validatedData['billing_end_date'] = $billingDates['billing_end_date'];
        } catch (\Exception $e) {
            // Handle the error gracefully (you can redirect or throw an error)
            return back()->withErrors(['error' => 'Invalid duration or duration type.']);
        }

        // Find the client service by ID
        $clientService = ClientService::findOrFail($id);

        // Update the client service with new data
        $clientService->update($validatedData);

        // Redirect back with a success message
        return redirect()->route('ClientServices.index', ['client_id' => $validatedData['client_id']])
            ->with('success', 'Client service updated successfully.');
    }

    /**
     * Remove the specified client service from storage.
     */
    public function destroy($client_id)
    {
        try {
            // Find and delete the client service by ID
            ClientService::findOrFail($client_id)->delete();

            return redirect()->route('ClientServices.index')->with('success', 'Client service deleted.');
        } catch (\Exception $e) {
            return redirect()->route('ClientServices.index')->with('error', 'Error deleting client service.');
        }
    }

    // This is your reusable validation method
    private function validateClientService($request)
    {
        return $request->validate([
            'client_id' => 'required|integer',
            'service_id' => 'required|integer',
            'duration' => 'nullable|integer',
            'duration_type' => 'nullable|string',
            'hosting_service' => 'nullable|string|max:255',
            'email_service' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'nullable|numeric|min:0',
            'billing_start_date' => 'required|date|',
            'billing_period_frequency' => 'nullable|in:one-time annually,semi-annually,quarterly,monthly', // Use `in` for validation
            'advance_paid' => 'nullable|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',  // Allow decimal values with up to 2 decimal places
        ]);
    }
}
