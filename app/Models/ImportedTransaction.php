<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedTransaction extends Model
{
    use HasFactory;
    protected $table = 'imported_transactions';
    protected $primary_key ='id';
    protected $fillable = ['source','amount','date','type','description','file_name'];
}
