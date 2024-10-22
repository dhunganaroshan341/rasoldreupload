<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogAuditActivity
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $user = Auth::user();
        if ($user && $this->shouldLogActivity($request)) {
            // Log the activity
            $this->logActivity($request, $user);
        }

        return $response;
    }

    protected function shouldLogActivity($request)
    {
        // Determine if the request method is one that should be logged
        return in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']);
    }

    protected function logActivity($request, $user)
    {
        // Determine model and action
        $model = $this->getModelFromRoute($request);
        $action = $request->method();

        // Log the activity without model_id initially
        $logData = [
            'user_id' => $user->id,
            'action' => $action,
            'model' => $model,
            'changes' => json_encode($request->all()), // Serialize request data as JSON
        ];

        // Determine model_id based on action
        if ($action === 'PUT' || $action === 'PATCH' || $action === 'DELETE') {
            // For PUT, PATCH, DELETE requests, attempt to extract model_id from route parameters
            $modelId = $request->route()->parameter('id');
            if ($modelId) {
                $logData['model_id'] = $modelId;
            }
        } elseif ($action === 'POST') {
            // For POST requests, dynamically determine the last inserted ID
            $lastId = $this->getLastInsertedId($model);
            $modelId = $lastId ? $lastId + 1 : 1; // Increment last ID or start at 1 if no records
            $logData['model_id'] = $modelId;
        }

        // Log the activity
        AuditLog::create($logData);
    }

    protected function getModelFromRoute($request)
    {
        // Extract model name from the route segments
        $segments = $request->segments();
        $model = isset($segments[1]) ? ucfirst($segments[1]) : 'Unknown';

        // Validate if the model class exists
        if (! class_exists('App\Models\\'.$model)) {
            $model = 'Unknown'; // Default to 'Unknown' if the model class doesn't exist
        }

        return $model;
    }

    protected function getLastInsertedId($model)
    {
        // Use Laravel's Schema Builder to dynamically get the last inserted ID for $model
        $tableName = (new $model)->getTable();

        return DB::table($tableName)->max('id');
    }
}
