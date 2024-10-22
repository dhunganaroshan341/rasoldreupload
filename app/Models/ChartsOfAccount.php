<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartsOfAccount extends Model
{
    use HasFactory;

    protected $table = 'charts_of_account';

    protected $primary_key = 'id';

    protected $fillable = [
        'name',
        'type',
        'description',
    ];
}
