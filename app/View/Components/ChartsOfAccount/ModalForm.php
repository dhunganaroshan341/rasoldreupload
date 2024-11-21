<?php

namespace App\View\Components\ChartsOfAccount;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModalForm extends Component
{
    protected $uniqueAccountTypes;

    /**
     * Create a new component instance.
     */
    public function __construct($uniqueAccountTypes)
    {
        //
        $this->uniqueAccountTypes = $uniqueAccountTypes;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.charts-of-account.modal-form', ['uniqueAccountTypes' => $this->uniqueAccountTypes]);
    }
}
