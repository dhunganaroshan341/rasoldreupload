<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LedgerSummaryModal extends Component
{
    public $totalIncome;

    public $totalExpense;

    public $balance;

    public $ledgerEntries;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($totalIncome, $totalExpense, $balance, $ledgerEntries)
    {
        $this->totalIncome = $totalIncome;
        $this->totalExpense = $totalExpense;
        $this->balance = $balance;
        $this->ledgerEntries = $ledgerEntries;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('components.ledger-summary-modal');
    }
}
