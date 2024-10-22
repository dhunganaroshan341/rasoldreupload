<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'contracts';

    protected $fillable = [
        'id', 'client_id', 'service_id',
        'name', 'duration', 'duration_type', 'start_date',
        'end_date', 'price', 'advance_amount', 'currency',
        'remarks', 'status',
    ];

    protected $primary_key = 'id';

    public function Client()
    {
        return $this->belongsTo(Client::class, 'id');
    }

    public function Service()
    {
        return $this->belongsTo(OurServices::class, 'id');
    }
}
