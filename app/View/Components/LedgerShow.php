<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LedgerShow extends Component
{
    protected $client;

    protected $ledgers;

    protected $ledgerCalculationForClient; // Fix the typo

    protected $totalClientServiceAmount;

    /**
     * Create a new component instance.
     */
    public function __construct($client, $ledgers, $ledgerCalculationForClient, $totalClientServiceAmount) // Fix the typo here
    {
        // Assign values to the class properties
        $this->client = $client;
        $this->ledgers = $ledgers;
        $this->ledgerCalculationForClient = $ledgerCalculationForClient; // Fix the typo here
        $this->totalClientServiceAmount = $totalClientServiceAmount;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // dd($this->client); // This will show the client data, and then stop execution

        return view('components.ledger-show', [
            'client' => $this->client,
            'ledgers' => $this->ledgers,
            'ledgerCalculationForClient' => $this->ledgerCalculationForClient,
            'totalClientServiceAmount' => $this->totalClientServiceAmount,
        ]);
    }
}
