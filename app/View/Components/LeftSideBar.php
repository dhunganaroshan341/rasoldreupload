<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LeftSideBar extends Component
{
    /**
     * Create a new component instance.
     */
    public $menuItems;

    public function __construct($menuItems)
    {
        $this->menuItems = $menuItems;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.left-side-bar');
    }
}
