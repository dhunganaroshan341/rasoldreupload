<?php

namespace App\Providers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class ActivityLogServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Listen for all model events (create, update, delete)
        Event::listen('*', function ($eventName, $data) {
            // Check if the event is related to a model
            if (in_array('Illuminate\Database\Eloquent\Model', class_implements($data[0]))) {
                $model = $data[0];

                // Check if the model has been created, updated, or deleted
                if ($eventName === 'eloquent.created: '.get_class($model)) {
                    $this->logActivity($model, 'created');
                } elseif ($eventName === 'eloquent.updated: '.get_class($model)) {
                    $this->logActivity($model, 'updated');
                } elseif ($eventName === 'eloquent.deleted: '.get_class($model)) {
                    $this->logActivity($model, 'deleted');
                }
            }
        });
    }

    /**
     * Log activity to the ActivityLog model.
     */
    protected function logActivity($model, $action)
    {
        ActivityLog::create([
            'user_id' => Auth::id(), // Get the authenticated user's ID
            'model' => get_class($model), // Store the model class name
            'model_id' => $model->id, // The affected record ID
            'action' => $action, // Action performed (created, updated, deleted)
        ]);
    }
}
