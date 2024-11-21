<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutStandingInvoice extends Model
{
    use HasFactory;

    // Define table name if it's not the plural of the model name
    protected $table = 'outstanding_invoices';

    // The primary key for the model (default is 'id', but explicitly specifying it here)
    protected $primaryKey = 'id';

    // Mass assignable attributes (for bulk insert)
    protected $fillable = [
        'client_service_id', 'total_amount', 'prev_remaining_amount', 'all_total', 'paid_amount',
        'remaining_amount', 'discount_amount', 'discount_percentage', 'due_date', 'last_paid',
        'remarks', 'bill_number', 'status', 'all_total_paid',
    ];

    // Cast the 'due_date' and 'last_paid' fields to date
    protected $casts = [
        'due_date' => 'date',         // Cast 'due_date' to a date format
        'last_paid' => 'date',        // Cast 'last_paid' to a date format
    ];

    /**
     * The relationship between OutstandingInvoice and Income (One-to-Many).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function incomes()
    {
        return $this->hasMany(Income::class, 'invoice_id', 'id');
    }

    /**
     * The relationship between OutstandingInvoice and ClientService (Many-to-One).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clientService()
    {
        return $this->belongsTo(ClientService::class, 'client_service_id', 'id');
    }

    /**
     * Accessor to get the calculated total amount due after the discount is applied.
     *
     * @return float
     */
    public function getAmountDueAttribute()
    {
        // Apply discount to total_amount
        $discount = ($this->discount_percentage / 100) * $this->total_amount;

        return $this->total_amount - $discount - $this->discount_amount;
    }

    /**
     * Accessor to get the remaining balance after payments are made.
     *
     * @return float
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->all_total - $this->all_total_paid;
    }
}
