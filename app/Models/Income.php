<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Income extends Model
{
    use HasFactory;

    protected $table = 'incomes';

    protected $primaryKey = 'id';

    protected $fillable = [
        'income_source',  // This is actually the client_service_id
        'income_source_id',  // Maps to client_service_id
        'source_type',
        'transaction_date',
        'amount',
        'medium',
        'remark',
    ];

    // Define relationship to ClientService
    public function clientService()
    {
        return $this->belongsTo(ClientService::class, 'income_source_id', 'id');
    }

    // In Income model
    public function ledger()
    {
        return $this->hasOne(Ledger::class, 'income_id'); // 'income_id' is the foreign key in the Ledger model
    }

    // protected static function booted()
    // {
    //     // Event listener for creating a Ledger entry on Income creation
    //     static::created(function ($income) {
    //         $clientService = $income->clientService;

    //         if ($clientService) {
    //             // Create a new Ledger entry
    //             Ledger::create([
    //                 'client_id' => $clientService->client_id,  // client_id from ClientService
    //                 'client_service_id' => $income->income_source_id,  // client_service_id from Income
    //                 'transaction_type' => 'income',
    //                 'source' => $income->income_source_id,  // This is also the client_service_id
    //                 'transaction_date' => $income->transaction_date,
    //                 'amount' => $income->amount,
    //                 'medium' => $income->medium,
    //             ]);
    //         } else {
    //             Log::error('ClientService not found for Income ID: '.$income->id);
    //         }
    //     });

    //     // Event listener for updating the Ledger entry on Income update
    //     static::updated(function ($income) {
    //         $clientService = $income->clientService;

    //         if ($clientService) {
    //             // Try to find the existing Ledger entry based on client_service_id
    //             $ledger = Ledger::where([
    //                 'client_service_id' => $income->income_source_id,  // Matching by client_service_id
    //                 'transaction_type' => 'income',
    //                 'source' => $income->income_source_id,
    //             ])->first();

    //             if ($ledger) {
    //                 // Update the existing Ledger entry with new values
    //                 $ledger->update([
    //                     'client_id' => $clientService->client_id,  // Keep the client_id the same
    //                     'transaction_date' => $income->transaction_date,
    //                     'amount' => $income->amount,
    //                     'medium' => $income->medium,
    //                 ]);
    //             } else {
    //                 // If no Ledger entry is found, create a new one
    //                 Ledger::create([
    //                     'client_id' => $clientService->client_id,  // Use the same client_id
    //                     'client_service_id' => $income->income_source_id,  // Add client_service_id to Ledger
    //                     'transaction_type' => 'income',
    //                     'source' => $income->income_source_id,  // client_service_id again
    //                     'transaction_date' => $income->transaction_date,
    //                     'amount' => $income->amount,
    //                     'medium' => $income->medium,
    //                 ]);

    //                 Log::warning('Created new Ledger entry during Income update for ClientService ID: '.$clientService->id);
    //             }
    //         } else {
    //             Log::error('ClientService not found for Income ID: '.$income->id);
    //         }
    //     });
    // }
}
