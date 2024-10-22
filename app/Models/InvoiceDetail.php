<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    use HasFactory;

    protected $table = 'invoice_details';

    protected $primaryKey = 'id';

    protected $fillable = [
        'client_service_id',  // This is actually the client_service_id
        'amount',  // Maps to client_service_id
        'ledger_id',
        'invoice_id',

    ];

    // Define relationship to ClientService
    public function clientServices()
    {
        return $this->belongsTo(ClientService::class, 'client_service_id', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
}
