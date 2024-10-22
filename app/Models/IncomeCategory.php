<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeCategory extends Model
{
    use HasFactory;

    protected $table = 'income_categories';

    // Corrected primary key declaration
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'name'];
}
