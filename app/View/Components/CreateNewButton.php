<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CreateNewButton extends Component
{
    /**
     * Create a new component instance.
     */
    public $routeName;

    public $route;

    public function __construct($routeName, $route)
    {
        //
        $this->route = $route;
        $this->routeName = $routeName;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.create-new-button');
    }
}
