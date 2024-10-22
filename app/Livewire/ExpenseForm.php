<?php

namespace App\Livewire;

use App\Models\ClientService;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ExpenseForm extends Component
{
    public $expense_type;

    public $outsourcing_expense;

    public $custom_expense;

    public $transaction_date;

    public $amount;

    public $medium;

    public $remarks;

    public $clientServices;

    public function mount()
    {
        $this->clientServices = ClientService::with('client', 'service')->get();
    }

    public function render()
    {
        return view('livewire.expense-form');
    }

    // public function updatedExpenseType($value)
    // {
    //     if ($value === 'outsourcing') {
    //         $this->emit('show-outsourcing-expense');
    //     } elseif ($value === 'custom') {
    //         $this->emit('show-custom-expense');
    //     } else {
    //         $this->emit('hide-all');
    //     }
    // }

    public function submit()
    {
        $this->validate([
            'expense_type' => 'required',
            'outsourcing_expense' => 'nullable|exists:client_services,id',
            'custom_expense' => 'nullable|string',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'medium' => 'required',
            'remarks' => 'nullable|string',
        ]);

        // Make a POST request to your controller
        $response = Http::post(route('expenses.store'), [
            'expense_type' => $this->expense_type,
            'outsourcing_expense' => $this->outsourcing_expense,
            'custom_expense' => $this->custom_expense,
            'transaction_date' => $this->transaction_date,
            'amount' => $this->amount,
            'medium' => $this->medium,
            'remarks' => $this->remarks,
        ]);

        if ($response->successful()) {
            session()->flash('message', 'Expense has been successfully recorded.');
            $this->reset();
        } else {
            session()->flash('error', 'There was an error recording the expense.');
        }
    }
}
