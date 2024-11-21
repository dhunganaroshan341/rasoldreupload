<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Dropdown extends Component
{
    public $name;

    public $dropdownItem;

    /**
     * Create a new component instance.
     */
    public function __construct($name, $dropdownItem)
    {
        // Assign the passed data to the component's properties
        $this->name = $name;
        $this->dropdownItem = $dropdownItem;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dropdown');
    }
}
