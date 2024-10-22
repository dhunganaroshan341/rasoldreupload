<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';

    // Corrected primary key declaration
    protected $primaryKey = 'id';

    protected $fillable = [
        'expense_source',
        'client_service_id',
        'source_type',
        'transaction_date',
        'amount',
        'medium',
        'remarks',
    ];

    // Define the relationship with ClientService
    public function clientService()
    {
        return $this->belongsTo(ClientService::class, 'client_service_id', 'id');
    }

    protected static function booted()
    {
        // Create Ledger when a new Expense is created
        static::created(function ($expense) {
            $clientService = $expense->clientService;

            // Ensure clientService exists before trying to access client_id
            if ($clientService) {
                // Create a new Ledger entry
                Ledger::create([
                    'client_id' => $clientService->client_id, // Use client_id from ClientService
                    'client_service_id' => $expense->client_service_id, // Store client_service_id in Ledger
                    'transaction_type' => 'expense', // It's an expense
                    'source' => $expense->expense_source,
                    'transaction_date' => $expense->transaction_date,
                    'amount' => $expense->amount,
                    'medium' => $expense->medium,
                    'expense_id' => $expense->id,
                ]);
            } else {
                // Log error if clientService not found
                Log::error('ClientService not found for Expense ID: '.$expense->id);
            }
        });

        // Update Ledger when Expense is updated
        static::updated(function ($expense) {
            $clientService = $expense->clientService;

            if ($clientService) {
                // Try to find the existing Ledger entry based on client_service_id
                $ledger = Ledger::where([
                    'client_service_id' => $expense->client_service_id,
                    'transaction_type' => 'expense',
                    'source' => $expense->expense_source,
                ])->first();

                if ($ledger) {
                    // Update the existing Ledger entry
                    $ledger->update([
                        'client_id' => $clientService->client_id,  // Use client_id from ClientService
                        'client_service_id' => $expense->client_service_id,
                        'transaction_date' => $expense->transaction_date,
                        'amount' => $expense->amount,
                        'medium' => $expense->medium,
                        'expense_id' => $expense->id,
                    ]);
                } else {
                    // If no Ledger entry is found, create a new one
                    Ledger::create([
                        'client_id' => $clientService->client_id, // Use client_id from ClientService
                        'client_service_id' => $expense->client_service_id, // Store client_service_id in Ledger
                        'transaction_type' => 'expense', // It's an expense
                        'source' => $expense->expense_source,
                        'transaction_date' => $expense->transaction_date,
                        'amount' => $expense->amount,
                        'medium' => $expense->medium,
                    ]);

                    Log::warning('Created new Ledger entry during Expense update for ClientService ID: '.$clientService->id);
                }
            } else {
                // Log error if clientService not found
                Log::error('ClientService not found for Expense ID: '.$expense->id);
            }
        });
    }
}
