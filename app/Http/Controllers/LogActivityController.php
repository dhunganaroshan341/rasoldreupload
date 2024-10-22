<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Spatie\Activitylog\Models\Activity;

// class LogActivityController extends Controller
// {
//     public function index(Request $request)
//     {
//         // Fetch all activities or add filtering logic if needed
//         $activities = Activity::with('causer') // Ensure to load the 'causer' relationship
//             ->orderBy('created_at', 'desc')
//             ->get();

//         return view('logactivity.index', compact('activities'));
//     }

//     public function show($id)
//     {
//         // Fetch logs for a specific model (e.g., Income or Expense)
//         $activity = Activity::findOrFail($id);

//         return view('logactivity.show', compact('activity'));
//     }
// }
