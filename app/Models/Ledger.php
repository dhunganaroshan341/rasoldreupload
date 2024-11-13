<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    protected $table = 'ledgers';

    protected $fillable = [
        'client_id',
        'transaction_type',
        'source',
        'transaction_date',
        'amount',
        'medium',
        'client_service_id',
        'coa_id',
        'remark',
        'income_id',
        'expense_id',
    ];

    // Define the relationship with the Client model
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Define the relationship with the ClientService model through the client_id
    // Define the relationship with the ClientService model
    public function clientService()
    {
        return $this->belongsTo(ClientService::class, 'client_service_id', 'id');
    }

    // In Ledger model
    public function income()
    {
        return $this->belongsTo(Income::class, 'income_id'); // 'income_id' is the foreign key in the Ledger model
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class, 'expense_id'); // 'income_id' is the foreign key in the Ledger model
    }
}
