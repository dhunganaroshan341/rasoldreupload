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
}
