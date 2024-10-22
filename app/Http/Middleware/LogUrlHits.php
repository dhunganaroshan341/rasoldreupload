<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogUrlHits
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check if the user is authenticated
        // $userId = Auth::check() ? Auth::id() : 1; // Default to 1 or a specific ID if not authenticated
        // $userId = 1;
        // // Create a log entry
        // Log::create([
        //     'user_id' => $userId,
        //     'action' => 'visit',
        //     'model' => 'URL',
        //     'model_id' => 0, // Default value since null is not allowed
        //     'changes' => 'URL visited',
        //     'url' => $request->fullUrl(),
        // ]);

        return $response;
    }
}
