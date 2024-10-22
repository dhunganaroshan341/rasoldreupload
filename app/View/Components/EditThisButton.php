<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditThisButton extends Component
{
    protected $route;

    protected $routeIdVariable;

    protected $routeId;

    protected $label;

    /**
     * Create a new component instance.
     */
    public function __construct($label, $route, $routeIdVariable, $routeId)
    {
        //
        $this->route = $route;
        $this->routeId = $routeId;
        $this->routeIdVariable = $routeIdVariable;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.edit-this-button', ['label' => $this->label, 'route' => $this->route, 'routeIdVariable' => $this->routeIdVariable, 'routeId' => $this->routeId]);
    }
}
