<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractTransaction extends Model
{
    use HasFactory;
    protected $table = 'contract_transactions';
    protected $fillable = [];
    protected $primary_key= "id";

}
