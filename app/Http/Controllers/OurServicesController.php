<?php

namespace App\Http\Controllers;

use App\Models\OurServices;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class OurServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $OurServices = OurServices::all();

        return view('dashboard.OurServices.index', compact('OurServices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ServiceCategory::all();

        return view('dashboard.OurServices.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1',
            'duration' => 'nullable|integer|min:1',
            'duration_type' => 'nullable|in:days,weeks,months',
            'category' => 'nullable|integer|exists:service_categories,id',
            'status' => 'required|in:active,inactive',
        ]);

        // // If category is "new", you should handle that separately
        // if ($request->category === 'new') {
        //     // Handle adding a new category logic here
        // } else {
        //     $validatedData['category_id'] = $validatedData['category'];
        //     unset($validatedData['category']);
        // }

        OurServices::create($validatedData);

        return redirect()->route('OurServices.index')->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $service = OurServices::findOrFail($id);

        return view('dashboard.OurServices.show', [
            'title' => 'Service Information',
            'data' => $service->toArray(),
            'editRoute' => 'OurServices.edit',
            'service' => $service,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $our_service = OurServices::find($id);

        if (! $our_service) {
            return redirect()->route('OurServices.index')->with('error', 'Service not found.');
        }

        $categories = ServiceCategory::all();

        return view('dashboard.OurServices.edit', compact('our_service', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1',
            'duration' => 'nullable|integer|min:1',
            'duration_type' => 'nullable|in:days,weeks,months',
            'category' => 'nullable|integer|exists:service_categories,id',
            'status' => 'required|in:active,inactive',
        ]);

        $service = OurServices::find($id);

        if (! $service) {
            return redirect()->route('OurServices.index')->with('error', 'Service not found.');
        }

        // Handle new category logic
        if ($request->category === 'new') {
            // Handle adding a new category logic here
        } else {
            // $validatedData['category_id'] = $validatedData['category'];
            // unset($validatedData['category']);
        }

        $service->update($validatedData);

        return redirect()->route('OurServices.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $service = OurServices::findOrFail($id);
            $service->delete();

            return redirect()->route('OurServices.index')->with('success', 'Service deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('OurServices.index')->with('error', 'Error deleting service');
        }
    }
}
