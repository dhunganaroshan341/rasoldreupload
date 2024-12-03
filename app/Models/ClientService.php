<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ClientService extends Pivot
{
    use HasFactory;

    protected $table = 'client_services';

    protected $primary_key = 'id';

    protected $fillable = [
        'client_id',
        'billing_end_date',
        'billing_start_date',
        'billing_period_frequency',
        'advance_paid',
        'remaining_amount',
        'outsourced_amount',
        'amount',
        'service_id',
        'client_id',
        'hosting_service',
        'email_service',
        'name',
        'duration',
        'duration_type',
        'description',
        'hosting_service',
        'email_service',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    // Define the relationship with the OurServices model
    public function service()
    {
        return $this->belongsTo(OurServices::class, 'service_id', 'id');  // Specify the fields that need to be accessed on the pivot table

    }

    // public function client()
    // {
    //     return $this->belongsTo(Client::class);
    // }

    public function ourService()
    {
        return $this->belongsTo(OurServices::class, 'service_id');
    }

    public function incomes()
    {
        return $this->hasMany(Income::class, 'income_source_id', 'id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'client_service_id', 'id');
    }

    // Define relationship with Ledger based on client_id
    // Define the relationship with the Ledger model
    public function ledgers()
    {
        return $this->hasMany(Ledger::class, 'client_service_id', 'id');
    }

    // invoices
    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'client_service_id', 'id');
    }

    public function outstandingInvoices()
    {
        return $this->hasMany(OutStandingInvoice::class, 'client_service_id', 'id');
    }
}
