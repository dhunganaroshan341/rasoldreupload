<?php

namespace App\Imports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransactionsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Assuming your Transaction model has properties like 'amount', 'description', etc.
        return new Transaction([
            'income_amount' => $row['Income Amount'],
            'income_source' => $row['Income Source'],
            'income_date' => $row['Income Date'],
            'expense_amount' => $row['Expense Amount'],
            'expense_source' => $row['Expense Source'],
            'expense_date' => $row['Expense Date'],
            // Map other columns as needed
        ]);
    }
}
