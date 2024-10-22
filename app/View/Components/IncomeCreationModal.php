<?php

namespace App\View\Components;

use App\Models\Client;
use Illuminate\View\Component;

class IncomeCreationModal extends Component
{
    public $clientId;

    public $clientServices;

    public function __construct($clientId)
    {
        $this->clientId = $clientId;
        $this->clientServices = Client::find($clientId)->clientServices; // Assuming the relationship is defined
    }

    public function render()
    {
        return view('components.income-creation-modal');
    }
}
