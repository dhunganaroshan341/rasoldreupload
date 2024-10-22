<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeInvoice extends Model
{
    use HasFactory;

    protected $table = 'income_invoices';

    protected $primaryKey = 'id';

    protected $fillable = [
        'income_source',  // This is actually the client_service_id
        'income_source_id',  // Maps to client_service_id
        'source_type',
        'transaction_date',
        'amount',
        'medium',
    ];
}
