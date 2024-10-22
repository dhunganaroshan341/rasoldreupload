<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ViewIndex extends Component
{
    /**
     * Create a new component instance.
     */
    private $routeLink;

    private $iconClass;

    private $routeName;

    public function __construct($routeLink, $routeName = null, $iconClass = null)
    {
        //
        $this->routeLink = $routeLink;
        $this->routeName = $routeName;
        $this->$iconClass = $iconClass;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.view-index');
    }
}
