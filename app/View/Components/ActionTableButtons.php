<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ActionTableButtons extends Component
{
    public $params; // For handling dynamic params

    public $indexRoute;

    public $indexRouteId;

    public $indexRouteIdVariable;

    public $showRoute;

    public $showRouteId;

    public $showRouteIdVariable;

    public $editRoute;

    public $editRouteId;

    public $editRouteIdVariable;

    public $destroyRoute;

    public $destroyRouteId;

    public $destroyRouteIdVariable;

    public $route;

    public $routeId;

    public $routeIdVariable;

    /**
     * Create a new component instance.
     *
     * @param  array  $parameters  Associative array of key-value pairs.
     */
    public function __construct(array $parameters = [])
    {
        // Default values for parameters
        $defaults = [
            'indexRoute' => null,
            'indexRouteId' => null,
            'indexRouteIdVariable' => null,

            'showRoute' => null,
            'showRouteId' => null,
            'showRouteIdVariable' => null,

            'editRoute' => null,
            'editRouteId' => null,
            'editRouteIdVariable' => null,

            'destroyRoute' => null,
            'destroyRouteId' => null,
            'destroyRouteIdVariable' => null,

            'route' => null,
            'routeId' => null,
            'routeIdVariable' => null,
        ];

        // Merge the provided parameters with defaults
        $parameters = array_merge($defaults, $parameters);

        // Assign values to class properties dynamically
        foreach ($parameters as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        $this->params = $parameters; // Assign full array of params for flexibility
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.action-table-buttons');
    }
}
