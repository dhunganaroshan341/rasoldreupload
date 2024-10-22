<?php

namespace App\Http\Controllers;

use App\Models\ClientService;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;

class NewTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all client services
        $clientServices = ClientService::with(['client', 'service'])->get();

        // Get all incomes and expenses
        $incomes = Income::all();
        $expenses = Expense::all();

        // Transform and merge incomes and expenses
        $transactions = $incomes->map(function ($income) {
            return [
                'type' => 'income',
                'id' => $income->id,
                'transaction_date' => $income->date,
                'amount' => $income->amount,
                'medium' => $income->medium,
                'remarks' => $income->remarks,
                'client_service' => $income->income_source_id,
            ];
        })->merge(
            $expenses->map(function ($expense) {
                return [
                    'type' => 'expense',
                    'id' => $expense->id,
                    'transaction_date' => $expense->date,
                    'amount' => $expense->amount,
                    'medium' => $expense->medium,
                    'remarks' => $expense->remarks,
                    'client_service' => $expense->expense_source_id,
                ];
            })
        );

        return view('dashboard.transactions.new_index', compact('clientServices', 'transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
