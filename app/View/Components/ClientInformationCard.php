<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ClientInformationCard extends Component
{
    public $client;

    public $clientServices;

    /**
     * Create a new component instance.
     */
    public function __construct($client, $clientServices)
    {

        $this->client = $client;
        $this->clientServices = $clientServices;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.client-information-card');
    }
}
