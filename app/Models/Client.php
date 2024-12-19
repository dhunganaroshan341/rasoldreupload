<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $primary_key = 'id';

    public function services()
    {
        return $this->belongsToMany(OurServices::class, 'client_services', 'client_id', 'service_id');
    }

    public function OurServices()
    {
        return $this->belongsToMany(OurServices::class);
    }

    // public function contract()
    // {
    //     return $this->hasMany(Contract::class);
    // }

    protected $fillable = [
        'name',
        'client_type',
        'address',
        'email',
        'phone',
        'pan_no',
        'hosting_service',
        'email_service',
    ];

    public function clientServices()
    {
        return $this->hasMany(ClientService::class, 'client_id', 'id');
    }

    public function ledgers()
    {
        return $this->hasMany(Ledger::class);
    }

    public function invoice()
    {
        return $this->hasMany(Invoice::class, 'client_id', 'id');
    }

    // Method to check if any ClientService has income// Check if any related ClientService has income
    public function hasIncome()
    {
        // Check if any ClientService has related Income records
        return $this->clientServices()->whereHas('incomes')->exists();
    }
}
