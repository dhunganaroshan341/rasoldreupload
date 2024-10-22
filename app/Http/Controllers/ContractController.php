<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\OurServices;
use App\Services\ContractServiceProvider;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $crud_item;

    protected $fields;

    public function __construct()
    {
        $this->crud_item = 'contracts'; // Example: 'contracts', 'services', etc.
        $this->fields = ['id', 'name', 'description', 'price', 'start_date', 'end_date', 'duration', 'duration_type', 'status'];
    }

    public function index()
    {
        $crud_item = $this->crud_item;
        $fields = $this->fields;
        $route = 'contracts';

        $items = Contract::paginate(10); // Paginate with 10 items per page

        return view('layouts.crud', compact('crud_item', 'fields', 'items', 'route'));
    }

    // Other controller methods...

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $our_services = OurServices::all();
        $clients = Client::all();

        return view('dashboard.contracts.create', compact('our_services', 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Initialize validation rules
        $rules = [
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string',
            'advance_amount' => 'nullable|numeric',
            'duration' => 'required|integer',
            'duration_type' => 'required|string|max:255',
            'start_date' => 'nullable|date',
        ];

        // Check for new client name
        if ($request->has('new_client_name')) {
            $rules['new_client_name'] = 'required|string|max:255';
        } else {
            $rules['client_id'] = 'required|exists:clients,id';
        }

        // Check for new service name
        if ($request->has('new_service_name')) {
            $rules['new_service_name'] = 'required|string|max:255';
        } else {
            $rules['service_id'] = 'required|exists:our_services,id';
        }

        // Validate request data
        $validatedData = $request->validate($rules);

        // Handle client creation or retrieval
        if ($request->has('new_client_name')) {
            $client = Client::create(['name' => $validatedData['new_client_name']]);
        } else {
            $client = Client::find($validatedData['client_id']);
            if (! $client) {
                return redirect()->back()->withErrors(['client_id' => 'The selected client does not exist.']);
            }
        }

        // Handle service creation or retrieval
        if ($request->has('new_service_name')) {
            $service = OurServices::create(['name' => $validatedData['new_service_name']]);
        } else {
            $service = OurServices::find($validatedData['service_id']);
            if (! $service) {
                return redirect()->back()->withErrors(['service_id' => 'The selected service does not exist.']);
            }
        }

        // Prepare data for creating a contract
        $contractData = [
            'name' => $validatedData['name'] ?? '', // Ensure name is provided or default to empty string
            'service_id' => $service->id,
            'client_id' => $client->id,
            'remarks' => $validatedData['remarks'] ?? null, // Adjust as needed
            'price' => $validatedData['price'],
            'currency' => $validatedData['currency'],
            'advance_amount' => $validatedData['advance_amount'] ?? null,
            'duration' => $validatedData['duration'],
            'duration_type' => $validatedData['duration_type'],
            'start_date' => $validatedData['start_date'] ?? null,
            'end_date' => ContractServiceProvider::calculateEndDate(
                $validatedData['start_date'],
                $validatedData['duration'],
                $validatedData['duration_type']
            ),
        ];

        // Store the contract
        Contract::create($contractData);

        // Redirect with success message
        return redirect()->route('contracts.index')->with('success', 'Contract created successfully.');
    }
    // public function store(Request $request)
    // {
    //     dd($request->all());
    // }

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
    public function edit(string $id)
    {
        //
        $our_services = OurServices::all();
        $clients = Client::all();

        $contract = Contract::find($id)->first();

        return view('dashboard.contracts.edit', compact('clients', 'our_services', 'contract'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
