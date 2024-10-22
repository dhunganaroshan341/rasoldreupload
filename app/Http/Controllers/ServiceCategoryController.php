<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceCategory;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $ServiceCategory = ServiceCategory::all();
        return view('dashboard.ServiceCategory.index',compact('ServiceCategory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $parentCategories = ServiceCategory::all(); // Fetch all parent categories

    return view('dashboard.ServiceCategory.create', compact('parentCategories'));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'duration' => 'nullable|integer',
            'category' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $service = ServiceCategory::create($validatedData);

        return redirect()->route('ServiceCategory.index')->with('success', 'Service created successfully.');
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
    public function edit(string $id)
    {
        //
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
