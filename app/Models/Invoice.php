<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    use HasFactory;

    protected $table = 'invoices';

    protected $primaryKey = 'id';

    protected $fillable = [
        'client_id',  // This is actually the client_service_id
        'total_amount',  // Maps to client_service_id
        'remianing_amount',

    ];

    // Define relationship to ClientService
    public function client()
    {
        return $this->belongsTo(ClientService::class, 'client_id', 'id');
    }

    public function invoiceDetails()
    {
        return $this->hasMany(Invoice::class, 'invoice_id', 'id');
    }
}
