<?php

namespace App\View\Components;

use App\Models\Client;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ExpenseCreationModal extends Component
{
    /**
     * Create a new component instance.
     */
    public $clientId;

    public $clientServices;

    public function __construct($clientId)
    {
        $this->clientId = $clientId;
        $this->clientServices = Client::find($clientId)->clientServices; // Assuming the relationship is defined
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.expense-creation-modal');
    }
}
