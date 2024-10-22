<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReccuringTransaction extends Model
{
    use HasFactory;
    protected $table = 'reccurring_transactions';
    protected $fillable = [];
    protected $primary_key= "id";
}
