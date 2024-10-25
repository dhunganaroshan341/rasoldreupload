<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditIncomeExpenseButton extends Component
{
    protected $type;

    protected $id;

    /**
     * Create a new component instance.
     */
    public function __construct($incomeId = null, $expenseId = null)
    {
        if ($incomeId != null && $expenseId == null) {
            $this->id = $incomeId;
            $this->type = 'income';
        } elseif ($incomeId == null && $expenseId != null) {
            $this->id = $expenseId;
            $this->type = 'expense';
        } else {
            // Default values when both are null
            $this->id = 1; // or any other default value you prefer
            $this->type = 'income'; // or any other default type
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.edit-income-expense-button')->with([
            'id' => $this->id,
            'type' => $this->type,
        ]);
    }
}
