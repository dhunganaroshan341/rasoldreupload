<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\OurServices;
use App\Models\Contract;

class CustomContractController extends Controller
{
    public function create()
    {
        return view('dashboard.contracts.create_custom');
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'client_name' => 'required|string|max:255',
        'service_type' => 'required|string|max:255',
        'remarks' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'currency' => 'required|string',
        'status' => 'required|string',
        'advance_amount' => 'nullable|numeric',
        'duration' => 'required|integer',
        'duration_type' => 'required|string|max:255',
        'start_date' => 'nullable|date',
    ]);

    // Create or find the client
    $client = Client::firstOrCreate(['name' => $validatedData['client_name']]);

    // Create or find the service
    $service = OurServices::firstOrCreate(['name' => $validatedData['service_type']]);

    // Store the contract with the related client and service IDs
    Contract::create([
        'name' => $validatedData['name'],
        'client_id' => $client->id,
        'service_id' => $service->id,
        'remarks' => $validatedData['remarks'] ?? null,
        'price' => $validatedData['price'],
        'currency' => $validatedData['currency'],
        'advance_amount' => $validatedData['advance_amount'] ?? null,
        'duration' => $validatedData['duration'],
        'duration_type' => $validatedData['duration_type'],
        'status' =>$validatedData[ 'status' ],
        'start_date' => $validatedData['start_date'] ?? null,
    ]);

    return redirect()->route('contracts.create.custom')->with('success', 'Custom contract created successfully.');
}

}
