<?php

namespace App\Observers;

use App\Models\Log; // Import the Log model
use Illuminate\Support\Facades\Auth;

class ModelObserver
{
    /**
     * Handle the model "created" event.
     */
    public function created($model): void
    {
        $this->logAction($model, 'created');
    }

    /**
     * Handle the model "updated" event.
     */
    public function updated($model): void
    {
        $this->logAction($model, 'updated');
    }

    /**
     * Handle the model "deleted" event.
     */
    public function deleted($model): void
    {
        $this->logAction($model, 'deleted');
    }

    /**
     * Handle the model "restored" event.
     */
    public function restored($model): void
    {
        $this->logAction($model, 'restored');
    }

    /**
     * Handle the model "force deleted" event.
     */
    public function forceDeleted($model): void
    {
        $this->logAction($model, 'force_deleted');
    }

    /**
     * Log the action to the logs table.
     */
    protected function logAction($model, string $action): void
    {
        // Determine user ID: use the authenticated user's ID or default to 1
        $userId = Auth::check() ? Auth::id() : 1; // Change '1' if you have a different default

        Log::create([
            'user_id' => $userId, // Get the current authenticated user ID or default to 1
            'action' => $action, // Action performed
            'model' => get_class($model), // Model class name
            'model_id' => $model->id, // ID of the affected model
            'changes' => json_encode($model->getChanges()), // Changes made
            'url' => request()->fullUrl(), // Optional URL field
        ]);
    }
}
