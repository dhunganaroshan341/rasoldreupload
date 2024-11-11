<?php

namespace App\View\Components\ChartsOfAccount;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DefaultTable extends Component
{
    /**
     * Create a new component instance.
     */
    protected $chartsOfAccount;

    public function __construct($chartsOfAccount)
    {
        //
        $this->chartsOfAccount = $chartsOfAccount;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.charts-of-account.default-table', ['chartsOfAccount' => $this->chartsOfAccount]);
    }
}
